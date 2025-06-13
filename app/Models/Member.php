<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'birth_date',
        'baptism_date',
        'status',
        'membership_type',
        'image_path'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'baptism_date' => 'date'
    ];
} 