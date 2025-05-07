<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'contact_number' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'profile_picture' => ['nullable', 'image', 'max:2048'], // max 2MB
            'current_password' => ['required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->image_path) {
                Storage::disk('public')->delete($user->image_path);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $validated['image_path'] = $path;
        }

        // Update password if provided
        if ($request->filled('new_password')) {
            $validated['password'] = Hash::make($request->new_password);
        }

        // Update user
        $user->update($validated);

        return redirect()->route('member.profile')->with('success', 'Profile updated successfully');
    }
} 