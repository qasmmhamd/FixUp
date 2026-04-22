<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @class Service
 * 
 * Represents a specific service that can be offered by workers.
 * Services are categorized under careers and can be selected
 * by workers to indicate their expertise areas.
 */
class Service extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'career_id',
        'name',
    ];

    /**
     * Get the career that this service belongs to.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Get all workers who offer this service.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workers()
    {
        return $this->belongsToMany(Worker::class, 'worker_service');
    }

    /**
     * Get all orders that include this service.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
