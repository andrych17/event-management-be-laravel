<?php

namespace App\Models;

use Carbon\Carbon;

class Event extends BaseModel
{
    protected $fillable = [
        'title',
        'location_id',
        'floor_id',
        'event_start_datetime',
        'event_end_datetime',
        'description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'event_start_datetime' => 'datetime',
        'event_end_datetime' => 'datetime',
    ];

    /**
     * Boot method to auto-populate legacy date/time fields
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-populate legacy fields when creating
        static::creating(function ($model) {
            if ($model->event_start_datetime) {
                $startDateTime = Carbon::parse($model->event_start_datetime);
                $model->event_date = $startDateTime->format('Y-m-d');
                $model->event_time = $startDateTime->format('H:i');
            }
        });

        // Auto-populate legacy fields when updating
        static::updating(function ($model) {
            if ($model->isDirty('event_start_datetime') && $model->event_start_datetime) {
                $startDateTime = Carbon::parse($model->event_start_datetime);
                $model->event_date = $startDateTime->format('Y-m-d');
                $model->event_time = $startDateTime->format('H:i');
            }
        });
    }

    /**
     * Get the location config for this event
     */
    public function location()
    {
        return $this->belongsTo(Config::class, 'location_id');
    }

    /**
     * Get the floor config for this event
     */
    public function floor()
    {
        return $this->belongsTo(Config::class, 'floor_id');
    }

    /**
     * Scope for today's events in WIB timezone
     */
    public function scopeToday($query)
    {
        $today = Carbon::now('Asia/Jakarta')->format('Y-m-d');
        return $query->whereDate('event_start_datetime', $today)
                     ->orderBy('event_start_datetime', 'asc');
    }

    /**
     * Get formatted start date
     */
    public function getFormattedDateAttribute()
    {
        return Carbon::parse($this->event_start_datetime)->format('d M Y');
    }

    /**
     * Get formatted start time
     */
    public function getFormattedTimeAttribute()
    {
        return Carbon::parse($this->event_start_datetime)->format('H:i');
    }

    /**
     * Get formatted end time
     */
    public function getFormattedEndTimeAttribute()
    {
        return $this->event_end_datetime ? Carbon::parse($this->event_end_datetime)->format('H:i') : null;
    }
}
