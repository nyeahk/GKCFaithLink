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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Apply search filter if provided
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
        
        // Apply role filter if provided
        if ($request->has('role') && $request->input('role') != '') {
            $query->where('role', $request->input('role'));
        }
        
        // Apply status filter if provided
        if ($request->has('status')) {
            $status = $request->input('status');
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Order by created_at by default
        $query->orderBy('created_at', 'desc');
        
        // Paginate the results
        $users = $query->paginate(15);
        
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



