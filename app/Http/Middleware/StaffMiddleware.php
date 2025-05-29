<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        // Log for debugging
        Log::info('StaffMiddleware: User role is ' . $user->role . ' for path ' . $request->path());
        
        if ($user->role == 'staff') {
            return $next($request);
        }
        
        // If user is admin, allow access to staff routes
        if ($user->role == 'admin') {
            return $next($request);
        }
        
        // Redirect based on user role
        if ($user->role == 'treasurer') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('login');
        }
    }
}









