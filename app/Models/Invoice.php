<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'worker_id',
        'order_id',
        'invoice_value',
        'payment_value',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
