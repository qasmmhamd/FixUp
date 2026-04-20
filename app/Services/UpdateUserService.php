<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class UpdateUserService
{


public function update(User $user, $request)
{
    return DB::transaction(function () use ($user, $request) {

        $data = $request->validated();

        // --------------------
        // 1. تحديث المستخدم
        // --------------------
        $userData = collect($data)->only([
            'name',
            'email',
            'phone_number',
            'birth_date',
        ])->toArray();

        // صورة
        if ($request->hasFile('profile_picture')) {

            if (
                $user->profile_picture &&
                Storage::disk('public')->exists($user->profile_picture)
            ) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $userData['profile_picture'] = $request
                ->file('profile_picture')
                ->store('images', 'public');
        }

        $user->update($userData);

        // --------------------
        // 2. تحديث العنوان
        // --------------------
        if (
            isset($data['latitude']) ||
            isset($data['longitude']) ||
            isset($data['detailed_address'])
        ) {

            $user->address()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'latitude' => $data['latitude'] ?? null,
                    'longitude' => $data['longitude'] ?? null,
                    'detailed_address' => $data['detailed_address'] ?? null,
                    'area_address_id' => $data['area_address_id'] ?? null,
                ]
            );
        }

        return $user->load('address');
    });
}
}