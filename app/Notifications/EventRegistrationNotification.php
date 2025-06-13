<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\EventRegistration;

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
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Event Registration')
                    ->line('A new member has registered for an event.')
                    ->line('Event: ' . $this->registration->event->title)
                    ->line('Member: ' . $this->registration->user->username)
                    ->line('Registration Date: ' . $this->registration->registration_date->format('F j, Y, g:i a'))
                    ->action('View Event', url('/staff/events/' . $this->registration->event_id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event_id' => $this->registration->event_id,
            'event_title' => $this->registration->event->title,
            'user_id' => $this->registration->user_id,
            'user_name' => $this->registration->user->username,
            'registration_id' => $this->registration->id,
            'registration_date' => $this->registration->registration_date->format('Y-m-d H:i:s'),
            'type' => 'event_registration'
        ];
    }
}