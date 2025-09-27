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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'contact_number' => ['nullable', 'digits_between:10,11', 'regex:/^[0-9]+$/'],
            'address' => ['nullable', 'string', 'max:255'],
        ]);

        try {
            // Update basic info
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->contact_number = $request->input('contact_number');
            $user->address = $request->input('address');

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($user->image_path) {
                    Storage::disk('public')->delete($user->image_path);
                }
                
                // Store new image
                $path = $request->file('image')->store('profile-photos', 'public');
                
                // Update user with new image path
                $user->image_path = $path;
            }

            $user->save();
            
            return redirect()->route('treasurer.profile.index')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
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
            
            return redirect()->route('treasurer.profile.password')->with('success', 'Password updated successfully.');
        } catch (\Exception $e) {
            Log::error('Password update error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'An error occurred while updating your password.']);
        }
    }
}

