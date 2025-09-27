<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\EventRegistration;
use App\Events\NotificationSent;

class VolunteerApprovedNotification extends Notification
{
    use Queueable;

    protected $registration;

    /**
     * Create a new notification instance.
     */
    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Generate role-appropriate URL
        $url = match($notifiable->role) {
            1 => route('admin.dashboard'), // Admin
            2 => route('member.events.show', $this->registration->event), // Treasurer (same as member)
            3 => route('member.events.show', $this->registration->event), // Member
            4 => route('staff.dashboard'), // Staff
            default => route('member.events.show', $this->registration->event)
        };

        return [
            'title' => 'Volunteer Request Approved',
            'message' => 'Your volunteer request for "' . $this->registration->event->title . '" has been approved.',
            'event_id' => $this->registration->event->id,
            'event_title' => $this->registration->event->title,
            'volunteer_role' => $this->registration->volunteer_role,
            'url' => $url,
            'type' => 'volunteer_approved'
        ];
    }

    /**
     * Handle notification after it's stored in database
     */
    public function afterStore($notifiable, $notification)
    {
        // Trigger real-time event
        event(new NotificationSent($notification, $notifiable->id));
    }
}
