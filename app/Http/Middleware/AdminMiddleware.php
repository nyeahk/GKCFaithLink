<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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
        
        // Add logging for debugging
        Log::info('AdminMiddleware: User role is ' . $user->role . ' for path ' . $request->path());
        
        if ($user->role == 'admin') {
            return $next($request);
        }
        
        // Redirect based on user role
        if ($user->role == 'staff') {
            // Don't redirect to admin routes if trying to access staff routes
            if (strpos($request->path(), 'staff/') === 0) {
                return redirect()->route('staff.dashboard');
            }
            return redirect()->route('staff.dashboard');
        } elseif ($user->role == 'treasurer') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('home');
        }
    }
}



