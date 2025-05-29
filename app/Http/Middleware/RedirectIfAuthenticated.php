<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Log for debugging
                Log::info('RedirectIfAuthenticated: User role is ' . Auth::guard($guard)->user()->role . ' for path ' . $request->path());
                
                // Always allow access to staff login and registration pages
                if ($request->is('staff/login') || $request->is('staff/register')) {
                    return $next($request);
                }
                
                // Otherwise redirect based on role
                $user = Auth::guard($guard)->user();
                
                if ($user->role === 'staff') {
                    return redirect(RouteServiceProvider::STAFF_HOME);
                } elseif ($user->role === 'admin') {
                    return redirect(RouteServiceProvider::ADMIN_HOME);
                }
                
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}



