<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
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
                
                try {
                    // Use the getRoleDashboardRoute method
                    return redirect()->route($user->getRoleDashboardRoute())
                        ->with('error', 'You do not have permission to access that page.');
                } catch (\Exception $redirectException) {
                    Log::error('Error during 403 redirect: ' . $redirectException->getMessage());
                    return redirect('/');
                }
            }
            
            // If not authenticated, redirect to login
            return redirect()->route('login');
        }
    });
}
}
