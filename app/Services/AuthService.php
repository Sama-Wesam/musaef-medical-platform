<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Donor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البريد الإلكتروني أو كلمة المرور غير صحيحة.'],
            ]);
        }

        $token = $user->createToken('musaef_auth_token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    public function registerDonor(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $userRole = defined('App\Enums\UserRole::DONOR') ? UserRole::DONOR->value : 'donor';

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => $userRole,
            ]);

            $donor = Donor::create([
                'user_id'       => $user->id,
                'blood_type_id' => $data['blood_type_id'],
                'birth_date'    => $data['birth_date'],
                'gender'        => $data['gender'],
                'is_available'  => true,
            ]);

            return ['user' => $user, 'donor' => $donor];
        });
    }
}
