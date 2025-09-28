<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Approve a user and notify them.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve($id)
    {
        $user = User::findOrFail($id);
        $user->is_approved = true;
        $user->save();

        $user->notify(new \App\Notifications\UserApprovedNotification());

        return redirect()->back()->with('success', 'User approved and notified successfully.');
    }

    /**
     * Display a listing of the users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $query = User::query();   // 👈 Base query for users

    // role filter
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    // status filter
    if ($request->filled('status')) {
        $query->where('is_active', $request->status === 'active');
    }

    // approval filter
    if ($request->filled('approval')) {
        $query->where('is_approved', $request->approval === 'approved');
    }

    // 🔎 👉 THIS IS WHERE YOU ADD THE SEARCH LOGIC
    if ($request->filled('search')) {
        $search = strtolower($request->search);

        $query->where(function ($q) use ($search) {
            $q->whereRaw("LOWER(email) LIKE ?", ["%{$search}%"])
              ->orWhereRaw("LOWER(first_name) LIKE ?", ["%{$search}%"])
              ->orWhereRaw("LOWER(last_name) LIKE ?", ["%{$search}%"])
              ->orWhereRaw("LOWER(CONCAT(first_name, ' ', last_name)) LIKE ?", ["%{$search}%"]);
        });
    }

    $users = $query->paginate(10);

    return view('admin.users.index', compact('users'));
}

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Toggle user active status.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function toggle($id)
    {
        $user = User::findOrFail($id);
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'enabled' : 'disabled';
        return redirect()->back()->with('success', "User has been {$status} successfully.");
    }

    /**
     * Assign role to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|integer|in:1,2,3,4'
        ]);

        $user = User::findOrFail($id);
        $user->role = $request->input('role');
        $user->save();

        $roleNames = [
            1 => 'Administrator',
            2 => 'Treasurer', 
            3 => 'Member',
            4 => 'Staff'
        ];

        $roleName = $roleNames[$request->input('role')] ?? 'Unknown';
        
        return redirect()->back()->with('success', "User role has been updated to {$roleName}.");
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

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer|in:1,2,3,4',
            'name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
            'is_active' => true,
            'is_approved' => true,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|integer|in:1,2,3,4',
            'name' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'role' => $request->role,
            'name' => $request->name,
            'contact_number' => $request->contact_number,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }
}



