<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'upcoming');
        $now = Carbon::now();

        $query = Event::where('status', 'published');

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
        } else if ($filter === 'all') {
            // All events in chronological order (upcoming -> current -> past)
            $query->orderByRaw("
                CASE
                    WHEN start_date > ? THEN 1
                    WHEN start_date <= ? AND end_date >= ? THEN 2
                    ELSE 3
                END, start_date ASC", [$now, $now, $now]);
        } else {
            // Default: upcoming and current events only
            $query->where('end_date', '>=', $now)
                  ->orderBy('start_date', 'asc');
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

        return view('member.events.index', compact('events', 'filter'));
    }
    
    public function show(Event $event)
    {
        // Check if the event is published
        if ($event->status !== 'published') {
            abort(404);
        }
        
        return view('member.events.show', compact('event'));
    }
}
