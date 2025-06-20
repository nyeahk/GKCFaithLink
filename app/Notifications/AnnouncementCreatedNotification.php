<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Announcement;

class AnnouncementCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $announcement;

    public function __construct(Announcement $announcement)
    {
        $this->announcement = $announcement;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Announcement: ' . $this->announcement->title)
            ->greeting('Hello!')
            ->line('A new announcement has been posted: ' . $this->announcement->title)
            ->line($this->announcement->content)
            ->action('View Announcement', route('staff.announcements.show', $this->announcement->id))
            ->line('Thank you for staying connected!');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Announcement',
            'message' => 'A new announcement has been posted: ' . $this->announcement->title,
            'description' => $this->announcement->content,
            'announcement_id' => $this->announcement->id,
            'url' => route('staff.announcements.show', $this->announcement->id),
        ];
    }
} 