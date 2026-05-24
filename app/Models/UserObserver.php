<?php
namespace App\Observers;

use App\Models\User;
use App\Models\Wallet;

class UserObserver
{
    public function created(User $user): void
    {
        if ($user->role === 'worker') {

            Wallet::create([
                'user_id' => $user->id,
            ]);
        }
    }
}