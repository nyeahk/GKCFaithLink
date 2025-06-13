<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
        // Get current date
        $today = Carbon::now();
        
        // Get all published events that haven't ended yet
        $events = Event::where('status', 'published')
            ->where('end_date', '>=', $today)
            ->orderBy('start_date', 'asc')
            ->paginate(10);
            
        return view('member.events.index', compact('events'));
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
