<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class NotificationStreamController extends Controller
{
    /**
     * Stream real-time notifications using Server-Sent Events
     */
    public function stream(Request $request)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Disable nginx buffering
        
        $userId = Auth::id();
        $lastEventId = $request->header('Last-Event-ID', 0);
        
        // Get new notifications since last event
        $notifications = Auth::user()->unreadNotifications()
            ->where('id', '>', $lastEventId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $output = '';
        
        foreach ($notifications as $notification) {
            $data = [
                'id' => $notification->id,
                'type' => $notification->data['type'] ?? 'general',
                'title' => $notification->data['title'] ?? 'New Notification',
                'message' => $notification->data['message'] ?? '',
                'url' => $notification->data['url'] ?? '#',
                'created_at' => $notification->created_at->diffForHumans(),
                'timestamp' => $notification->created_at->timestamp
            ];
            
            $output .= "id: {$notification->id}\n";
            $output .= "event: notification\n";
            $output .= "data: " . json_encode($data) . "\n\n";
        }
        
        // Send heartbeat to keep connection alive
        $output .= "event: heartbeat\n";
        $output .= "data: " . json_encode(['timestamp' => time()]) . "\n\n";
        
        $response->setContent($output);
        return $response;
    }
    
    /**
     * Check for new notifications (polling fallback)
     */
    public function check(Request $request)
    {
        $lastCheck = $request->input('last_check', 0);
        
        $notifications = Auth::user()->unreadNotifications()
            ->where('created_at', '>', date('Y-m-d H:i:s', $lastCheck))
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $formattedNotifications = $notifications->map(function ($notification) {
            return [
                'id' => $notification->id,
                'type' => $notification->data['type'] ?? 'general',
                'title' => $notification->data['title'] ?? 'New Notification',
                'message' => $notification->data['message'] ?? '',
                'url' => $notification->data['url'] ?? '#',
                'created_at' => $notification->created_at->diffForHumans(),
                'timestamp' => $notification->created_at->timestamp
            ];
        });
        
        return response()->json([
            'notifications' => $formattedNotifications,
            'count' => $formattedNotifications->count(),
            'timestamp' => time()
        ]);
    }
}
