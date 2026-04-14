<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
     protected $fillable = [
        'career_id',
        'name'
    ];

    public function career()
    {
        return $this->belongsTo(Career::class);
    }

    public function workers()
    {
        return $this->belongsToMany(Worker::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
