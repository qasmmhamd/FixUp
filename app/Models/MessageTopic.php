<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MessageTopic extends Model
{
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

