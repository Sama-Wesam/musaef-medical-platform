<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Donor;
use App\Models\HealthInfo;
use Illuminate\Support\Facades\Hash;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. استخدام الـ Factory لتوليد متبرعين ببيانات فلسطينية حقيقية
        try {
            if (class_exists(\Database\Factories\DonorFactory::class)) {
                Donor::factory(20)->create()->each(function ($donor) {
                    HealthInfo::create([
                        'donor_id'             => $donor->id,
                        'weight'               => rand(60, 90),
                        'height'               => rand(160, 188),
                        'has_chronic_diseases' => false,
                        'diseases_description' => null,
                        'is_eligible'          => true,
                        'rejection_reason'     => null,
                    ]);
                });
                return;
            }
        } catch (\Throwable $e) {
            // في حال حدوث خطأ ننتقل للإنشاء المباشر
        }

        // 2. إنشاء قائمة متبرعين مباشرة بأسماء وأرقام فلسطينية محلية
        $arabicDonors = [
            ['name' => 'سما وسام', 'phone' => '0599123456', 'blood_type_id' => 1, 'address' => 'غزة - الرمال'],
            ['name' => 'سيرين الصامل', 'phone' => '0598765432', 'blood_type_id' => 7, 'address' => 'دير البلح'],
            ['name' => 'رحمة السليم', 'phone' => '0597112233', 'blood_type_id' => 2, 'address' => 'خانيونس'],
            ['name' => 'براء الشهري', 'phone' => '0595445566', 'blood_type_id' => 7, 'address' => 'رفح'],
            ['name' => 'هشام وجيه', 'phone' => '0569887766', 'blood_type_id' => 3, 'address' => 'النصيرات'],
            ['name' => 'وجدان برماوي', 'phone' => '0568990011', 'blood_type_id' => 4, 'address' => 'شمال غزة'],
            ['name' => 'محمد حسن', 'phone' => '0599987654', 'blood_type_id' => 2, 'address' => 'غزة - تل الهوا'],
        ];

        foreach ($arabicDonors as $index => $data) {
            $user = User::firstOrCreate(
                ['email' => "donor{$index}@musaef.ps"],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'donor',
                    'phone'    => $data['phone'],
                ]
            );

            $donor = Donor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type_id' => $data['blood_type_id'],
                    'city'          => 'غزة',
                    'address'       => $data['address'],
                    'is_available'  => true,
                ]
            );

            HealthInfo::firstOrCreate(
                ['donor_id' => $donor->id],
                [
                    'weight'               => rand(60, 85),
                    'height'               => rand(165, 185),
                    'has_chronic_diseases' => false,
                    'diseases_description' => null,
                    'is_eligible'          => true,
                    'rejection_reason'     => null,
                ]
            );
        }
    }
}
