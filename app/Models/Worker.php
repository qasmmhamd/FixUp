<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @class Worker
 *
 * Represents a worker profile in the FixUp system.
 * Workers are users who provide professional services to customers.
 * This model manages worker profiles, career information, and service offerings.
 */
class Worker extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'career_id',
        'about',
        'status',
        'years_experience',
    ];

    /**
     * Get the user account associated with this worker.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the career/profession of this worker.
     *
     * @return BelongsTo
     */
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    /**
     * Get all services offered by this worker.
     *
     * @return BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class, 'worker_service');
    }

    /**
     * Get all price offers made by this worker.
     *
     * @return HasMany
     */
    public function offers()
    {
        return $this->hasMany(PriceOffer::class);
    }

    /**
     * Get all images uploaded by this worker.
     *
     * @return HasMany
     */
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    /**
     * Scope to filter workers by their status.
     *
     * @param  Builder  $query
     * @param  string  $status  The status to filter by (active, inactive, pending)
     * @return Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    
}
