<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // View all users
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    // Show user details
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    // Enable/Disable user
    public function toggle(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
        return back()->with('success', 'User status updated.');
    }

    public function assignRole(Request $request, User $user)
{
    $request->validate([
        'role' => 'required|integer|min:1|max:4',
    ]);
    
    $user->role = $request->role;
    $user->save();
    
    return back()->with('success', 'User role updated successfully.');
}
}