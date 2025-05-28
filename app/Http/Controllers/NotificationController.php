<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of the user's notifications.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $notifications = Auth::user()->notifications()->paginate(10);
            return view('notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            // If the notifications table doesn't exist yet
            return view('notifications.index', ['notifications' => collect()]);
        }
    }

    /**
     * Display the specified notification and mark it as read.
     *
     * @param  string  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $notification = Auth::user()->notifications()->findOrFail($id);
            
            // Mark as read if unread
            if ($notification->read_at === null) {
                $notification->markAsRead();
            }
            
            // Determine redirect based on notification type
            if (isset($notification->data['url'])) {
                return redirect($notification->data['url']);
            }
            
            // Handle different notification types
            switch ($notification->type) {
                case 'App\Notifications\DonationApprovedNotification':
                case 'App\Notifications\DonationStatusNotification':
                    if (isset($notification->data['donation_id'])) {
                        return redirect()->route('member.donations.show', $notification->data['donation_id']);
                    }
                    break;
                    
                case 'App\Notifications\NewDonationNotification':
                    if (isset($notification->data['donation_id'])) {
                        return redirect()->route('admin.donations.show', $notification->data['donation_id']);
                    }
                    break;
                    
                default:
                    return redirect()->route('notifications.index')
                        ->with('success', 'Notification marked as read.');
            }
            
            return redirect()->route('notifications.index');
        } catch (\Exception $e) {
            return redirect()->route('notifications.index')
                ->with('error', 'Notification not found or notifications table does not exist.');
        }
    }

    /**
     * Mark all notifications as read.
     *
     * @return \Illuminate\Http\Response
     */
    public function markAllAsRead()
    {
        try {
            Auth::user()->unreadNotifications->markAsRead();
            return back()->with('success', 'All notifications marked as read.');
        } catch (\Exception $e) {
            return back()->with('error', 'Could not mark notifications as read. The notifications table may not exist yet.');
        }
    }
}


