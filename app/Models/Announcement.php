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
        'image',
        'image_path', // Keep for backward compatibility
        'posted_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function setPostedAtAttribute($value)
    {
        $this->attributes['posted_at'] = $value ?: now();
    }

    /**
     * Get the user who created the announcement.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the announcement.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Handle the image_path and image field compatibility
     */
    public function getImagePathAttribute()
    {
        return $this->image ?? null;
    }

    public function setImagePathAttribute($value)
    {
        $this->attributes['image'] = $value;
    }
}
