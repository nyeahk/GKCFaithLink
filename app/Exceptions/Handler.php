/**
 * Register the exception handling callbacks for the application.
 */
public function register(): void
{
    $this->reportable(function (Throwable $e) {
        //
    });

    // Handle 403 Forbidden errors
    $this->renderable(function (HttpException $e, $request) {
        if ($e->getStatusCode() == 403) {
            // If user is authenticated, redirect to their appropriate dashboard
            if (Auth::check()) {
                $user = Auth::user();
                
                switch ($user->role) {
                    case 1: // Admin
                        return redirect()->route('admin.dashboard')
                            ->with('error', 'You do not have permission to access that page.');
                    case 2: // Treasurer
                        return redirect()->route('treasurer.dashboard')
                            ->with('error', 'You do not have permission to access that page.');
                    case 3: // Member
                        return redirect()->route('member.dashboard')
                            ->with('error', 'You do not have permission to access that page.');
                    case 4: // Staff
                        return redirect()->route('staff.dashboard')
                            ->with('error', 'You do not have permission to access that page.');
                    default:
                        return redirect('/');
                }
            }
            
            // If not authenticated, redirect to login
            return redirect()->route('login');
        }
    });
}