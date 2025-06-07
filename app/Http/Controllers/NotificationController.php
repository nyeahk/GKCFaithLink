<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);

        // Mark as read
        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        // Handle different notification types
        if (isset($notification->data['url'])) {
            return redirect($notification->data['url']);
        }

        return redirect()->route('notifications.index')
            ->with('success', 'Notification marked as read.');
    }

    public function markAllAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('success', 'All notifications marked as read.');
    }

    public function getUnreadCount()
    {
        $count = Auth::user()->unreadNotifications->count();
        return response()->json(['count' => $count]);
    }
}

