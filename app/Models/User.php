<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Traits\HasRoles;
use Illuminate\Notifications\DatabaseNotification;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'username',
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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the dashboard route based on user role
     *
     * @return string
     */
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
                return 'login';
        }
    }

    /**
     * Get the registrations for the user.
     */
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the user's profile photo URL (returns the correct image or a default avatar)
     */
    public function getProfilePhotoUrlAttribute()
    {
        if (!empty($this->image_path)) {
            return asset('storage/' . ltrim($this->image_path, '/'));
        }
        return asset('images/default-avatar.png');
    }
}







