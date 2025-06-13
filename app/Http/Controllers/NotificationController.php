<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user
        $user = auth()->user();

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
        } else {
            return 'Notification';
        }
    }
    
    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        // Mark as read if unread
        if (!$notification->read_at) {
            $notification->markAsRead();
        }
        
        return view('notifications.show', compact('notification'));
    }
    
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }
    
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return redirect()->back()->with('success', 'Notification marked as read.');
    }
    
    public function getCount()
    {
        $count = auth()->user()->unreadNotifications->count();
        
        return response()->json(['count' => $count]);
    }
}

