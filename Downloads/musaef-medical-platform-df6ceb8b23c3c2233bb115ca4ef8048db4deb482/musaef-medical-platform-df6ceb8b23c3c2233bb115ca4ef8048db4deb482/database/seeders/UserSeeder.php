<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء حساب مدير النظام الافتراضي
        User::firstOrCreate(
            ['email' => 'admin@musaef.com'],
            [
                'name' => 'مدير النظام - Super Admin',
                'password' => Hash::make('password123'), // كلمة المرور الافتراضية
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );
    }
}