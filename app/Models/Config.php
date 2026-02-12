<?php

namespace App\Models;

class Config extends BaseModel
{

    protected $fillable = [
        'group_code',
        'parent_id',
        'value',
        'descr',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get events using this config as location
     */
    public function locationEvents()
    {
        return $this->hasMany(Event::class, 'location_id');
    }

    /**
     * Get events using this config as floor
     */
    public function floorEvents()
    {
        return $this->hasMany(Event::class, 'floor_id');
    }

    /**
     * Get the parent config (Location for Floor)
     */
    public function parent()
    {
        return $this->belongsTo(Config::class, 'parent_id');
    }

    /**
     * Get child configs (Floors for Location)
     */
    public function children()
    {
        return $this->hasMany(Config::class, 'parent_id');
    }

    /**
     * Get active children only
     */
    public function activeChildren()
    {
        return $this->children()->where('is_active', true);
    }

    /**
     * Scope to get only active configs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by group code
     */
    public function scopeByGroup($query, $groupCode)
    {
        return $query->where('group_code', $groupCode);
    }
}
