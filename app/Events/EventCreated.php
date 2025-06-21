<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Event;

class EventCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;
    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Event $event, $notification)
    {
        $this->event = $event;
        $this->notification = $notification;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // Broadcast to all authenticated users
        return [
            new Channel('notifications')
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'type' => 'event_created',
            'event' => [
                'id' => $this->event->id,
                'title' => $this->event->title,
                'description' => $this->event->description,
                'start_date' => $this->event->start_date->format('F j, Y'),
                'end_date' => $this->event->end_date->format('F j, Y'),
                'location' => $this->event->location,
                'status' => $this->event->status,
            ],
            'notification' => $this->notification,
            'timestamp' => now()->toISOString(),
        ];
    }
} 