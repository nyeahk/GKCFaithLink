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

    public function show($id)
    {
        $event = Event::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }
}
