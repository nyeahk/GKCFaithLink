<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'donor_name',
        'amount',
        'purpose',
        'reference_number',
        'screenshot',
        'payment_method',
        'status',
        'admin_response',
        'admin_id',
        'transaction_date',
        'check_number',
        'bank_name',
        'check_date',
        'receipt_number',
        'verified_by',
        'verification_date',
        'verification_notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'status' => 'string',
        'transaction_date' => 'datetime',
        'check_date' => 'date',
        'verification_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeDeclined($query)
    {
        return $query->where('status', 'declined');
    }
} 