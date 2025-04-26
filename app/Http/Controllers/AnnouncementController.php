<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('posted_at', 'desc')->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request)
    {
        try {
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

            $announcement = Announcement::create($validated);

            return redirect()->route('announcements.index')
                ->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create announcement. ' . $e->getMessage()]);
        }
    }

    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:draft,pending,sent,published',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'posted_at' => 'nullable|date'
            ]);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($announcement->image_path) {
                    Storage::disk('public')->delete($announcement->image_path);
                }
                $imagePath = $request->file('image')->store('announcements', 'public');
                $validated['image_path'] = $imagePath;
            }

            $announcement->update($validated);

            return redirect()->route('announcements.index')
                ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->withErrors(['error' => 'Failed to update announcement. ' . $e->getMessage()]);
        }
    }

    public function destroy(Announcement $announcement)
    {
        try {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $announcement->delete();
            return redirect()->route('announcements.index')
                ->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to delete announcement. ' . $e->getMessage()]);
        }
    }
} 