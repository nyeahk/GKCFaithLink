<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminRegisterController extends Controller
{
    public function __construct()
    {
        // Allow anyone to register an admin if no admins exist
        // Otherwise, require admin privileges
        $this->middleware(function ($request, $next) {
            $adminExists = User::where('role', 'admin')->exists();
            if ($adminExists) {
                return redirect()->route('login')
                    ->with('error', 'Admin accounts can only be created by existing admins.');
            }
            return $next($request);
        });
    }

    public function showRegistrationForm()
    {
        return view('auth.admin.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Set role as admin
            'is_active' => true,
        ]);

        auth()->login($user);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Admin account created successfully!');
    }
}