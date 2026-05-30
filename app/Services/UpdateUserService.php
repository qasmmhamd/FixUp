<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpdateUserService
{
    /*
    |--------------------------------------------------------------------------
    | Update User Profile
    |--------------------------------------------------------------------------
    */

    public function update(
        User $user,
        $request
    ) {

        return DB::transaction(function () use (
            $user,
            $request
        ) {

            /*
            |--------------------------------------------------------------------------
            | Validate Request Data
            |--------------------------------------------------------------------------
            */

            $data = $request->validated();

            /*
            |--------------------------------------------------------------------------
            | Prepare User Data
            |--------------------------------------------------------------------------
            */

            $userData = collect($data)
                ->only([
                    'name',
                    'email',
                    'phone_number',
                    'birth_date',
                ])
                ->toArray();

            /*
            |--------------------------------------------------------------------------
            | Handle Profile Image
            |--------------------------------------------------------------------------
            */

            if ($request->hasFile('profile_image')) {

                /*
                |--------------------------------------------------------------------------
                | Delete Old Image
                |--------------------------------------------------------------------------
                */

                if (
                    $user->profile_image &&
                    Storage::disk('public')->exists($user->profile_image)
                ) {

                    Storage::disk('public')->delete(
                        $user->profile_image
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Store New Image
                |--------------------------------------------------------------------------
                */

                $userData['profile_image'] = $request
                    ->file('profile_image')
                    ->store(
                        'images',
                        'public'
                    );
            }

            /*
            |--------------------------------------------------------------------------
            | Update User
            |--------------------------------------------------------------------------
            */

            $user->update($userData);

            /*
            |--------------------------------------------------------------------------
            | Update Address If Provided
            |--------------------------------------------------------------------------
            */

            if (
                isset($data['latitude']) ||
                isset($data['longitude']) ||
                isset($data['detailed_address'])
            ) {

                $user->address()->updateOrCreate(
                    [
                        'user_id' => $user->id
                    ],
                    [
                        'latitude'         => $data['latitude'] ?? null,
                        'longitude'        => $data['longitude'] ?? null,
                        'detailed_address'  => $data['detailed_address'] ?? null,
                        'area_address_id'   => $data['area_address_id'] ?? null,
                    ]
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Return Updated User
            |--------------------------------------------------------------------------
            */

            return $user->load('address');
        });
    }
}