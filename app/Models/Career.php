<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @class Career
 * 
 * Represents a professional career or job category in the FixUp system.
 * Careers group related services and workers who specialize in
 * particular professional fields.
 */
class Career extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name'];

    /**
     * Get all workers who belong to this career.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    /**
     * Get all services that belong to this career.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
