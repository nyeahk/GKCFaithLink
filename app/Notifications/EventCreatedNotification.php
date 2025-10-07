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

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    public function via($notifiable)
    {
        // Use database channel so the notification appears in the UI for members
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $url = match($notifiable->role) {
            1 => route('admin.events.show', $this->event->id),
            2 => route('member.events.show', $this->event->id),
            3 => route('member.events.show', $this->event->id),
            4 => route('staff.events.show', $this->event->id),
            default => route('member.events.show', $this->event->id)
        };

        return [
            'title' => 'New Event',
            'message' => 'A new event has been created: ' . $this->event->title,
            'description' => $this->event->description,
            'event_id' => $this->event->id,
            'url' => $url,
            'type' => 'event_created'
        ];
    }

    /**
     * After the notification is stored in the database, broadcast it for real-time UI updates.
     */
    public function afterStore($notifiable, $notification)
    {
        try {
            event(new \App\Events\NotificationSent($notification, $notifiable->id));
        } catch (\Exception $e) {
            \Log::info('EventCreatedNotification broadcast failed: ' . $e->getMessage());
        }
    }

    /**
     * Fallback mail representation for queued notifications that expect a mail channel.
     * This ensures older queued jobs that still try to send mail won't fatally fail.
     */
    public function toMail($notifiable)
    {
        // Use a simple mail message that links to the event for users who receive email
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New Event: ' . $this->event->title)
            ->greeting('Hello!')
            ->line('A new event has been created: ' . $this->event->title)
            ->action('View Event', url('/events/' . $this->event->id))
            ->line('Thank you for being part of our community!');
    }
}