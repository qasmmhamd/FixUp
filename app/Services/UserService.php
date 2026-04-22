<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * @class UserService
 * 
 * Handles business logic for user management in the FixUp system.
 * This service manages user registration, profile creation, and related
 * operations while maintaining data integrity through database transactions.
 */
class UserService
{
    /**
     * Register a new user with their profile information.
     * 
     * @param array $data The user registration data including personal details and address
     * @return User The created user instance
     */
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
