<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryWork extends Model
{
    protected $fillable = [
        'worker_id',
        'description',
    ];

    public function worker()
    {
        return $this->belongsTo(Worker::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
