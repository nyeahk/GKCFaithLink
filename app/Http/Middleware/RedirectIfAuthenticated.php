public function handle(Request $request, Closure $next, string ...$guards): Response
{
    $guards = empty($guards) ? [null] : $guards;

    foreach ($guards as $guard) {
        if (Auth::guard($guard)->check()) {
            // Allow access to staff registration even when authenticated
            if ($request->is('staff/register') || $request->is('staff/login')) {
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