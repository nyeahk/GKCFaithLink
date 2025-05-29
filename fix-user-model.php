<?php

// This script checks and fixes the User model location issue

// Check if app/Models directory exists
if (!is_dir(__DIR__ . '/app/Models')) {
    echo "Creating app/Models directory...\n";
    mkdir(__DIR__ . '/app/Models', 0755, true);
}

// Check if app/Models/User.php exists
if (!file_exists(__DIR__ . '/app/Models/User.php') && file_exists(__DIR__ . '/app/User.php')) {
    echo "Copying User model from app/User.php to app/Models/User.php...\n";
    $content = file_get_contents(__DIR__ . '/app/User.php');
    
    // Update namespace
    $content = str_replace('namespace App;', 'namespace App\Models;', $content);
    
    file_put_contents(__DIR__ . '/app/Models/User.php', $content);
    echo "User model copied and namespace updated.\n";
}

// Check auth.php configuration
$authConfig = file_get_contents(__DIR__ . '/config/auth.php');
if (strpos($authConfig, 'App\User::class') !== false) {
    echo "Updating auth.php configuration...\n";
    $authConfig = str_replace('App\User::class', 'App\Models\User::class', $authConfig);
    file_put_contents(__DIR__ . '/config/auth.php', $authConfig);
    echo "Auth configuration updated.\n";
}

echo "Done. Please run 'composer dump-autoload' and clear Laravel caches.\n";