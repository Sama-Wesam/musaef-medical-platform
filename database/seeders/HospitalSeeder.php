<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // قائمة الجهات الطبية بحساباتها وبياناتها التفصيلية
        $facilities = [
            [
                'email'          => 'shifa@musaef.com',
                'name'           => 'مجمع الشفاء الطبي',
                'facility_name'  => 'مجمع الشفاء الطبي',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. مروان أبو سعدة',
                'license_number' => 'BB-1971',
                'address'        => 'غزة - الرمال',
                'phone'          => '082823400',
                'latitude'       => 31.5167,
                'longitude'      => 34.4500,
            ],
            [
                'email'          => 'central_bb@musaef.com',
                'name'           => 'جمعية بنك الدم المركزي',
                'facility_name'  => 'جمعية بنك الدم المركزي',
                'facility_type'  => 'أهلي خيري',
                'manager_name'   => 'د. زياد شعت',
                'license_number' => 'BB-1980',
                'address'        => 'غزة - الرمال شارع الوحدة',
                'phone'          => '082845600',
                'latitude'       => 31.5190,
                'longitude'      => 34.4530,
            ],
            [
                'email'          => 'moh_bb@musaef.com',
                'name'           => 'بنك الدم المركزي - وزارة الصحة',
                'facility_name'  => 'بنك الدم المركزي - وزارة الصحة',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. أيمن عكلوك',
                'license_number' => 'BB-1995',
                'address'        => 'غزة - النصر',
                'phone'          => '082881234',
                'latitude'       => 31.5250,
                'longitude'      => 34.4480,
            ],
            [
                'email'          => 'ahli@musaef.com',
                'name'           => 'مستشفى الأهلي العربي (المعمداني)',
                'facility_name'  => 'مستشفى الأهلي العربي (المعمداني)',
                'facility_type'  => 'أهلي خيري',
                'manager_name'   => 'د. سهيلة ترازي',
                'license_number' => 'BB-1882',
                'address'        => 'غزة - الزيتون',
                'phone'          => '082860123',
                'latitude'       => 31.5050,
                'longitude'      => 34.4630,
            ],
            [
                'email'          => 'quds@musaef.com',
                'name'           => 'مستشفى القدس',
                'facility_name'  => 'مستشفى القدس - الهلال الأحمر',
                'facility_type'  => 'أهلي خيري',
                'manager_name'   => 'د. بشار مراد',
                'license_number' => 'BB-2002',
                'address'        => 'غزة - تل الهوى',
                'phone'          => '082834567',
                'latitude'       => 31.4980,
                'longitude'      => 34.4380,
            ],
            [
                'email'          => 'friends_patient@musaef.com',
                'name'           => 'مستشفى أصدقاء المريض الخيري',
                'facility_name'  => 'مستشفى أصدقاء المريض الخيري',
                'facility_type'  => 'أهلي خيري',
                'manager_name'   => 'د. سعيد الشرفا',
                'license_number' => 'BB-1980-FP',
                'address'        => 'غزة - حي الرمال - شارع الشهداء',
                'phone'          => '082826666',
                'latitude'       => 31.5130,
                'longitude'      => 34.4485,
            ],
            [
                'email'          => 'kamal_adwan@musaef.com',
                'name'           => 'مستشفى كمال عدوان',
                'facility_name'  => 'مستشفى كمال عدوان',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. حسام أبو صفية',
                'license_number' => 'BB-2005',
                'address'        => 'شمال غزة - بيت لاهيا',
                'phone'          => '082488888',
                'latitude'       => 31.5490,
                'longitude'      => 34.4980,
            ],
            [
                'email'          => 'indonesian@musaef.com',
                'name'           => 'المستشفى الإندونيسي',
                'facility_name'  => 'المستشفى الإندونيسي',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. عاطف الكحلوت',
                'license_number' => 'BB-2015',
                'address'        => 'شمال غزة - بيت لاهيا',
                'phone'          => '082477777',
                'latitude'       => 31.5380,
                'longitude'      => 34.5020,
            ],
            [
                'email'          => 'awda_nuseirat@musaef.com',
                'name'           => 'مستشفى العودة - النصيرات',
                'facility_name'  => 'مستشفى العودة - النصيرات',
                'facility_type'  => 'أهلي خيري',
                'manager_name'   => 'د. أحمد مهنا',
                'license_number' => 'BB-1998-AW',
                'address'        => 'المحافظة الوسطى - النصيرات',
                'phone'          => '082557777',
                'latitude'       => 31.4480,
                'longitude'      => 34.3910,
            ],
            [
                'email'          => 'aqsa@musaef.com',
                'name'           => 'مستشفى شهداء الأقصى',
                'facility_name'  => 'مستشفى شهداء الأقصى',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. إياد أبا الجديان',
                'license_number' => 'BB-2001',
                'address'        => 'المحافظة الوسطى - دير البلح',
                'phone'          => '082531111',
                'latitude'       => 31.4178,
                'longitude'      => 34.3522,
            ],
            [
                'email'          => 'nasser@musaef.com',
                'name'           => 'مجمع ناصر الطبي',
                'facility_name'  => 'مجمع ناصر الطبي',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. عاطف الحوت',
                'license_number' => 'BB-1985',
                'address'        => 'خانيونس - وسط المدينة',
                'phone'          => '082055555',
                'latitude'       => 31.3450,
                'longitude'      => 34.3030,
            ],
            [
                'email'          => 'najjar@musaef.com',
                'name'           => 'مستشفى أبو يوسف النجار',
                'facility_name'  => 'مستشفى أبو يوسف النجار',
                'facility_type'  => 'حكومي',
                'manager_name'   => 'د. مروان الهمص',
                'license_number' => 'BB-2000',
                'address'        => 'رفح - الجنينة',
                'phone'          => '082133333',
                'latitude'       => 31.2910,
                'longitude'      => 34.2580,
            ],
        ];

        foreach ($facilities as $facility) {
            // 1. إنشاء أو تحديث حساب المستخدم مع تشفير كلمة المرور صراحة
            $user = User::updateOrCreate(
                ['email' => $facility['email']],
                [
                    'name'     => $facility['name'],
                    'password' => Hash::make('password123'), // التشفير الصريح لضمان نجاح تسجيل الدخول
                    'role'     => 'hospital',
                    'phone'    => $facility['phone'],
                ]
            );

            // 2. ربط حساب المستخدم بسجل المستشفى
            Hospital::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'facility_name'  => $facility['facility_name'],
                    'facility_type'  => $facility['facility_type'],
                    'manager_name'   => $facility['manager_name'],
                    'license_number' => $facility['license_number'],
                    'address'        => $facility['address'],
                    'latitude'       => $facility['latitude'],
                    'longitude'      => $facility['longitude'],
                    'is_verified'    => true,
                ]
            );
        }
    }
}
