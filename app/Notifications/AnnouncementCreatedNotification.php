<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Announcement;
use App\Events\NotificationSent;

use Illuminate\Support\Facades\Route;

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
        // Generate role-appropriate URL. Wrap route generation defensively so
        // a missing route doesn't cause the notification to fail.
        try {
            $url = match($notifiable->role) {
                    1 => Route::has('admin.announcements.show') ? route('admin.announcements.show', $this->announcement->id) : route('notifications.index'), // Admin fallback
                    2 => Route::has('member.announcements.show') ? route('member.announcements.show', $this->announcement->id) : route('notifications.index'), // Treasurer
                    3 => Route::has('member.announcements.show') ? route('member.announcements.show', $this->announcement->id) : route('notifications.index'), // Member
                    4 => Route::has('staff.announcements.index') ? route('staff.announcements.index') : route('notifications.index'), // Staff
                default => route('notifications.index')
            };
        } catch (\Exception $e) {
            // If any exception occurs while generating the URL, fall back to the notifications index.
            \Log::info('AnnouncementCreatedNotification::toArray route generation failed: ' . $e->getMessage());
            $url = route('notifications.index');
        }

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