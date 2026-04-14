<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
     protected $fillable = [
        'user_id',
        'career_id',
        'about',
        'status',
        'years_experience',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function offers()
    {
        return $this->hasMany(PriceOffer::class);
    }
}
