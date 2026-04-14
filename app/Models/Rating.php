<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
     protected $fillable = [
        'user_id',
        'worker_id',
        'order_id',
        'rate',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
