<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // We'll handle the staff check in the routes
    }

    /**
     * Display a listing of the announcements.
     */
    public function index()
    {
        // Log for debugging
        Log::info('Staff AnnouncementController: index method called by user ' . Auth::id() . ' with role ' . Auth::user()->role);
        
        // Check if we're in the staff section
        if (!request()->is('staff/*')) {
            Log::warning('Staff AnnouncementController: Accessed outside staff prefix');
            if (Auth::user()->role == 'staff') {
                return redirect()->route('staff.announcements.index');
            }
        }
        
        $announcements = Announcement::orderBy('posted_at', 'desc')->paginate(10);
        return view('staff.announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('staff.announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:draft,pending,published',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'posted_at' => 'nullable|date'
            ]);

            // Set posted_at to current time if not provided
            if (!isset($validated['posted_at'])) {
                $validated['posted_at'] = now();
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('announcements', 'public');
                $validated['image_path'] = $imagePath; // Make sure this matches your database column
            }

            // Create the announcement
            $announcement = Announcement::create($validated);

            return redirect()->route('staff.announcements.index')
                ->with('success', 'Announcement created successfully.');
        } catch (\Exception $e) {
            Log::error('Error creating announcement: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to create announcement. ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        return view('staff.announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('staff.announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:draft,pending,published',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'posted_at' => 'nullable|date'
            ]);

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($announcement->image) {
                    Storage::disk('public')->delete($announcement->image);
                }
                
                $imagePath = $request->file('image')->store('announcements', 'public');
                $validated['image'] = $imagePath;
            }

            // Update the announcement
            $announcement->update($validated);

            return redirect()->route('staff.announcements.index')
                ->with('success', 'Announcement updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating announcement: ' . $e->getMessage());
            return back()->withInput()
                ->withErrors(['error' => 'Failed to update announcement. ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        try {
            // Delete image if exists
            if ($announcement->image) {
                Storage::disk('public')->delete($announcement->image);
            }
            
            // Delete the announcement
            $announcement->delete();

            return redirect()->route('staff.announcements.index')
                ->with('success', 'Announcement deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting announcement: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete announcement. ' . $e->getMessage()]);
        }
    }
}



