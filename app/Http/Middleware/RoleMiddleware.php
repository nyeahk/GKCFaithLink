<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userRole = Auth::user()->role;
        
        foreach ($roles as $role) {
            // If user role matches any of the allowed roles
            if ($userRole == $role) {
                return $next($request);
            }
        }

        // If we reach here, user doesn't have the required role
        abort(403, 'Unauthorized action.');
    }
}




