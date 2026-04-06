<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'workshop_id',
        'category_id',
        'address_id',
        'problem_description',
        'price',
        'status',
        'payment_status',
        'scheduled_at'
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}