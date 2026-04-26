<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
        'user_id',
        'description',
        'status',
        'expires_at',
        'address_id',
        'career_id',
        'priority',
        'scheduled_at'
            ];

    protected $casts = [
        'expires_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'is_broadcast' => 'boolean',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }
      public function address()
    {
        return $this->belongsTo(Address::class);
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
    public function images()
{
    return $this->hasMany(Image::class);
}
public function career()
{
    return $this->belongsTo(Career::class);
}
}
