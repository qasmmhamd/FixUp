<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceOffer extends Model
{ use HasFactory;
    protected $fillable = [
        'worker_id',
        'order_id',
        'conversation_id',
        'price',
        'time_range',
        'status',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
