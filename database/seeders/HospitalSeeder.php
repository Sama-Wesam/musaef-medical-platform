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
        // قائمة المستشفيات والمراكز الطبية ثنائية اللغة
        $facilities = [

            // ==========================================
            // 🏥 1. شمال قطاع غزة
            // ==========================================
            [
                'email'             => 'indonesian@musaef.com',
                'name'              => 'المستشفى الإندونيسي',
                'facility_name'     => 'المستشفى الإندونيسي – بيت لاهيا',
                'facility_name_en'  => 'Indonesian Hospital – Beit Lahia',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. عاطف الكحلوت',
                'license_number'    => 'BB-2015-IND',
                'address'           => 'شمال غزة - بيت لاهيا',
                'address_en'        => 'North Gaza - Beit Lahia',
                'phone'             => '082477777',
                'latitude'          => 31.5380,
                'longitude'         => 34.5020,
            ],
            [
                'email'             => 'kamal_adwan@musaef.com',
                'name'              => 'مستشفى كمال عدوان',
                'facility_name'     => 'مستشفى كمال عدوان – بيت لاهيا',
                'facility_name_en'  => 'Kamal Adwan Hospital – Beit Lahia',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. حسام أبو صفية',
                'license_number'    => 'BB-2005-KA',
                'address'           => 'شمال غزة - بيت لاهيا',
                'address_en'        => 'North Gaza - Beit Lahia',
                'phone'             => '082488888',
                'latitude'          => 31.5490,
                'longitude'         => 34.4980,
            ],
            [
                'email'             => 'awda_jabalia@musaef.com',
                'name'              => 'مستشفى العودة - جباليا',
                'facility_name'     => 'مستشفى العودة – شمال غزة / جباليا',
                'facility_name_en'  => 'Al-Awda Hospital – Jabalia',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. أحمد مهنا',
                'license_number'    => 'BB-1997-AWJ',
                'address'           => 'شمال غزة - تل الزعتر / جباليا',
                'address_en'        => 'North Gaza - Jabalia',
                'phone'             => '082451111',
                'latitude'          => 31.5320,
                'longitude'         => 34.4910,
            ],

            // ==========================================
            // 🏥 2. مدينة غزة
            // ==========================================
            [
                'email'             => 'shifa@musaef.com',
                'name'              => 'مجمع الشفاء الطبي',
                'facility_name'     => 'مجمع الشفاء الطبي – مدينة غزة',
                'facility_name_en'  => 'Al-Shifa Medical Complex',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. مروان أبو سعدة',
                'license_number'    => 'BB-1971-SHF',
                'address'           => 'مدينة غزة - الرمال',
                'address_en'        => 'Gaza City - Rimal',
                'phone'             => '082823400',
                'latitude'          => 31.5167,
                'longitude'         => 34.4500,
            ],
            [
                'email'             => 'ahli@musaef.com',
                'name'              => 'المستشفى الأهلي العربي (المعمداني)',
                'facility_name'     => 'المستشفى الأهلي العربي (المعمداني) – مدينة غزة',
                'facility_name_en'  => 'Al-Ahli Arab Hospital (Al-Mamadani)',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. سهيلة ترازي',
                'license_number'    => 'BB-1882-AHL',
                'address'           => 'مدينة غزة - الزيتون / الشجاعية',
                'address_en'        => 'Gaza City - Zeytoun',
                'phone'             => '082860123',
                'latitude'          => 31.5050,
                'longitude'         => 34.4630,
            ],
            [
                'email'             => 'quds@musaef.com',
                'name'              => 'مستشفى القدس',
                'facility_name'     => 'مستشفى القدس – مدينة غزة',
                'facility_name_en'  => 'Al-Quds Hospital – Gaza City',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. بشار مراد',
                'license_number'    => 'BB-2002-QDS',
                'address'           => 'مدينة غزة - تل الهوى',
                'address_en'        => 'Gaza City - Tel Al-Hawa',
                'phone'             => '082834567',
                'latitude'          => 31.4980,
                'longitude'         => 34.4380,
            ],
            [
                'email'             => 'friends_patient@musaef.com',
                'name'              => 'مستشفى أصدقاء المريض الخيري',
                'facility_name'     => 'مستشفى أصدقاء المريض الخيري – مدينة غزة',
                'facility_name_en'  => 'Patient\'s Friends Benevolent Society',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. سعيد الشرفا',
                'license_number'    => 'BB-1980-FP',
                'address'           => 'مدينة غزة - حي الرمال - شارع الشهداء',
                'address_en'        => 'Gaza City - Rimal St.',
                'phone'             => '082826666',
                'latitude'          => 31.5130,
                'longitude'         => 34.4485,
            ],

            // ==========================================
            // 🏥 3. وسط قطاع غزة – المحافظة الوسطى
            // ==========================================
            [
                'email'             => 'aqsa@musaef.com',
                'name'              => 'مستشفى شهداء الأقصى',
                'facility_name'     => 'مستشفى شهداء الأقصى – دير البلح',
                'facility_name_en'  => 'Al-Aqsa Martyrs Hospital – Deir Al-Balah',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. إياد أبا الجديان',
                'license_number'    => 'BB-2001-AQS',
                'address'           => 'المحافظة الوسطى - دير البلح',
                'address_en'        => 'Middle Area - Deir Al-Balah',
                'phone'             => '082531111',
                'latitude'          => 31.4178,
                'longitude'         => 34.3522,
            ],
            [
                'email'             => 'awda_nuseirat@musaef.com',
                'name'              => 'مستشفى العودة - النصيرات',
                'facility_name'     => 'مستشفى العودة – النصيرات',
                'facility_name_en'  => 'Al-Awda Hospital – Nuseirat',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. ياسر الشاعر',
                'license_number'    => 'BB-1998-AWN',
                'address'           => 'المحافظة الوسطى - النصيرات',
                'address_en'        => 'Middle Area - Nuseirat',
                'phone'             => '082557777',
                'latitude'          => 31.4480,
                'longitude'         => 34.3910,
            ],

            // ==========================================
            // 🏥 4. جنوب قطاع غزة – خان يونس
            // ==========================================
            [
                'email'             => 'nasser@musaef.com',
                'name'              => 'مجمع ناصر الطبي',
                'facility_name'     => 'مجمع ناصر الطبي – خان يونس',
                'facility_name_en'  => 'Nasser Medical Complex – Khan Younis',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. عاطف الحوت',
                'license_number'    => 'BB-1985-NSR',
                'address'           => 'خان يونس - وسط المدينة',
                'address_en'        => 'Khan Younis - Center',
                'phone'             => '082055555',
                'latitude'          => 31.3450,
                'longitude'         => 34.3030,
            ],
            [
                'email'             => 'european@musaef.com',
                'name'              => 'المستشفى الأوروبي',
                'facility_name'     => 'المستشفى الأوروبي – خان يونس',
                'facility_name_en'  => 'Gaza European Hospital',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. يوسف العقاد',
                'license_number'    => 'BB-2000-EUR',
                'address'           => 'خان يونس - الفخاري',
                'address_en'        => 'Khan Younis - Al-Fukhari',
                'phone'             => '082071111',
                'latitude'          => 31.3090,
                'longitude'         => 34.3390,
            ],
            [
                'email'             => 'red_crescent_khanyounis@musaef.com',
                'name'              => 'مستشفى الهلال الأحمر الفلسطيني',
                'facility_name'     => 'مستشفى الهلال الأحمر الفلسطيني – خان يونس',
                'facility_name_en'  => 'PRCS Hospital – Khan Younis',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. حيدر القدرة',
                'license_number'    => 'BB-2003-PRCS',
                'address'           => 'خان يونس - حي الأمل',
                'address_en'        => 'Khan Younis - Al-Amal',
                'phone'             => '082062222',
                'latitude'          => 31.3520,
                'longitude'         => 34.2980,
            ],

            // ==========================================
            // 🏥 5. أقصى الجنوب – رفح
            // ==========================================
            [
                'email'             => 'najjar@musaef.com',
                'name'              => 'مستشفى أبو يوسف النجار',
                'facility_name'     => 'مستشفى أبو يوسف النجار – رفح',
                'facility_name_en'  => 'Abu Yousef Al-Najjar Hospital – Rafah',
                'facility_type'     => 'حكومي',
                'facility_type_en'  => 'Government Hospital',
                'manager_name'      => 'د. مروان الهمص',
                'license_number'    => 'BB-2000-NJR',
                'address'           => 'رفح - حي الجنينة',
                'address_en'        => 'Rafah - Al-Jnena',
                'phone'             => '082133333',
                'latitude'          => 31.2910,
                'longitude'         => 34.2580,
            ],
            [
                'email'             => 'kuwaiti@musaef.com',
                'name'              => 'مستشفى الكويت التخصصي',
                'facility_name'     => 'مستشفى الكويت التخصصي – رفح',
                'facility_name_en'  => 'Kuwait Specialty Hospital – Rafah',
                'facility_type'     => 'أهلي خيري',
                'facility_type_en'  => 'Charity Hospital',
                'manager_name'      => 'د. صهيب الهمص',
                'license_number'    => 'BB-2007-KWT',
                'address'           => 'رفح - وسط البلد',
                'address_en'        => 'Rafah - Center',
                'phone'             => '082144444',
                'latitude'          => 31.2820,
                'longitude'         => 34.2510,
            ],
        ];

        foreach ($facilities as $facility) {
            // 1. إنشاء أو تحديث حساب المستخدم
            $user = User::updateOrCreate(
                ['email' => $facility['email']],
                [
                    'name'     => $facility['name'],
                    'password' => Hash::make('password123'),
                    'role'     => 'hospital',
                    'phone'    => $facility['phone'],
                ]
            );

            // 2. ربط حساب المستشفى والتفاصيل مع مراعاة اللغتين
            Hospital::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'facility_name'    => $facility['facility_name'],
                    'facility_name_en' => $facility['facility_name_en'],
                    'facility_type'    => $facility['facility_type'],
                    'facility_type_en' => $facility['facility_type_en'],
                    'manager_name'     => $facility['manager_name'],
                    'license_number'   => $facility['license_number'],
                    'address'          => $facility['address'],
                    'address_en'       => $facility['address_en'],
                    'latitude'         => $facility['latitude'],
                    'longitude'        => $facility['longitude'],
                    'is_verified'      => true,
                ]
            );
        }
    }
}
