<?php

use Illuminate\Support\Str;

return [
    /*
    |--------------------------------------------------------------------------
    | Optimization Settings
    |--------------------------------------------------------------------------
    |
    | This file contains various optimization settings for your Laravel application.
    |
    */

    'cache' => [
        'enabled' => env('CACHE_ENABLED', true),
        'driver' => env('CACHE_DRIVER', 'file'),
        'prefix' => env('CACHE_PREFIX', 'laravel'),
    ],

    'view' => [
        'compiled' => env(
            'VIEW_COMPILED_PATH',
            realpath(storage_path('framework/views'))
        ),
    ],

    'session' => [
        'lifetime' => env('SESSION_LIFETIME', 120),
        'expire_on_close' => false,
        'encrypt' => false,
        'files' => storage_path('framework/sessions'),
        'connection' => env('SESSION_CONNECTION', null),
        'table' => 'sessions',
        'store' => env('SESSION_STORE', null),
        'lottery' => [2, 100],
        'cookie' => env(
            'SESSION_COOKIE',
            Str::slug(env('APP_NAME', 'laravel'), '_').'_session'
        ),
        'path' => '/',
        'domain' => env('SESSION_DOMAIN', null),
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => 'lax',
    ],
]; 