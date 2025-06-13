<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'is_volunteer',
        'volunteer_role',
        'status',
        'notes',
        'registration_date'
    ];

    protected $casts = [
        'registration_date' => 'datetime',
        'is_volunteer' => 'boolean',
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // Relationship with Event
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}