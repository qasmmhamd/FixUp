<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkshopImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'workshop_id',
        'image_path',
        'is_primary'
    ];

    public function workshop()
    {
        return $this->belongsTo(Workshop::class);
    }
}