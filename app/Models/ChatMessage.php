<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'message',
        'message_type',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    // المحادثة التابعة للرسالة
    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }

    // مرسل الرسالة
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}