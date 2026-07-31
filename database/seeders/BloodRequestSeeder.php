<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\BloodType;

class BloodRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        $bloodTypes = BloodType::all();

        // تجنب حدوث خطأ في حال عدم وجود مستشفيات أو فصائل دم
        if ($hospitals->isEmpty() || $bloodTypes->isEmpty()) {
            return;
        }

        $emergencyLevels = ['normal', 'high', 'critical'];

        // إنشاء 8 حالات طارئة متنوعة لتعبئة الصفحة الرئيسية ورادار الطوارئ مباشرة
        foreach (range(1, 8) as $index) {
            BloodRequest::create([
                'hospital_id'     => $hospitals->random()->id,
                'blood_type_id'   => $bloodTypes->random()->id,
                'units_required'  => rand(2, 6),
                'emergency_level' => $emergencyLevels[array_rand($emergencyLevels)],
                'status'          => 'searching',
            ]);
        }
    }
}
