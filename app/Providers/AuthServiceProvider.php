<?php

namespace App\Providers;

use App\Auth\CustomUserProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

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
        // Register custom user provider
        Auth::provider('custom-eloquent', function ($app, array $config) {
            return new CustomUserProvider(
                $app['hash'],
                $config['model']
            );
        });

        // Define gates for roles with logging
        Gate::define('admin', function ($user) {
            $result = $user->role === 'admin';
            Log::info("Gate 'admin' check for user {$user->id}: " . ($result ? 'allowed' : 'denied'));
            return $result;
        });

        Gate::define('staff', function ($user) {
            $result = $user->role === 'staff';
            Log::info("Gate 'staff' check for user {$user->id}: " . ($result ? 'allowed' : 'denied'));
            return $result;
        });

        Gate::define('treasurer', function ($user) {
            $result = $user->role === 'treasurer';
            Log::info("Gate 'treasurer' check for user {$user->id}: " . ($result ? 'allowed' : 'denied'));
            return $result;
        });
    }
} 





