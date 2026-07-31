<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donor;
use App\Models\HealthInfo;

class DonorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // توليد 25 متبرعاً وهمياً مع بياناتهم الصحية لاختبار رادار الخريطة والمطابقة الذكية
        Donor::factory(25)->create()->each(function ($donor) {
            HealthInfo::create([
                'donor_id' => $donor->id,
                'weight' => fake()->randomFloat(2, 55, 95),
                'height' => fake()->randomFloat(2, 160, 190),
                'has_chronic_diseases' => false,
                'diseases_description' => null,
                'is_eligible' => true,
                'rejection_reason' => null,
            ]);
        });
    }
}
