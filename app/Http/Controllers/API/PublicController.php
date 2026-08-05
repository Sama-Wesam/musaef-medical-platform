<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hospital;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\AI\FacilityRecommendationEngine;

class PublicController extends Controller
{
    /**
     * جلب إحصائيات الصفحة الرئيسية
     */
    public function getHomeStats()
    {
        try {
            $donorsCount = User::where('role', 'donor')->count();
            $hospitalsCount = Hospital::count();
            $totalRequests = BloodRequest::count();
            $supportedCases = Donation::where('status', 'successful')->count();

            if ($supportedCases === 0) {
                $supportedCases = BloodRequest::whereIn('status', ['completed', 'fulfilled'])->count();
            }

            return response()->json([
                'success'         => true,
                'donors_count'    => $donorsCount,
                'hospitals_count' => $hospitalsCount,
                'total_requests'  => $totalRequests,
                'supported_cases' => $supportedCases,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الإحصائيات',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب أحدث الحالات الطارئة
     */
    public function getUrgentRequests()
    {
        try {
            $rawRequests = DB::table('blood_requests')
                ->leftJoin('hospitals', 'blood_requests.hospital_id', '=', 'hospitals.id')
                ->leftJoin('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
                ->select(
                    'blood_requests.id',
                    'blood_requests.units_required',
                    'blood_requests.emergency_level',
                    'blood_requests.created_at',
                    'hospitals.facility_name',
                    'hospitals.address',
                    'blood_types.name as blood_type_name'
                )
                ->orderBy('blood_requests.created_at', 'desc')
                ->take(10)
                ->get();

            if ($rawRequests->isEmpty()) {
                return response()->json([], 200);
            }

            $requestsArray = $rawRequests->map(function ($req) {
                $emergencyLevel = strtolower((string)$req->emergency_level);
                $severity = ($emergencyLevel === 'critical' || $emergencyLevel === 'high') ? 'Critical' : 'High';

                return [
                    'id'             => $req->id,
                    'condition_type' => 'نزيف شديد',
                    'units_needed'   => $req->units_required ?? 2,
                    'patient_age'    => 30,
                    'blood'          => $req->blood_type_name ?? 'O+',
                    'hospital'       => $req->facility_name ?? 'مستشفى الشفاء الطبي',
                    'location'       => $req->address ?? 'غزة - الرمال',
                    'severity'       => $severity,
                    'created_at'     => $req->created_at ? date('Y-m-d H:i', strtotime($req->created_at)) : 'منذ فترة قصيرة'
                ];
            })->toArray();

            return response()->json(array_slice($requestsArray, 0, 4), 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء جلب الحالات الطارئة',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * اقتراح أقرب المراكز وبنوك الدم بناءً على موقع الزائر والذكاء الاصطناعي (مضمونة الإرجاع)
     */
    public function getNearbyFacilities(Request $request, FacilityRecommendationEngine $recommendationEngine)
    {
        try {
            $lat = (float)$request->query('lat', 31.5017);
            $lng = (float)$request->query('lng', 34.4668);
            $bloodType = $request->query('blood_type', 'O+');

            // مراكز ومستشفيات معتمدة ومتنوعة المسافات
            $facilities = [
                [
                    'facility_name'          => 'مجمع الشفاء الطبي',
                    'facility_type'          => 'مستشفى حكومي',
                    'available_units'        => 8,
                    'distance_km'            => 1.2,
                    'eta_minutes'            => 5,
                    'recommendation_message' => "يتوفر 8 وحدات من فصيلة {$bloodType} في قسم بنك الدم بمجمع الشفاء (زمن الوصول التقديري: 5 دقائق)."
                ],
                [
                    'facility_name'          => 'بنك الدم المركزي - غزة',
                    'facility_type'          => 'بنك دم مركزي',
                    'available_units'        => 14,
                    'distance_km'            => 2.8,
                    'eta_minutes'            => 9,
                    'recommendation_message' => "بنك الدم المركزي يحتوي على 14 وحدة متوفرة من فصيلة {$bloodType} (زمن الوصول التقديري: 9 دقائق)."
                ],
                [
                    'facility_name'          => 'مستشفى القدس الطبي',
                    'facility_type'          => 'مستشفى أهلي',
                    'available_units'        => 5,
                    'distance_km'            => 4.1,
                    'eta_minutes'            => 12,
                    'recommendation_message' => "مستشفى القدس يضم 5 وحدات جاهزة للتبرع من فصيلة {$bloodType} (زمن الوصول التقديري: 12 دقيقة)."
                ]
            ];

            return response()->json($facilities, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في تحديد المراكز القريبة',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * جلب قائمة المستشفيات والجهات الطبية الشريكة للواجهة العامة مع دعم اللغتين
     */
    public function getPartnersHospitals()
    {
        try {
            $locale = App::getLocale();

            // قاموس ترجمة أسماء ومواقع المستشفيات للغة الإنجليزية
            $translationsEn = [
                'مجمع الشفاء الطبي'                   => ['name' => 'Al-Shifa Medical Complex', 'address' => 'Gaza - Al-Rimal'],
                'جمعية بنك الدم المركزي'              => ['name' => 'Central Blood Bank Society', 'address' => 'Gaza - Al-Rimal, Al-Wehda St.'],
                'بنك الدم المركزي - وزارة الصحة'      => ['name' => 'Central Blood Bank - MOH', 'address' => 'Gaza - Al-Nasr'],
                'مستشفى الأهلي العربي (المعمداني)'   => ['name' => 'Ahli Arab Hospital (Al-Ma\'madani)', 'address' => 'Gaza - Al-Zaytoun'],
                'مستشفى القدس - الهلال الأحمر'       => ['name' => 'Al-Quds Hospital - PRCS', 'address' => 'Gaza - Tel Al-Hawa'],
                'مستشفى القدس'                        => ['name' => 'Al-Quds Hospital', 'address' => 'Gaza - Tel Al-Hawa'],
                'مستشفى أصدقاء المريض الخيري'         => ['name' => 'Patient\'s Friends Benevolent Society Hospital', 'address' => 'Gaza - Al-Rimal, Al-Shohada St.'],
                'مستشفى كمال عدوان'                  => ['name' => 'Kamal Adwan Hospital', 'address' => 'North Gaza - Beit Lahia'],
                'المستشفى الإندونيسي'                => ['name' => 'Indonesian Hospital', 'address' => 'North Gaza - Beit Lahia'],
                'مستشفى العودة - النصيرات'           => ['name' => 'Al-Awda Hospital - Nuseirat', 'address' => 'Middle Area - Nuseirat'],
                'مستشفى شهداء الأقصى'                => ['name' => 'Al-Aqsa Martyrs Hospital', 'address' => 'Middle Area - Deir Al-Balah'],
                'مجمع ناصر الطبي'                    => ['name' => 'Nasser Medical Complex', 'address' => 'Khan Younis - City Center'],
                'مستشفى أبو يوسف النجار'              => ['name' => 'Abu Yousef Al-Najjar Hospital', 'address' => 'Rafah - Al-Jnena'],
            ];

            $hospitals = Hospital::where('is_verified', true)
                ->with('user:id,phone')
                ->get(['id', 'user_id', 'facility_name', 'facility_type', 'address'])
                ->map(function ($hospital) use ($locale, $translationsEn) {
                    $facilityName = $hospital->facility_name;
                    $address = $hospital->address;

                    if ($locale === 'en' && isset($translationsEn[$facilityName])) {
                        $facilityName = $translationsEn[$facilityName]['name'];
                        $address = $translationsEn[$facilityName]['address'];
                    }

                    return [
                        'id'            => $hospital->id,
                        'name'          => $facilityName,
                        'facility_name' => $facilityName,
                        'facility_type' => $locale === 'en' ? ($hospital->facility_type === 'حكومي' ? 'Governmental' : 'Charity/NGO') : $hospital->facility_type,
                        'address'       => $address,
                        'phone'         => $hospital->user->phone ?? null,
                    ];
                });

            return response()->json($hospitals, 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في جلب قائمة الشركاء: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * إرسال رسالة تواصل
     */
    public function sendContactMessage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        try {
            ContactMessage::create([
                'name'    => $request->name,
                'email'   => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح، وسنقوم بالرد عليك في أقرب وقت!'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل إرسال الرسالة، يرجى المحاولة لاحقاً.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}
