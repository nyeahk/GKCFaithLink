<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Filter by type if provided
        $query = $user->notifications();

        if ($request->has('type')) {
            $type = $request->input('type');
            if ($type === 'donation') {
                $query->where(function($q) {
                    $q->where('type', 'like', '%Donation%');
                });
            } elseif ($type === 'event') {
                $query->where(function($q) {
                    $q->where('type', 'like', '%Event%');
                });
            }
        }

        // Get all notifications and paginate them
        $notifications = $query->latest()->paginate(10);

        // Group notifications by date
        $groupedNotifications = collect($notifications->items())->groupBy(function($notification) {
            return $notification->created_at->format('Y-m-d');
        });

        return view('notifications.index', compact('notifications', 'groupedNotifications'))
            ->with('controller', $this);
    }

    /**
     * Get notification type for filtering
     */
    public function getNotificationType($type)
    {
        if (str_contains($type, 'Donation')) return 'donation';
        if (str_contains($type, 'Event')) return 'event';
        return 'general';
    }

    /**
     * Get notification icon data
     */
    public function getNotificationIcon($type, $data = [])
    {
        if ($type == 'App\Notifications\DonationApprovedNotification') {
            return ['icon' => 'fas fa-check-circle', 'class' => 'success'];
        } elseif ($type == 'App\Notifications\DonationDeclinedNotification') {
            return ['icon' => 'fas fa-times-circle', 'class' => 'danger'];
        } elseif ($type == 'App\Notifications\DonationStatusNotification') {
            $status = $data['status'] ?? 'approved';
            return $status == 'declined'
                ? ['icon' => 'fas fa-times-circle', 'class' => 'danger']
                : ['icon' => 'fas fa-check-circle', 'class' => 'success'];
        } elseif ($type == 'App\Notifications\NewDonationNotification') {
            return ['icon' => 'fas fa-hand-holding-usd', 'class' => 'warning'];
        } elseif ($type == 'App\Notifications\EventRegistrationNotification') {
            return ['icon' => 'fas fa-calendar-check', 'class' => 'info'];
        } elseif ($type == 'App\Notifications\EventVolunteerNotification') {
            return ['icon' => 'fas fa-users', 'class' => 'primary'];
        } elseif ($type == 'App\Notifications\EventCreatedNotification') {
            return ['icon' => 'fas fa-calendar-plus', 'class' => 'success'];
        } elseif ($type == 'App\Notifications\AnnouncementCreatedNotification') {
            return ['icon' => 'fas fa-bullhorn', 'class' => 'info'];
        } else {
            return ['icon' => 'fas fa-bell', 'class' => 'secondary'];
        }
    }

    /**
     * Get notification title
     */
    public function getNotificationTitle($type, $data = [])
    {
        if ($type == 'App\Notifications\DonationApprovedNotification') {
            return 'Donation Approved';
        } elseif ($type == 'App\Notifications\DonationDeclinedNotification') {
            return 'Donation Declined';
        } elseif ($type == 'App\Notifications\DonationStatusNotification') {
            $status = $data['status'] ?? 'approved';
            return $status == 'declined' ? 'Donation Declined' : 'Donation Approved';
        } elseif ($type == 'App\Notifications\NewDonationNotification') {
            return 'New Donation Received';
        } elseif ($type == 'App\Notifications\EventRegistrationNotification') {
            return 'Event Registration';
        } elseif ($type == 'App\Notifications\EventVolunteerNotification') {
            return 'Volunteer Opportunity';
        } elseif ($type == 'App\Notifications\EventCreatedNotification') {
            return 'New Event Created';
        } elseif ($type == 'App\Notifications\AnnouncementCreatedNotification') {
            return 'New Announcement';
        } else {
            return 'Notification';
        }
    }
    
    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        
        // Mark as read if unread
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        return view('notifications.show', compact('notification'));
    }
    
    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
    
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }
    
    public function getCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }

    public function checkNew(Request $request)
    {
        $user = Auth::user();
        $lastCheck = $request->input('last_check', now()->subMinutes(5)->timestamp);
        
        // Get notifications created after the last check
        $newNotifications = $user->notifications()
            ->where('created_at', '>', date('Y-m-d H:i:s', $lastCheck))
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();

        if ($newNotifications->count() > 0) {
            $notifications = $newNotifications->map(function($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $this->getNotificationTitle($notification->type, $notification->data),
                    'message' => $notification->data['message'] ?? 'New notification received',
                    'type' => $this->getNotificationType($notification->type),
                    'url' => $notification->data['url'] ?? route('notifications.show', $notification->id),
                    'created_at' => $notification->created_at->diffForHumans()
                ];
            });

            return response()->json([
                'hasNew' => true,
                'notifications' => $notifications,
                'count' => $newNotifications->count()
            ]);
        }

        return response()->json([
            'hasNew' => false,
            'notifications' => [],
            'count' => 0
        ]);
    }
}

