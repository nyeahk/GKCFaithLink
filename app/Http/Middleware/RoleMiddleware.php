<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Log for debugging
        Log::info('RoleMiddleware: User role is ' . $user->role . ' for path ' . $request->path() . ' (required role: ' . $role . ')');
        
        if ($user->role == $role) {
            return $next($request);
        }
        
        // Special case for announcements - allow staff to access their own routes
        if ($request->is('staff/announcements') || $request->is('staff/announcements/*')) {
            if ($user->role == 'staff') {
                return $next($request);
            }
        }
        
        // Handle profile redirections
        if ($request->is('admin/profile') || $request->is('admin/profile/*')) {
            if ($user->role == 'staff') {
                return redirect()->route('staff.profile.index');
            }
        }
        
        if ($request->is('staff/profile') || $request->is('staff/profile/*')) {
            if ($user->role == 'admin') {
                return redirect()->route('profile.index');
            }
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


