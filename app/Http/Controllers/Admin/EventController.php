<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $now = now();

        $query = Event::query();

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

        return view('admin.events.index', compact('events', 'filter'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);

        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }

    /**
     * Display attendees for a specific event (Admin view - read-only)
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

        return view('admin.events.attendees', compact(
            'event',
            'regularAttendees',
            'volunteers',
            'pendingVolunteers',
            'approvedVolunteers'
        ));
    }
}
