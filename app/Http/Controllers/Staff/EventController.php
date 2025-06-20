<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of the events.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'upcoming');
        
        $query = Event::query();
        
        // Apply filters
        if ($filter === 'past') {
            $query->where('end_date', '<', now())
                  ->orderBy('start_date', 'desc');
        } else if ($filter === 'all') {
            $query->orderBy('start_date', 'asc');
        } else {
            // Default: upcoming events
            $query->where('end_date', '>=', now())
                  ->orderBy('start_date', 'asc');
        }
        
        // Paginate the results instead of getting all at once
        $events = $query->paginate(10);
        
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
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'location' => 'required|string|max:255',
            'status' => 'required|in:draft,published,cancelled',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Add the authenticated user's ID
        $validated['created_by'] = auth()->id();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('events', 'public');
            $validated['image_path'] = $path;
        }

        Event::create($validated);

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

        return view('staff.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('staff.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
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
}


