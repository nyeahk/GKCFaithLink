<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Event;
use App\Events\NotificationSent;

class EventCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Generate role-appropriate URL
        $url = match($notifiable->role) {
            1 => route('admin.events.index'), // Admin
            2 => route('member.events.show', $this->event->id), // Treasurer (same as member)
            3 => route('member.events.show', $this->event->id), // Member
            4 => route('staff.events.show', $this->event->id), // Staff
            default => route('member.events.show', $this->event->id)
        };

        return [
            'title' => 'New Event Created',
            'message' => 'A new event has been created: ' . $this->event->title,
            'description' => $this->event->description,
            'event_id' => $this->event->id,
            'url' => $url,
            'type' => 'event_created'
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