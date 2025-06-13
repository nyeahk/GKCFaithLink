<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EventRegistrationNotification;
use App\Notifications\EventVolunteerNotification;

class EventRegistrationController extends Controller
{
    /**
     * Show the registration form for an event
     */
    public function create(Event $event)
    {
        // Check if event is published
        if ($event->status !== 'published') {
            return redirect()->route('member.events')->with('error', 'This event is not available for registration.');
        }
        
        // Check if event date has passed
        if ($event->end_date < now()) {
            return redirect()->route('member.events')->with('error', 'This event has already ended.');
        }
        
        // Check if user is already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        // Get volunteer roles (you can customize this list)
        $volunteerRoles = [
            'usher' => 'Usher',
            'greeter' => 'Greeter',
            'setup_crew' => 'Setup Crew',
            'cleanup_crew' => 'Cleanup Crew',
            'technical_support' => 'Technical Support',
            'worship_team' => 'Worship Team',
            'children_ministry' => 'Children Ministry',
            'food_service' => 'Food Service',
            'other' => 'Other'
        ];
        
        return view('member.events.register', compact('event', 'existingRegistration', 'volunteerRoles'));
    }
    
    /**
     * Store a new registration
     */
    public function store(Request $request, Event $event)
    {
        // Validate request
        $validated = $request->validate([
            'is_volunteer' => 'boolean',
            'volunteer_role' => 'required_if:is_volunteer,1|nullable|string',
            'notes' => 'nullable|string|max:500',
        ]);
        
        // Check if user is already registered
        $existingRegistration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existingRegistration) {
            return redirect()->route('member.events.show', $event)
                ->with('error', 'You are already registered for this event.');
        }
        
        // Create registration
        $registration = new EventRegistration();
        $registration->event_id = $event->id;
        $registration->user_id = Auth::id();
        $registration->is_volunteer = $request->has('is_volunteer');
        $registration->volunteer_role = $validated['volunteer_role'] ?? null;
        $registration->notes = $validated['notes'] ?? null;
        $registration->status = 'pending';
        $registration->registration_date = now();
        $registration->save();
        
        // Notify staff about the registration
        $staffUsers = \App\Models\User::where('role', 4)->get();
        foreach ($staffUsers as $staff) {
            if ($registration->is_volunteer) {
                $staff->notify(new EventVolunteerNotification($registration));
            } else {
                $staff->notify(new EventRegistrationNotification($registration));
            }
        }
        
        // Redirect with success message
        $message = $registration->is_volunteer 
            ? 'Thank you for volunteering! Your request is pending approval.' 
            : 'You have successfully registered for this event!';
            
        return redirect()->route('member.events.show', $event)
            ->with('success', $message);
    }
    
    /**
     * Cancel a registration
     */
    public function cancel(Event $event)
    {
        // Find the registration
        $registration = EventRegistration::where('event_id', $event->id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$registration) {
            return redirect()->route('member.events.show', $event)
                ->with('error', 'You are not registered for this event.');
        }
        
        // Check if the event is in the future
        if ($event->start_date <= now()) {
            return redirect()->route('member.events.show', $event)
                ->with('error', 'You cannot cancel registration for an event that has already started.');
        }
        
        // Update registration status
        $registration->status = 'cancelled';
        $registration->save();
        
        return redirect()->route('member.events.show', $event)
            ->with('success', 'Your registration has been cancelled.');
    }
    
    /**
     * View all registrations for the authenticated user
     */
    public function index()
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('member.events.registrations', compact('registrations'));
    }
}