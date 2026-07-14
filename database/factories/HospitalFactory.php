<?php

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class HospitalFactory extends Factory
{
    protected $model = Hospital::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(['role' => 'hospital']),
            'license_number' => 'LIC-' . fake()->unique()->numberBetween(100000, 999999),
            'address' => fake()->address(),
            'latitude' => fake()->latitude(24.0, 26.0), // إحداثيات قريبة لحساب المسافات عبر الـ Trait
            'longitude' => fake()->longitude(43.0, 45.0),
            'is_verified' => true, // توثيق تلقائي لتسهيل عمليات الفحص الفوري للطلبات
        ];
    }
}
