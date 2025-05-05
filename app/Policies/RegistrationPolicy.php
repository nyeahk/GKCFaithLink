<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegistrationPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can register.
     *
     * @param  \App\Models\User|null  $user
     * @return bool
     */
    public function register(?User $user): bool
    {
        // Allow registration if no user is logged in
        return $user === null;
    }

    /**
     * Determine whether the user can register with a specific position.
     *
     * @param  \App\Models\User|null  $user
     * @param  string  $position
     * @return bool
     */
    public function registerWithPosition(?User $user, string $position): bool
    {
        // Allow registration with any position if no user is logged in
        if ($user === null) {
            return true;
        }
        
        // If a user is logged in, only allow registration with positions they have permission for
        // For example, only admins can register pastors
        if ($user->position === 'Pastor') {
            return in_array($position, ['Staff', 'Treasurer', 'Member']);
        }
        
        return false;
    }
}
