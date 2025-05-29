<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        if ($user->role == $role) {
            return $next($request);
        }
        
        // Redirect based on user role
        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role == 'staff') {
            return redirect()->route('staff.dashboard');
        } elseif ($user->role == 'treasurer') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('home');
        }
    }

}

