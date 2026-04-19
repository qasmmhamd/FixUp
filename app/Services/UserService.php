<?php
namespace App\Services;

use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
class UserService
{
    public function register(array $data)
    {
        return DB::transaction(function () use ($data) {

        // 1. إنشاء المستخدم
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'birth_date' => $data['birth_date'],
            'password' => Hash::make($data['password']),
        ]);

        // 2. إنشاء العنوان
        Address::create([
            'user_id' => $user->id,
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'detailed_address' => $data['detailed_address'],
            'area_address_id' => $data['area_address_id'],
        ]);

        return $user;
    });
    }
}