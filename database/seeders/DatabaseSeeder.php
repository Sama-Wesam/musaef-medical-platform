<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // استدعاء ملفات الـ Seeders بالترتيب المنطقي
        $this->call([
            RoleSeeder::class,
            BloodTypeSeeder::class,
            UserSeeder::class,
            HospitalSeeder::class,
            // يمكنك لاحقاً إضافة DonorSeeder لتوليد متبرعين وهميين لاختبار المطابقة الذكية
        ]);
    }
}