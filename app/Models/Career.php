<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
     protected $fillable = ['name'];

    public function workers()
    {
        return $this->hasMany(Worker::class);
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }
}
