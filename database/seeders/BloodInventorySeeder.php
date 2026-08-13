<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\BloodType;
use App\Models\BloodInventory;

class BloodInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        $bloodTypes = BloodType::all();

        if ($hospitals->isEmpty() || $bloodTypes->isEmpty()) {
            return;
        }

        // إسناد كميات متوازنة ومتنوعة من وحدات الدم لكل مستشفى ولكل فصيلة
        foreach ($hospitals as $hospital) {
            foreach ($bloodTypes as $type) {
                // إعطاء كميات عشوائية واقعية بين 5 و 28 وحدة لكل فصيلة
                $availableUnits = rand(5, 28);

                BloodInventory::updateOrCreate(
                    [
                        'hospital_id'   => $hospital->id,
                        'blood_type_id' => $type->id,
                    ],
                    [
                        'units_available' => $availableUnits,
                        'min_threshold'   => 10,
                    ]
                );
            }
        }
    }
}
