<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'workshop_id',
        'booking_id',
        'rating',
        'comment'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}