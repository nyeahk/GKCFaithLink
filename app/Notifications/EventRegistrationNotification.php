<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\EventRegistration;
use App\Events\NotificationSent;

class EventRegistrationNotification extends Notification
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
            1 => route('admin.events.attendees', $this->registration->event), // Admin
            2 => route('staff.dashboard'), // Treasurer (limited access)
            3 => route('member.dashboard'), // Member (shouldn't receive this notification)
            4 => route('staff.events.attendees', $this->registration->event), // Staff
            default => route('staff.events.attendees', $this->registration->event)
        };

        return [
            'title' => 'New Event Registration',
            'message' => $this->registration->user->name . ' has registered for "' . $this->registration->event->title . '"',
            'event_id' => $this->registration->event_id,
            'event_title' => $this->registration->event->title,
            'user_id' => $this->registration->user_id,
            'user_name' => $this->registration->user->name,
            'registration_id' => $this->registration->id,
            'registration_date' => $this->registration->registration_date->format('Y-m-d H:i:s'),
            'url' => $url,
            'type' => 'event_registration'
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