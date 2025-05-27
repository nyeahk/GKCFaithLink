<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        
        // Add a view composer to check user role
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            if (\Illuminate\Support\Facades\Auth::check()) {
                $user = \Illuminate\Support\Facades\Auth::user();
                $view->with('isStaff', $user->role === 'staff');
                $view->with('isAdmin', $user->role === 'admin');
                $view->with('isTreasurer', $user->role === 'treasurer');
            }
        });
    }
}

