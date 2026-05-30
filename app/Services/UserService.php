<?php

namespace App\Services;

use App\Models\Address;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * --------------------------------------------------------------------------
 * UserService
 * --------------------------------------------------------------------------
 *
 * Handles business logic related to user management in the FixUp system.
 * This service is responsible for user registration and initial profile
 * creation, ensuring data consistency using database transactions.
 */
class UserService
{
    /**
     * ----------------------------------------------------------------------
     * Register New User
     * ----------------------------------------------------------------------
     *
     * Creates a new user account and its related address record within a
     * single database transaction to ensure atomicity.
     *
     * @param array $data
     *      User registration payload containing:
     *      - name (string)
     *      - email (string)
     *      - phone_number (string)
     *      - birth_date (string)
     *      - password (string)
     *      - latitude (float)
     *      - longitude (float)
     *      - detailed_address (string)
     *      - area_address_id (int|null)
     *
     * @return User
     *      The newly created user instance.
     *
     * @throws \Throwable
     *      Rolls back transaction automatically on any failure.
     */
    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {

            /*
            |--------------------------------------------------------------------------
            | Create User
            |--------------------------------------------------------------------------
            |
            | Stores basic user credentials and personal information.
            |
            */

            $user = User::create([
                'name'          => $data['name'],
                'email'         => $data['email'],
                'phone_number'  => $data['phone_number'],
                'birth_date'    => $data['birth_date'],
                'password'      => Hash::make($data['password']),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Address
            |--------------------------------------------------------------------------
            |
            | Links a primary address to the newly created user account.
            |
            */

            Address::create([
                'user_id'           => $user->id,
                'latitude'          => $data['latitude'],
                'longitude'         => $data['longitude'],
                'detailed_address'  => $data['detailed_address'],
                'area_address_id'   => $data['area_address_id'],
            ]);

            return $user;
        });
    }
}