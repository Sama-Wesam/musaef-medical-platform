<?php

namespace Database\Factories;

use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\BloodType;
use Illuminate\Database\Eloquent\Factories\Factory;

class BloodRequestFactory extends Factory
{
    protected $model = BloodRequest::class;

    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::factory(),
            'blood_type_id' => fake()->randomElement([1, 2, 3, 4, 5, 6, 7, 8]),
            'units_required' => fake()->numberBetween(1, 15), // متوافق مع قيود الـ Validation والحد الأقصى[cite: 70]
            'emergency_level' => fake()->randomElement(['normal', 'high', 'critical']),
            'status' => fake()->randomElement(['pending', 'searching', 'accepted']),
        ];
    }
}
