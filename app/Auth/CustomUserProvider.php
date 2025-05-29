<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\Log;

class CustomUserProvider extends EloquentUserProvider
{
    public function __construct(HasherContract $hasher, $model)
    {
        parent::__construct($hasher, $model);
        Log::info("CustomUserProvider initialized with model: {$model}");
    }

    public function createModel()
    {
        // Try the original model path
        $class = '\\'.ltrim($this->model, '\\');
        
        // Log the model class we're trying to create
        Log::info("CustomUserProvider: Attempting to create model {$class}");
        
        // If the class doesn't exist, try alternative locations
        if (!class_exists($class)) {
            Log::warning("CustomUserProvider: Class {$class} does not exist, trying alternatives");
            
            if (class_exists('\\App\\Models\\User')) {
                $class = '\\App\\Models\\User';
                Log::info("CustomUserProvider: Using App\\Models\\User instead");
            } elseif (class_exists('\\App\\User')) {
                $class = '\\App\\User';
                Log::info("CustomUserProvider: Using App\\User instead");
            } else {
                Log::error("CustomUserProvider: Could not find a valid User model class");
            }
        }
        
        return new $class;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $model = parent::retrieveById($identifier);
        Log::info("CustomUserProvider: Retrieved user by ID {$identifier}: " . ($model ? 'found' : 'not found'));
        return $model;
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        Log::info("CustomUserProvider: Retrieving user by credentials", ['credentials' => array_keys($credentials)]);
        return parent::retrieveByCredentials($credentials);
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $result = parent::validateCredentials($user, $credentials);
        Log::info("CustomUserProvider: Validated credentials for user {$user->getAuthIdentifier()}: " . ($result ? 'valid' : 'invalid'));
        return $result;
    }
}

