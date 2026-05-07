<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
     protected $fillable = [
        'text',
        'sender_type',
        'topic',
    ];
}
