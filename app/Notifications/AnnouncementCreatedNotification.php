<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Announcement;
use App\Events\NotificationSent;

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
        return ['database'];
    }

    public function toArray($notifiable)
    {
        // Generate role-appropriate URL
        $url = match($notifiable->role) {
            1 => route('admin.announcements.index'), // Admin
            2 => route('member.announcements.show', $this->announcement->id), // Treasurer (same as member)
            3 => route('member.announcements.show', $this->announcement->id), // Member
            4 => route('staff.announcements.index'), // Staff
            default => route('member.announcements.show', $this->announcement->id)
        };

        return [
            'title' => 'New Announcement',
            'message' => 'A new announcement has been posted: ' . $this->announcement->title,
            'description' => $this->announcement->content,
            'announcement_id' => $this->announcement->id,
            'url' => $url,
            'type' => 'announcement_created'
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