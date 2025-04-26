<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'status',
        'image_path',
        'posted_at'
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function setPostedAtAttribute($value)
    {
        $this->attributes['posted_at'] = $value ?: now();
    }

    // Add any model-specific logic here
}