<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // Don't use can:staff here as we're already using the staff middleware in the route group
    }

    public function index()
    {
        // Log for debugging
        Log::info('Staff ProfileController: index method called by user ' . Auth::id());
        
        // Double-check user role
        if (Auth::user()->role !== 'staff') {
            Log::warning('Non-staff user attempted to access staff profile: ' . Auth::user()->role);
            abort(403, 'You do not have staff privileges. Your role is: ' . Auth::user()->role);
        }
        
        return view('staff.profile.index');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'current_password' => ['nullable', 'required_with:new_password'],
            'new_password' => ['nullable', 'confirmed', Password::defaults()],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        try {
            // Update basic info
            $user->name = $request->name;
            $user->email = $request->email;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image_path) {
                    try {
                        Storage::disk('public')->delete($user->image_path);
                    } catch (\Exception $e) {
                        Log::error('Failed to delete old image: ' . $e->getMessage());
                    }
                }
                
                try {
                    // Store new image
                    $path = $request->file('image')->store('profile-photos', 'public');
                    $user->image_path = $path;
                } catch (\Exception $e) {
                    Log::error('Failed to store new image: ' . $e->getMessage());
                    return back()->withErrors(['image' => 'Failed to upload image. Please try again.']);
                }
            }
            
            // Update password if provided
            if ($request->filled('new_password')) {
                // Verify current password
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'The current password is incorrect.']);
                }
                
                $user->password = Hash::make($request->new_password);
            }
            
            $user->save();
            
            return redirect()->route('staff.profile.index')->with('success', 'Profile updated successfully.');
            
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return back()->withErrors(['general' => 'An error occurred while updating your profile. Please try again.']);
        }
    }
}
