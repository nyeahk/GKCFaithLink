<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'created_by'
    ];

    protected $dates = [
        'event_date',
        'created_at',
        'updated_at'
    ];

    /**
     * Get the formatted event date
     *
     * @return string
     */
    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('Y-m-d');
    }
}
