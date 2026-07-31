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
        // استدعاء ملفات الـ Seeders بالترتيب المنطقي لمنع أخطاء المفاتيح الأجنبية
        $this->call([
            BloodTypeSeeder::class,   // 1. تهيئة فصائل الدم الثمانية
            UserSeeder::class,        // 2. إنشاء حساب الأدمن
            HospitalSeeder::class,    // 3. إنشاء المستشفيات وحساباتها
            DonorSeeder::class,       // 4. إنشاء المتبرعين وتوزيعهُم في غزة
            BloodRequestSeeder::class // 5. إطلاق النداءات والحالات الطارئة للحسابات
        ]);
    }
}
