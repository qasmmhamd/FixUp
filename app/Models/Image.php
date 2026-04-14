<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
      protected $fillable = [
        'user_id',
        'order_id',
        'gallery_work_id',
        'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function galleryWork()
    {
        return $this->belongsTo(GalleryWork::class);
    }
}
