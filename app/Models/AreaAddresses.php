<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaAddresses extends Model
{
        protected $fillable = ['area_name', 'created_at'];
    
        public function users()
        {
            return $this->hasMany(User::class);
        }
}
