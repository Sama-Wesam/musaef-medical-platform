<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hospital;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // قائمة بجميع المستشفيات وبنوك الدم في قطاع غزة
        $facilities = [
            // --- بنوك الدم المركزية ---
            ['name' => 'جمعية بنك الدم المركزي', 'email' => 'central_bb@musaef.com', 'address' => 'قطاع غزة - تأسس عام 1971', 'lat' => 31.5167, 'lng' => 34.4500, 'license' => 'BB-1971'],
            ['name' => 'بنك الدم المركزي - وزارة الصحة', 'email' => 'moh_bb@musaef.com', 'address' => 'قطاع غزة - وزارة الصحة', 'lat' => 31.5160, 'lng' => 34.4450, 'license' => 'BB-MOH'],

            // --- محافظة غزة ---
            ['name' => 'مجمع الشفاء الطبي', 'email' => 'shifa@musaef.com', 'address' => 'محافظة غزة', 'lat' => 31.5273, 'lng' => 34.4447, 'license' => 'HOSP-GZ-01'],
            ['name' => 'مستشفى الأهلي العربي (المعمداني)', 'email' => 'ahli@musaef.com', 'address' => 'محافظة غزة', 'lat' => 31.5050, 'lng' => 34.4550, 'license' => 'HOSP-GZ-02'],
            ['name' => 'مستشفى القدس', 'email' => 'quds@musaef.com', 'address' => 'محافظة غزة', 'lat' => 31.5100, 'lng' => 34.4400, 'license' => 'HOSP-GZ-03'],
            ['name' => 'مستشفى العيون', 'email' => 'eye_hosp@musaef.com', 'address' => 'محافظة غزة', 'lat' => 31.5200, 'lng' => 34.4450, 'license' => 'HOSP-GZ-04'],
            ['name' => 'مستشفى الرنتيسي للأطفال', 'email' => 'rantisi@musaef.com', 'address' => 'محافظة غزة', 'lat' => 31.5300, 'lng' => 34.4500, 'license' => 'HOSP-GZ-05'],
            ['name' => 'مستشفى الصحة النفسية', 'email' => 'mental_health@musaef.com', 'address' => 'محافظة غزة', 'lat' => 31.5250, 'lng' => 34.4480, 'license' => 'HOSP-GZ-06'],

            // --- شمال غزة ---
            ['name' => 'مستشفى كمال عدوان', 'email' => 'kamal_adwan@musaef.com', 'address' => 'شمال غزة', 'lat' => 31.5600, 'lng' => 34.4900, 'license' => 'HOSP-NG-01'],
            ['name' => 'مستشفى العودة', 'email' => 'awda@musaef.com', 'address' => 'شمال غزة', 'lat' => 31.5500, 'lng' => 34.4800, 'license' => 'HOSP-NG-02'],
            ['name' => 'المستشفى الإندونيسي', 'email' => 'indonesian@musaef.com', 'address' => 'شمال غزة', 'lat' => 31.5650, 'lng' => 34.5000, 'license' => 'HOSP-NG-03'],

            // --- دير البلح (المحافظة الوسطى) ---
            ['name' => 'مستشفى شهداء الأقصى', 'email' => 'aqsa@musaef.com', 'address' => 'دير البلح - المحافظة الوسطى', 'lat' => 31.4167, 'lng' => 34.3500, 'license' => 'HOSP-DB-01'],

            // --- خان يونس ---
            ['name' => 'مجمع ناصر الطبي', 'email' => 'nasser@musaef.com', 'address' => 'خان يونس', 'lat' => 31.3462, 'lng' => 34.3063, 'license' => 'HOSP-KY-01'],
            ['name' => 'مستشفى غزة الأوروبي', 'email' => 'european@musaef.com', 'address' => 'خان يونس', 'lat' => 31.3200, 'lng' => 34.3300, 'license' => 'HOSP-KY-02'],
            ['name' => 'مستشفى الأمل', 'email' => 'amal@musaef.com', 'address' => 'خان يونس', 'lat' => 31.3500, 'lng' => 34.3000, 'license' => 'HOSP-KY-03'],

            // --- رفح ---
            ['name' => 'مستشفى أبو يوسف النجار', 'email' => 'najjar@musaef.com', 'address' => 'رفح', 'lat' => 31.2968, 'lng' => 34.2455, 'license' => 'HOSP-RF-01'],
            ['name' => 'مستشفى الكويت التخصصي', 'email' => 'kuwait@musaef.com', 'address' => 'رفح', 'lat' => 31.2900, 'lng' => 34.2400, 'license' => 'HOSP-RF-02'],
        ];

        // حلقة التكرار لإنشاء الحسابات والمستشفيات بشكل آلي ونظيف
        foreach ($facilities as $facility) {
            
            // 1. إنشاء حساب المستخدم للمستشفى (لتسجيل الدخول)
            $user = User::firstOrCreate(
                ['email' => $facility['email']],
                [
                    'name' => $facility['name'],
                    'password' => Hash::make('password123'), // كلمة المرور الموحدة لسهولة التجربة
                    'role' => 'hospital',
                    'email_verified_at' => now(),
                ]
            );

            // 2. إدخال بيانات المستشفى في الجدول الخاص به
            Hospital::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'license_number' => $facility['license'],
                    'address' => $facility['address'],
                    'latitude' => $facility['lat'],
                    'longitude' => $facility['lng'],
                    'is_verified' => true, // تم التوثيق مسبقاً لأنها مستشفيات رسمية
                ]
            );
        }
    }
}