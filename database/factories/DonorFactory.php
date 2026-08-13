<?php

namespace Database\Factories;

use App\Models\Donor;
use App\Models\User;
use App\Models\BloodType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DonorFactory extends Factory
{
    protected $model = Donor::class;

    public function definition(): array
    {
        // قوائم بيانات حقيقية لقطاع غزة لتفادي توليد faker الأجنبي
        $addresses = [
            'غزة - الرمال', 'غزة - تل الهوا', 'خانيونس - البلد',
            'دير البلح - شارع النخيل', 'رفح - حي الأمل', 'شمال غزة - معسكر جباليا',
            'النصيرات - المخيم الجديد', 'البريج - الشارع العام'
        ];

        // توليد رقم هاتف جوال/أوريدو فلسطيني حقيقي
        $prefixes = ['0599', '0598', '0597', '0595', '0569', '0568'];
        $randomPhone = $prefixes[array_rand($prefixes)] . rand(100000, 999999);

        // قوائم أسماء عربية محلية حقيقية
        $firstNames = ['محمد', 'أحمد', 'محمود', 'عمر', 'خالد', 'سارة', 'فاطمة', 'مريم', 'هدى', 'يوسف', 'حسن', 'إيمان', 'براء', 'شذا', 'سما'];
        $fatherNames = ['حسن', 'وسام', 'جميل', 'خالد', 'تامر', 'وجيه', 'مصطفى', 'محمود', 'علي'];
        $familyNames = ['الشهري', 'الأحمدي', 'الصامل', 'السليم', 'برماوي', 'العبد', 'القاسم', 'حسن', 'يوسف'];

        $randomName = $firstNames[array_rand($firstNames)] . ' ' . $fatherNames[array_rand($fatherNames)] . ' ' . $familyNames[array_rand($familyNames)];

        return [
            // إنشاء مستخدم بدور متبرع مع اسم ورقم هاتف فلسطيني حقيقي
            'user_id' => User::factory([
                'name'  => $randomName,
                'phone' => $randomPhone,
                'role'  => 'donor'
            ]),
            'blood_type_id'      => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
            'birth_date'         => fake()->date('Y-m-d', '-18 years'),
            'gender'             => fake()->randomElement(['male', 'female']),
            'latitude'           => fake()->latitude(31.2, 31.6), // إحداثيات غزة
            'longitude'          => fake()->longitude(34.2, 34.6),
            'address'            => fake()->randomElement($addresses),
            'is_available'       => fake()->boolean(85),
            'last_donation_date' => fake()->optional(0.6)->date('Y-m-d', '-4 months'),
        ];
    }
}
