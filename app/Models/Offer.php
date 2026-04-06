<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'workshop_id',
        'price',
        'message',
        'status'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}