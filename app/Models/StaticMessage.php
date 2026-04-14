<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticMessage extends Model
{
      public $timestamps = false;

    protected $fillable = [
        'message',
    ];
}
