<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Event;

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
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Event: ' . $this->event->title)
            ->greeting('Hello!')
            ->line('A new event has been created: ' . $this->event->title)
            ->line($this->event->description)
            ->action('View Event', route('staff.events.show', $this->event->id))
            ->line('Thank you for staying connected!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Event Created',
            'message' => 'A new event has been created: ' . $this->event->title,
            'description' => $this->event->description,
            'event_id' => $this->event->id,
            'url' => route('staff.events.show', $this->event->id),
        ];
    }
} 