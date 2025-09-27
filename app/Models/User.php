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
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'username',
        'is_approved',
        'address',
        'contact_number',
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
     * Get the user's full name, combining first_name and last_name if name is not set
     *
     * @return string
     */
    public function getFullNameAttribute()
    {
        if ($this->name) {
            return $this->name;
        }
        
        $fullName = trim($this->first_name . ' ' . $this->last_name);
        return $fullName ?: $this->username;
    }

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

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole($role): bool
    {
        return $this->roles()->where('slug', $role)->exists();
    }

    public function hasAnyRole($roles): bool
    {
        return $this->roles()->whereIn('slug', (array) $roles)->exists();
    }

    public function hasPermission($permission): bool
    {
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    public function notifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->morphMany(DatabaseNotification::class, 'notifiable')->whereNull('read_at');
    }
}









