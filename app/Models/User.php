<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Remove Laravel\Sanctum\HasApiTokens if it's there

class User extends Authenticatable
{
    use HasFactory, Notifiable; // Remove HasApiTokens if it's there

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact_number',
        'address',
        'username',
        'email',
        'password',
        'role',
        'image_path'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    /**
     * Check if the user is currently active
     *
     * @return bool
     */
    public function isCurrentlyActive(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Consider user active if they've been active in the last 5 minutes
        return $this->last_active_at && $this->last_active_at->diffInMinutes(Carbon::now()) <= 5;
    }

    /**
     * Update the user's last active timestamp
     *
     * @return void
     */
    public function updateLastActive(): void
    {
        $this->update(['last_active_at' => Carbon::now()]);
    }

    public function getRoleDashboardRoute()
    {
        switch ($this->role) {
            case 1:
                return 'admin.dashboard';
            case 2:
                return 'treasurer.dashboard';
            case 3:
                return 'member.dashboard';
            case 4:
                return 'staff.dashboard';
            default:
                return 'home';
        }
    }
}





