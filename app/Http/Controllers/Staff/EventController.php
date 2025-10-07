<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Notifications\EventCreatedNotification;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $now = now();

        $query = Event::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        $events = $query->orderBy('start_date', 'asc')->paginate(10);

        // Apply filters with proper chronological ordering
        if ($filter === 'upcoming') {
            // Events that haven't started yet
            $query->where('start_date', '>', $now)
                  ->orderBy('start_date', 'asc');
        } else if ($filter === 'current') {
            // Events that are currently happening
            $query->where('start_date', '<=', $now)
                  ->where('end_date', '>=', $now)
                  ->orderBy('start_date', 'asc');
        } else if ($filter === 'past') {
            // Events that have ended
            $query->where('end_date', '<', $now)
                  ->orderBy('end_date', 'desc');
        } else {
            // Default: all events in chronological order (upcoming -> current -> past)
            $query->orderByRaw("
                CASE
                    WHEN start_date > ? THEN 1
                    WHEN start_date <= ? AND end_date >= ? THEN 2
                    ELSE 3
                END, start_date ASC", [$now, $now, $now]);
        }

        // Paginate the results
        $events = $query->paginate(10);

        // Add status indicators to events
        $events->getCollection()->transform(function ($event) use ($now) {
            if ($event->start_date > $now) {
                $event->time_status = 'upcoming';
                $event->time_label = 'Upcoming';
                $event->time_class = 'badge-primary';
            } elseif ($event->start_date <= $now && $event->end_date >= $now) {
                $event->time_status = 'current';
                $event->time_label = 'Happening Now';
                $event->time_class = 'badge-success';
            } else {
                $event->time_status = 'past';
                $event->time_label = 'Past Event';
                $event->time_class = 'badge-secondary';
            }
            return $event;
        });

        return view('staff.events.index', compact('events', 'filter'));
    }

    public function create()
    {
        return view('staff.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'status' => 'required|in:draft,published,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Validate start date again (defensive)
        if (\Carbon\Carbon::parse($validated['start_date'])->lt(now())) {
            return back()->withInput()->withErrors(['start_date' => 'Start date must not be in the past.']);
        }

        // Set creator
        $validated['created_by'] = auth()->id();

        // Handle image upload before creating to persist path
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        $event = Event::create($validated);

        // Notify members (3); exclude the creating staff user
        $recipients = User::whereIn('role', [3])
            ->where('is_active', true)
            ->where('id', '!=', auth()->id())
            ->get();

        if ($recipients->isNotEmpty()) {
            // Send synchronously so database notifications are stored immediately
            Notification::sendNow($recipients, new EventCreatedNotification($event));
        }

        return redirect()->route('staff.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function show(Event $event)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'start_date' => $event->start_date->format('F j, Y'),
                'start_time' => $event->start_date->format('h:i A'),
                'end_date' => $event->end_date->format('F j, Y'),
                'end_time' => $event->end_date->format('h:i A'),
                'location' => $event->location,
                'status' => $event->status,
                'status_class' => $this->getStatusClass($event->status),
                'image_path' => $event->image_path
            ]);
        }
        $event = Event::findOrFail($id); 

        return view('staff.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        $event = Event::findOrFail($id); 

        return view('staff.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date|after_or_equal:now',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'status' => 'required|in:draft,published,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Preserve the created_by field if it already exists
        if (!$event->created_by) {
            $validated['created_by'] = auth()->id();
        }

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            $path = $request->file('image')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        $event->update($validated);

        return redirect()->route('staff.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->image_path) {
            Storage::disk('public')->delete($event->image_path);
        }
        
        $event->delete();

        return redirect()->route('staff.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    private function getStatusClass($status)
    {
        return match($status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'published' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Display attendees for a specific event
     */
    public function attendees(Event $event)
    {
        $attendees = $event->registrations()
            ->with('user')
            ->where('status', '!=', 'cancelled')
            ->orderBy('registration_date', 'asc')
            ->get();

        $regularAttendees = $attendees->where('is_volunteer', false);
        $volunteers = $attendees->where('is_volunteer', true);
        $pendingVolunteers = $volunteers->where('status', 'pending');
        $approvedVolunteers = $volunteers->where('status', 'approved');

        return view('staff.events.attendees', compact(
            'event',
            'regularAttendees',
            'volunteers',
            'pendingVolunteers',
            'approvedVolunteers'
        ));
    }

    /**
     * Approve a volunteer request
     */
    public function approveVolunteer(Event $event, EventRegistration $registration)
    {
        // Ensure this is a volunteer registration
        if (!$registration->is_volunteer) {
            return redirect()->back()->with('error', 'This is not a volunteer registration.');
        }

        // Ensure the registration belongs to this event
        if ($registration->event_id !== $event->id) {
            return redirect()->back()->with('error', 'Invalid registration for this event.');
        }

        $registration->status = 'approved';
        $registration->save();

        // Notify the volunteer (if notification class exists)
        try {
            $registration->user->notify(new \App\Notifications\VolunteerApprovedNotification($registration));
        } catch (\Exception $e) {
            \Log::info('Volunteer approved notification not sent: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Volunteer request approved successfully.');
    }

    /**
     * Decline a volunteer request
     */
    public function declineVolunteer(Event $event, EventRegistration $registration)
    {
        // Ensure this is a volunteer registration
        if (!$registration->is_volunteer) {
            return redirect()->back()->with('error', 'This is not a volunteer registration.');
        }

        // Ensure the registration belongs to this event
        if ($registration->event_id !== $event->id) {
            return redirect()->back()->with('error', 'Invalid registration for this event.');
        }

        $registration->status = 'declined';
        $registration->save();

        // Notify the volunteer (if notification class exists)
        try {
            $registration->user->notify(new \App\Notifications\VolunteerDeclinedNotification($registration));
        } catch (\Exception $e) {
            \Log::info('Volunteer declined notification not sent: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Volunteer request declined.');
    }

    public function via($notifiable)
    {
        return ['database'];
    }
}