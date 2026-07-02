<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Conversation extends Model
{ use HasFactory;
    protected $fillable = [
        'customer_id',
        'worker_id',
        'topic_id',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    public function messages()
    {
        return $this->hasMany(ChatMessage::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Customer
    |--------------------------------------------------------------------------
    */

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Worker
    |--------------------------------------------------------------------------
    */

    public function worker()
    {
        return $this->belongsTo(Worker::class, 'worker_id');
    }
        public function topic()
    {
        return $this->belongsTo(MessageTopic::class,'topic_id');
    }
}