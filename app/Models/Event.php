<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'status',
        'image_path',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    // Relationship with User (creator)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with EventRegistrations
    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    // Add this method to check if a user is registered
    public function isUserRegistered($userId)
    {
        return $this->registrations()->where('user_id', $userId)->exists();
    }

    // Add this method to get a user's registration
    public function getUserRegistration($userId)
    {
        return $this->registrations()->where('user_id', $userId)->first();
    }

    public function joinedMembers()
    {
        return $this->belongsToMany(User::class, 'event_members');
    }
}



