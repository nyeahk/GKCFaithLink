<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all users except admins (role 1)
        $users = User::where('role', '!=', 1)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
                     
        return view('staff.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Staff should not be able to view admin users
        if ($user->role == 1) {
            return redirect()->route('staff.users.index')
                ->with('error', 'You do not have permission to view this user.');
        }
        
        return view('staff.users.show', compact('user'));
    }
    
    /**
     * Get the role name for display
     *
     * @param int $role
     * @return string
     */
    public static function getRoleName($role)
    {
        return match($role) {
            1 => 'Admin',
            2 => 'Treasurer',
            3 => 'Member',
            4 => 'Staff',
            default => 'Unknown'
        };
    }
}

