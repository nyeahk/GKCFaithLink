<?php

namespace App\Listeners;

use Illuminate\Notifications\Events\NotificationSent;
use App\Events\NotificationSent as RealTimeNotificationSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotificationSentListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationSent $event): void
    {
        // Only handle database notifications for real-time updates
        if ($event->channel === 'database' && $event->response) {
            // Trigger real-time notification event
            event(new RealTimeNotificationSent($event->response, $event->notifiable->id));
        }
    }
}
