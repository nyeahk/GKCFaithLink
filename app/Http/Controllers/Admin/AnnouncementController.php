<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $now = now();

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

        // Return the view with announcements
        return view('admin.announcements.index', compact('announcements', 'filter'));
    }

    public function show($id)
    {
        // Fetch a single announcement by ID
        $announcement = Announcement::findOrFail($id);

        // Return the view with the announcement details
        return view('admin.announcements.show', compact('announcement'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,pending,sent,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posted_at' => 'nullable|date'
        ]);
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
            $validated['image_path'] = $imagePath;
        }
        
        // Create the announcement
        $announcement = Announcement::create($validated);

        // Notify all roles that have access to announcements: admin, staff, treasurer, member
        $rolesToNotify = [1, 2, 3, 4]; // 1=Admin, 2=Treasurer, 3=Member, 4=Staff
        $usersToNotify = \App\Models\User::whereIn('role', $rolesToNotify)->get();
        foreach ($usersToNotify as $user) {
            $user->notify(new \App\Notifications\AnnouncementCreatedNotification($announcement));
        }
        
        return redirect()->route('announcements.index')->with('success', 'Announcement created successfully.');
    }

    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:draft,pending,sent,published',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'posted_at' => 'nullable|date'
        ]);
        
        $announcement = Announcement::findOrFail($id);
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $imagePath = $request->file('image')->store('announcements', 'public');
            $validated['image_path'] = $imagePath;
        }
        
        // Update the announcement
        $announcement->update($validated);
        
        return redirect()->route('announcements.index')->with('success', 'Announcement updated successfully.');
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->delete();
        return redirect()->route('announcements.index')->with('success', 'Announcement deleted successfully.');
    }
} 