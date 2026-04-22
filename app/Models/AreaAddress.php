<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @class AreaAddress
 * 
 * Represents a geographical area or location in the FixUp system.
 * Areas are used to organize services and workers by location,
 * helping customers find workers in their specific regions.
 */
class AreaAddress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['area_name', 'created_at'];

    /**
     * Get all users who belong to this area.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
