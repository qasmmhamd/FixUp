<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class MessageTopic extends Model
{   use HasFactory;
    //
        protected $fillable = [
            'topic',
        ];
         public function templates()
         {
            return $this->hasMany(
                 MessageTemplate::class
                );
         }
      }

