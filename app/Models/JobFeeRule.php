<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class JobFeeRule extends Model
{
    protected $fillable = [
        'career_id',
        'fee',
        'is_active'
    ];
   
    public function career()
    {
        return $this->belongsTo(Career::class);
    }

}
