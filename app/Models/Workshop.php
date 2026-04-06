<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workshop extends Model
{
    use HasFactory;

    // 🔥 مهم جداً
    protected $fillable = [
        'owner_id',
        'name',
        'description',
        'address',
        'lat',
        'lng',
        'rating_avg',
        'logo',
        'is_verified'
    ];

    

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'workshop_categories')
                    ->withTimestamps();
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(WorkshopImage::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function getRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}