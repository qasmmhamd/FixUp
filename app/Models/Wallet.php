<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'total_charged',
        'total_spent',
        'status'
    ];

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }
        public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
