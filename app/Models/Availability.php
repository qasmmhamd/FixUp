<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Availability extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'day',
        'start_time',
        'end_time'
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}
