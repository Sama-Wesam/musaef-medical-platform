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
            'user_id'        => User::factory(['role' => 'hospital']),
            'facility_name'  => 'مستشفى ' . fake()->company(),
            'facility_type'  => 'مستشفى عام',
            'license_number' => 'LIC-' . fake()->unique()->numberBetween(100000, 999999),
            'address'        => fake()->address(),
            'latitude'       => fake()->latitude(31.2, 31.6),
            'longitude'      => fake()->longitude(34.2, 34.6),
            'is_verified'    => true,
        ];
    }
}
