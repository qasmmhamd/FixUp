<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
     protected $fillable = [
        'user_id',
        'price',
        'location',
        'description',
        'priority',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function offers()
    {
        return $this->hasMany(PriceOffer::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }
}
