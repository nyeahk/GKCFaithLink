<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'recent');
        $now = now();

        $query = Announcement::where('status', 'published');

        // Apply filters with proper chronological ordering
        if ($filter === 'recent') {
            // Announcements from the last 30 days
            $query->where('posted_at', '>=', $now->subDays(30))
                  ->orderBy('posted_at', 'desc');
        } else if ($filter === 'current') {
            // Announcements from the last 7 days
            $query->where('posted_at', '>=', $now->subDays(7))
                  ->orderBy('posted_at', 'desc');
        } else if ($filter === 'older') {
            // Announcements older than 30 days
            $query->where('posted_at', '<', $now->subDays(30))
                  ->orderBy('posted_at', 'desc');
        } else {
            // Default: all announcements, newest first
            $query->orderBy('posted_at', 'desc');
        }

        // Paginate the results
        $announcements = $query->paginate(10);

        // Add time indicators to announcements
        $announcements->getCollection()->transform(function ($announcement) use ($now) {
            $daysDiff = $now->diffInDays($announcement->posted_at);

            if ($daysDiff <= 1) {
                $announcement->time_status = 'new';
                $announcement->time_label = 'New';
                $announcement->time_class = 'badge-success';
            } elseif ($daysDiff <= 7) {
                $announcement->time_status = 'recent';
                $announcement->time_label = 'Recent';
                $announcement->time_class = 'badge-primary';
            } elseif ($daysDiff <= 30) {
                $announcement->time_status = 'current';
                $announcement->time_label = 'This Month';
                $announcement->time_class = 'badge-info';
            } else {
                $announcement->time_status = 'older';
                $announcement->time_label = 'Older';
                $announcement->time_class = 'badge-secondary';
            }
            return $announcement;
        });

        return view('member.announcements.index', compact('announcements', 'filter'));
    }

    /**
     * Display the specified announcement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Check if the announcement is published
        if ($announcement->status !== 'published') {
            abort(404);
        }
        
        return view('member.announcements.show', compact('announcement'));
    }
}