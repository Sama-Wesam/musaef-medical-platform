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
        // استدعاء ملفات الـ Seeders بالترتيب المنطقي الصحيح لمنع أخطاء المفاتيح الأجنبية وتجهيز رادار الطوارئ
        $this->call([
            BloodTypeSeeder::class,      // 1. تهيئة فصائل الدم الثمانية
            UserSeeder::class,           // 2. إنشاء حساب الأدمن الرئيسي
            HospitalSeeder::class,       // 3. إنشاء المستشفيات وحساباتها الواقعية في قطاع غزة
            DonorSeeder::class,          // 4. إنشاء المتبرعين وتوزيعهُم الجغرافي
            BloodRequestSeeder::class,   // 5. إطلاق النداءات والحالات الطارئة النشطة للـ Polling
            BloodInventorySeeder::class, // 6. تعبئة مخزون بنوك الدم بالأرقام الحقيقية لوحة التحكم
        ]);
    }
}
