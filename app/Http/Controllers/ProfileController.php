<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
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
            if ($request->filled('current_password')) {
                if (!Hash::check($request->current_password, $user->password)) {
                    return back()->withErrors(['current_password' => 'The current password is incorrect.']);
                }

                $user->password = Hash::make($request->new_password);
            }

            $user->save();

            return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while updating your profile. Please try again.']);
        }
    }
} 