<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the announcements.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $now = Carbon::now();

        $query = Announcement::query();

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

        return view('staff.announcements.index', compact('announcements', 'filter'));
    }

    /**
     * Show the form for creating a new announcement.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('staff.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:draft,published',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'posted_at' => 'nullable|date',
            ]);

            $announcement = new Announcement();
            $announcement->title = $request->title;
            $announcement->content = $request->content;
            $announcement->status = $request->status;
            $announcement->posted_at = $request->posted_at ?: now();
            $announcement->created_by = auth()->id();

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('announcements', 'public');
                $announcement->image = $path;
            }

            $announcement->save();

            // Notify all roles that have access to announcements: admin, staff, treasurer, member
            $rolesToNotify = [1, 2, 3, 4]; // 1=Admin, 2=Treasurer, 3=Member, 4=Staff
            $usersToNotify = \App\Models\User::whereIn('role', $rolesToNotify)->get();
            foreach ($usersToNotify as $user) {
                $user->notify(new \App\Notifications\AnnouncementCreatedNotification($announcement));
            }

            return redirect()->route('staff.announcements.index')
                ->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            \Log::error('Error creating announcement: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create announcement. ' . $e->getMessage()]);
        }
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
        return view('staff.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('staff.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:draft,published',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'posted_at' => 'nullable|date',
            ]);

            $announcement = Announcement::findOrFail($id);
            $announcement->title = $request->title;
            $announcement->content = $request->content;
            
            // If status is changing from draft to published, set posted_at
            if ($announcement->status == 'draft' && $request->status == 'published') {
                $announcement->posted_at = now();
            } else {
                $announcement->posted_at = $request->posted_at;
            }
            
            $announcement->status = $request->status;
            $announcement->updated_by = auth()->id();

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                
                $path = $request->file('image')->store('announcements', 'public');
                $announcement->image = $path;
            }

            $announcement->save();

            return redirect()->route('staff.announcements.index')
                ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Error updating announcement: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to update announcement. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified announcement from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        
        // Delete image if exists
        if ($announcement->image) {
            Storage::disk('public')->delete($announcement->image);
        }
        
        $announcement->delete();

        return redirect()->route('staff.announcements.index')
            ->with('success', 'Announcement deleted successfully.');
    }
}
