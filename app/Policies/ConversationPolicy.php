<?php

namespace App\Policies;

use App\Models\Conversation;
use App\Models\User;

class ConversationPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        
    }
    public function access(User $user, Conversation $conversation)
{
    return $user->id === $conversation->customer_id ||
           $user->id === $conversation->worker_id;
}
}
