<?php

namespace App\Http\Controllers\API;

use App\Services\QRCardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BloodRequest;

class DonorController extends Controller
{
    use ApiResponseTrait;

    protected $qrCardService;

    public function __construct(QRCardService $qrCardService)
    {
        $this->qrCardService = $qrCardService;
    }

    public function profile(Request $request)
    {
        $donor = $request->user()->donor ?? null;

        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير مكتملة');
        }

        // جلب العلاقات دفعة واحدة بكفاءة
        $donor->load(['bloodType', 'healthInfo', 'user']);

        return $this->successResponse($donor, 'تم جلب الملف الشخصي للمتبرع بنجاح');
    }

    public function qrCard(Request $request)
    {
        $donorId = $request->user()->donor->id ?? null;
        if (!$donorId) {
            return $this->notFoundResponse('لم يتم العثور على معرّف المتبرع');
        }

        $cardData = $this->qrCardService->generateDonorCard($donorId);
        return $this->successResponse($cardData, 'تم جلب بيانات البطاقة الذكية');
    }

    public function homeStats(Request $request)
    {
        // استخدام count المباشر بدون جلب السجلات للذاكرة
        $nearbyCount = BloodRequest::where('status', 'pending')->count();

        // تحديد الحقول المطلوبة فقط لتخفيف الحمل من الداتا بيز
        $suggestedRequests = BloodRequest::select(['id', 'hospital_id', 'blood_type_id', 'status', 'created_at'])
            ->with([
                'hospital:id,facility_name,address',
                'bloodType:id,name'
            ])
            ->where('status', 'pending')
            ->latest()
            ->take(3)
            ->get();

        $data = [
            'donations_count' => 8,
            'points' => 230,
            'badges_count' => 3,
            'days_until_next_donation' => 45,
            'is_eligible' => true,
            'last_donation_text' => 'آخر تبرع منذ 45 يوم',
            'level' => 'متقدم',
            'nearby_requests_count' => $nearbyCount,
            'notifications' => [],
            'suggested_requests' => $suggestedRequests
        ];

        return $this->successResponse($data, 'تم جلب إحصائيات الصفحة الرئيسية بنجاح');
    }

    public function urgentRequests(Request $request)
    {
        $urgentRequests = BloodRequest::select(['id', 'hospital_id', 'blood_type_id', 'emergency_level', 'status', 'created_at'])
            ->with([
                'hospital:id,facility_name,address',
                'bloodType:id,name'
            ])
            ->whereIn('emergency_level', ['high', 'critical'])
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return $this->successResponse($urgentRequests, 'تم جلب الحالات العاجلة بنجاح');
    }

    public function rewardsAndCard(Request $request)
    {
        $donor = $request->user()->donor ?? null;

        $data = [
            'donor_code' => 'BD' . ($donor->id ?? 123456789),
            'level' => 'متبرع متقدم',
            'location' => 'غزة - فلسطين',
            'status_text' => 'متبرع نشط',
            'units_donated' => 8,
            'cases_supported' => 12,
            'points' => 350,
            'points_progress' => 70,
            'points_needed' => 150,
            'target_points' => 500,
            'badges' => [
                ['id' => 1, 'title' => 'منقذ حياة', 'desc' => 'تم إنقاذ أكثر من 10 حالات', 'date' => '1 يونيو 2024', 'image' => 'badge-hero.png'],
                ['id' => 2, 'title' => '10 تبرعات', 'desc' => 'تم إنجاز 10 تبرعات', 'date' => '20 مايو 2025', 'image' => 'badge-10.png'],
                ['id' => 3, 'title' => '5 تبرعات', 'desc' => 'تم إنجاز 5 تبرعات', 'date' => '10 أبريل 2024', 'image' => 'badge-5.png'],
                ['id' => 4, 'title' => 'أول تبرع', 'desc' => 'تم إنجاز أول تبرع', 'date' => '15 مارس 2024', 'image' => 'badge-1.png']
            ]
        ];

        return $this->successResponse($data, 'تم جلب المكافآت والبطاقة الذكية بنجاح');
    }

    public function donationHistory(Request $request)
    {
        $history = [
            [
                'date' => '10 مايو 2024',
                'hospital_name' => 'مجمع الشفاء الطبي - مدينة غزة',
                'blood_type' => '+A',
                'units' => 2,
                'status' => 'مكتمل',
                'points_earned' => '+100'
            ],
            [
                'date' => '15 يناير 2024',
                'hospital_name' => 'مستشفى شهداء الاقصى - دير البلح',
                'blood_type' => '+B',
                'units' => 1,
                'status' => 'مكتمل',
                'points_earned' => '+90'
            ]
        ];

        return $this->successResponse($history, 'تم جلب سجل التبرعات بنجاح');
    }

    public function notifications(Request $request)
    {
        $notifications = [
            [
                'id' => 1,
                'title' => 'حالة طارئة عاجلة - مستشفى الشفاء',
                'message' => 'مطلوب تبرع عاجل بفصيلة الدم O+ بشكل فورى.',
                'time' => 'منذ 10 دقائق',
                'read' => false
            ],
            [
                'id' => 2,
                'title' => 'تذكير موعد التبرع القادم',
                'message' => 'بقيت 5 أيام على اكتمال فترة التعافي لتتمكن من التبرع مجدداً.',
                'time' => 'منذ ساعتين',
                'read' => false
            ],
            [
                'id' => 3,
                'title' => 'تم استبدال النقاط بنجاح',
                'message' => 'حصلت على مكافأة شارة منقذ حياة!',
                'time' => 'أمس',
                'read' => true
            ]
        ];

        return $this->successResponse([
            'notifications' => $notifications,
            'unread_count' => collect($notifications)->where('read', false)->count()
        ], 'تم جلب الإشعارات بنجاح');
    }

    public function markNotificationsAsRead(Request $request)
    {
        return $this->successResponse(null, 'تم تحديث جميع الإشعارات كمقروءة بنجاح');
    }
}
