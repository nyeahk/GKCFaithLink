<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $displayName = method_exists($this->user, 'getFullNameAttribute') && $this->user->full_name
            ? $this->user->full_name
            : trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''));
        if (empty($displayName)) { $displayName = $this->user->email; }

        return (new MailMessage)
            ->subject('New User Registration')
            ->greeting('Hello Admin!')
            ->line('A new user has registered and is awaiting approval:')
            ->line('Name: ' . $displayName)
            ->line('Email: ' . $this->user->email)
            ->action('Review User', url('/admin/users/' . $this->user->id))
            ->line('Please review and approve the user.');
    }

    public function toDatabase($notifiable)
    {
        $displayName = method_exists($this->user, 'getFullNameAttribute') && $this->user->full_name
            ? $this->user->full_name
            : trim(($this->user->first_name ?? '') . ' ' . ($this->user->last_name ?? ''));
        if (empty($displayName)) { $displayName = $this->user->email; }

        return [
            'title' => 'New User Registration',
            'message' => $displayName . ' has registered and is awaiting approval.',
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'url' => url('/admin/users/' . $this->user->id),
        ];
    }
}
