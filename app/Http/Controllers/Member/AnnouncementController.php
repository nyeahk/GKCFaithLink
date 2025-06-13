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
    public function index()
    {
        $announcements = Announcement::where('status', 'published')
            ->orderBy('posted_at', 'desc')
            ->paginate(10);
            
        return view('member.announcements.index', compact('announcements'));
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