<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
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

        return view('admin.events.show', compact('event'));
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
