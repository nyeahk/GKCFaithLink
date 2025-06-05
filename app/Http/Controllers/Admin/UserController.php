<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('username', 'like', "%{$searchTerm}%")
                  ->orWhere('email', 'like', "%{$searchTerm}%")
                  ->orWhere('name', 'like', "%{$searchTerm}%");
            });
        }
        
        // Role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }
        
        // Status filter
        if ($request->has('status') && !empty($request->status)) {
            $isActive = $request->status === 'active' ? 1 : 0;
            $query->where('is_active', $isActive);
        }
        
        // Get users with pagination
        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Display a listing of the users by role.
     *
     * @param int $role
     * @return \Illuminate\Http\Response
     */
    public function byRole($role)
    {
        $users = User::where('role', $role)
                     ->orderBy('created_at', 'desc')
                     ->paginate(10);
                     
        $roleName = match($role) {
            1 => 'Admins',
            2 => 'Treasurers',
            3 => 'Members',
            4 => 'Staff',
            default => 'Unknown'
        };
        
        return view('admin.users.by-role', compact('users', 'roleName', 'role'));
    }
}

