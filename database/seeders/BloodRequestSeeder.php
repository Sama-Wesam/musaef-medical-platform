<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\BloodType;
use App\Enums\EmergencyLevel;
use App\Enums\RequestStatus;

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

        // تحديد قيم مستويات الطوارئ مع مراعاة وجود Enums
        $emergencyLevels = [EmergencyLevel::HIGH, EmergencyLevel::CRITICAL, EmergencyLevel::NORMAL];
        if (!class_exists(EmergencyLevel::class)) {
            $emergencyLevels = ['high', 'critical', 'normal'];
        }

        // 🛠️ تحديد حالة الطلب بشكل نصي مرن يتوافق مع استعلام الصفحة الرئيسية
        $statusValue = 'searching';

        // إنشاء 8 حالات طارئة متنوعة لتعبئة الصفحة الرئيسية ورادار الطوارئ مباشرة
        foreach (range(1, 8) as $index) {
            $randomLevel = $emergencyLevels[array_rand($emergencyLevels)];

            BloodRequest::create([
                'hospital_id'     => $hospitals->random()->id,
                'blood_type_id'   => $bloodTypes->random()->id,
                'units_required'  => rand(2, 6),
                'emergency_level' => $randomLevel,
                'status'          => $statusValue,
            ]);
        }
    }
}
