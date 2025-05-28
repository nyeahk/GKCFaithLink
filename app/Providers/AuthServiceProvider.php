<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Add a gate to check if user is active
        Gate::define('access-app', function ($user) {
            return $user->is_active;
        });
        
        // Add a check before authentication
        Auth::viaRequest('custom-token', function ($request) {
            // Your custom authentication logic
            // Make sure to check is_active status
        });
    }

    
} 
