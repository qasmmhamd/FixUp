<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
     protected $fillable = [
        'text',
        'sender_type',
        'topic_id',
    ];
        public function topic()
        {
            return $this->belongsTo(MessageTopic::class,'topic_id');
        }
}
