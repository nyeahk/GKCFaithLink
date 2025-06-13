<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        // Fetch paginated announcements
        $announcements = Announcement::orderBy('created_at', 'desc')->paginate(10);

        // Return the view with announcements
        return view('admin.announcements.index', compact('announcements'));
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
        Announcement::create($validated);
        
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