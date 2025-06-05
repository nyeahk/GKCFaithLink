<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function index()
    {
        return view('profile.index');
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function password()
    {
        return view('profile.password');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'contact_number' => ['nullable', 'digits_between:10,11', 'regex:/^[0-9]+$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Update basic info
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->contact_number = $request->input('contact_number');
            $user->address = $request->input('address');

            // Handle image upload
            if ($request->hasFile('image')) {
                try {
                    // Delete old image if exists
                    if ($user->image_path) {
                        try {
                            Storage::disk('public')->delete($user->image_path);
                        } catch (\Exception $e) {
                            Log::error('Failed to delete old image: ' . $e->getMessage());
                        }
                    }
                    
                    // Store new image
                    $path = $request->file('image')->store('profile-photos', 'public');
                    
                    // Log the path for debugging
                    Log::info('Image stored at: ' . $path);
                    
                    // Update user with new image path
                    $user->image_path = $path;
                    
                    // Verify the image exists
                    if (!Storage::disk('public')->exists($path)) {
                        Log::error('Image was saved but file does not exist at: ' . $path);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to store new image: ' . $e->getMessage());
                    return back()->withErrors(['image' => 'Failed to upload image. Please try again.']);
                }
            }

            $user->save();
            
            return redirect()->route('member.profile.index')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            // Show the actual error message for debugging
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        try {
            $user->password = Hash::make($request->new_password);
            $user->save();
            
            return redirect()->route('member.profile.password')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            Log::error('Password update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while updating your password.']);
        }
    }
}
