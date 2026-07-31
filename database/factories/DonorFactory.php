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
        return [
            // يعتمد على إنشاء مستخدم بدور متبرع أولاً لربط المفتاح الأجنبي الصحيح
            'user_id' => User::factory(['role' => 'donor']),
            'blood_type_id' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8]), // فصائل الدم الثمانية
            'birth_date' => fake()->date('Y-m-d', '-18 years'), // يضمن أن عمر المتبرع فوق 18 سنة قانونياً
            'gender' => fake()->randomElement(['male', 'female']),
            'latitude' => fake()->latitude(31.2, 31.6), // إحداثيات جغرافية دقيقة متوافقة مع قطاع غزة
            'longitude' => fake()->longitude(34.2, 34.6),
            'address' => fake()->address(),
            'is_available' => fake()->boolean(80), // 80% من المتبرعين متاحين افتراضياً
            'last_donation_date' => fake()->optional(0.5)->date('Y-m-d', '-4 months'),
        ];
    }
}
