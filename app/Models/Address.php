<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
      protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'detailed_address',
        'area_address_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
     public function areaAddress()
    {
        return $this->belongsTo(AreaAddress::class);
    }
}
