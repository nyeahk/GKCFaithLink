<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Staff middleware is applied in the routes
    }

    /**
     * Display a listing of the events.
     */
    public function index()
    {
        $events = Event::orderBy('start_date', 'desc')->paginate(10);
        return view('staff.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        return view('staff.events.create');
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:scheduled,cancelled,completed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('events', 'public');
                $validated['image_path'] = $imagePath;
            }

            // Create the event
            $event = Event::create($validated);

            return redirect()->route('staff.events.index')
                ->with('success', 'Event created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating event: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create event. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('staff.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        return view('staff.events.edit', compact('event'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'location' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'status' => 'required|in:scheduled,cancelled,completed',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($event->image_path) {
                    Storage::disk('public')->delete($event->image_path);
                }
                
                $imagePath = $request->file('image')->store('events', 'public');
                $validated['image_path'] = $imagePath;
            }

            // Update the event
            $event->update($validated);

            return redirect()->route('staff.events.index')
                ->with('success', 'Event updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating event: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to update event. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        try {
            // Delete image if exists
            if ($event->image_path) {
                Storage::disk('public')->delete($event->image_path);
            }
            
            // Delete the event
            $event->delete();

            return redirect()->route('staff.events.index')
                ->with('success', 'Event deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting event: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete event. ' . $e->getMessage()]);
        }
    }
}

