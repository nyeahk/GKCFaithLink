<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function register(Event $event)
    {
        try {
            // Check if user is already registered
            if ($event->registrations()->where('user_id', auth()->id())->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already registered for this event.'
                ]);
            }

            // Register user for the event
            $event->registrations()->create([
                'user_id' => auth()->id(),
                'status' => 'registered',
                'registered_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Successfully registered for the event.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while registering for the event.'
            ], 500);
        }
    }
} 