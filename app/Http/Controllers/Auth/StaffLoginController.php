<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class StaffLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.staff.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user exists and has staff role
        $user = User::where('email', $credentials['email'])->first();
        
        if (!$user || $user->role !== 'staff') {
            return back()->withErrors([
                'email' => 'These credentials do not belong to a staff account.',
            ])->onlyInput('email');
        }

        // Attempt authentication
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Debug information
            return redirect()->route('staff.dashboard')
                ->with('debug', 'Logged in as staff. User role: ' . Auth::user()->role);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('staff.login');
    }
}




