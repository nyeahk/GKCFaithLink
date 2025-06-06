<?php

namespace App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // Check if the user exists and is active
        $credentials = $request->only('email', 'password');
        
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

            // Redirect based on user role
            switch ($user->role) {
                case 1: // Admin
                    return redirect()->intended('admin/dashboard');
                case 2: // Treasurer
                    return redirect()->intended('treasurer/dashboard');
                case 3: // Member
                    return redirect()->intended('member/dashboard');
                case 4: // Staff
                    return redirect()->intended('staff/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        // If the login attempt was unsuccessful
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
} 



