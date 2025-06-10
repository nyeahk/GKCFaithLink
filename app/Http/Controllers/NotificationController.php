<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        
        return view('notifications.index', compact('notifications', 'groupedNotifications'));
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

