<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BloodType;

class BloodTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bloodTypes = [
            'O+', 'O-', 
            'A+', 'A-', 
            'B+', 'B-', 
            'AB+', 'AB-'
        ];

        foreach ($bloodTypes as $type) {
            BloodType::firstOrCreate([
                'name' => $type
            ]);
        }
    }
}