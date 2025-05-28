<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // First check if the credentials are valid
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            // Check if the user is active
            if (!$user->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return back()->withErrors([
                    'email' => 'Your account has been disabled. Please contact the administrator.',
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();

            switch ($user->role) {
                case 1:
                    return redirect()->intended('admin/dashboard');
                case 2:
                    return redirect()->intended('treasurer/dashboard');
                case 3:
                    return redirect()->intended('member/dashboard');
                case 4:
                    return redirect()->intended('staff/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
} 
