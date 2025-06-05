<?php

namespace App\Http\Controllers\Treasurer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Show the form for editing the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Show the form for changing the user's password.
     *
     * @return \Illuminate\Http\Response
     */
    public function password()
    {
        return view('profile.password');
    }

    /**
     * Update the user's profile information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            $user->name = $request->name;
            $user->email = $request->email;
            $user->contact_number = $request->contact_number;
            $user->address = $request->address;

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
            
            return redirect()->route('treasurer.profile.index')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()]);
        }
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            
            return redirect()->route('treasurer.profile.password')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            Log::error('Password update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while updating your password.']);
        }
    }
}