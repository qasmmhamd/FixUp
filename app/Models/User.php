<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Collection;
use App\Models\Order;


#[Fillable([ 'name',
        'email',
        'password',
        'phone_number',
        'profile_image',
        'role',
        'birth_date',
        ])]
#[Hidden(['password', 'remember_token'])]
/**
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Order[] $orders
 */
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
     public function worker()
    {
        return $this->hasOne(Worker::class);
    }

   
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function address()
   {
       return $this->hasOne(Address::class);
   }
   public function images()
{
    return $this->hasMany(Image::class);
}
    
}