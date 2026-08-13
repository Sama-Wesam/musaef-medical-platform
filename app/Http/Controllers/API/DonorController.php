<?php

namespace App\Http\Controllers\API;

use App\Services\QRCardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\BloodRequest;
use App\Models\Donation;
use App\Models\Notification;

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
        $user = $request->user();
        $donor = $user->donor ?? null;

        $donationsCount = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'successful')->count() : 0;
        $points = $donor->points ?? ($donationsCount * 50);

        $nearbyCount = BloodRequest::where('status', 'active')->count();

        $suggestedRequests = BloodRequest::select(['id', 'hospital_id', 'blood_type_id', 'status', 'urgency_level', 'created_at'])
            ->with([
                'hospital:id,facility_name,address',
                'bloodType:id,name'
            ])
            ->where('status', 'active')
            ->latest()
            ->take(3)
            ->get();

        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->get();

        $data = [
            'donations_count'          => $donationsCount,
            'points'                   => $points,
            'badges_count'             => floor($donationsCount / 2),
            'days_until_next_donation' => $donor->days_until_next_donation ?? 0,
            'is_eligible'              => $donor->is_eligible ?? true,
            'last_donation_text'       => $donationsCount > 0 ? 'مسجل في النظام' : 'لم تقم بتم تقديم تبرعات بعد',
            'level'                    => $points > 200 ? 'متبرع ذهبي' : 'متبرع نشط',
            'nearby_requests_count'    => $nearbyCount,
            'notifications'            => $unreadNotifications,
            'suggested_requests'      => $suggestedRequests
        ];

        return $this->successResponse($data, 'تم جلب إحصائيات الصفحة الرئيسية بنجاح');
    }

    public function urgentRequests(Request $request)
    {
        $urgentRequests = BloodRequest::select(['id', 'hospital_id', 'blood_type_id', 'urgency_level', 'status', 'created_at'])
            ->with([
                'hospital:id,facility_name,address',
                'bloodType:id,name'
            ])
            ->whereIn('urgency_level', ['high', 'critical', 'urgent'])
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return $this->successResponse($urgentRequests, 'تم جلب الحالات العاجلة بنجاح');
    }

    public function rewardsAndCard(Request $request)
    {
        $user = $request->user();
        $donor = $user->donor ?? null;

        $donationsCount = $donor ? Donation::where('donor_id', $donor->id)->where('status', 'successful')->count() : 0;
        $points = $donor->points ?? ($donationsCount * 50);

        $data = [
            'donor_code'     => 'BD' . ($donor->id ?? $user->id),
            'level'          => $points >= 300 ? 'متبرع الماسي' : 'متبرع نشط',
            'location'       => $donor->address ?? 'غزة - فلسطين',
            'status_text'    => 'متبرع نشط',
            'units_donated'  => $donationsCount,
            'cases_supported'=> $donationsCount,
            'points'         => $points,
            'points_progress'=> min(100, floor(($points / 500) * 100)),
            'points_needed'  => max(0, 500 - $points),
            'target_points'  => 500,
            'badges'         => [
                ['id' => 1, 'title' => 'منقذ حياة', 'desc' => 'تجاوز التبرعات الفعالة', 'date' => now()->format('Y-m-d'), 'image' => 'badge-hero.png'],
            ]
        ];

        return $this->successResponse($data, 'تم جلب المكافآت والبطاقة الذكية بنجاح');
    }

    public function donationHistory(Request $request)
    {
        $donor = $request->user()->donor ?? null;

        if (!$donor) {
            return $this->successResponse([], 'لا يوجد سجل تبرعات');
        }

        $donations = Donation::with('hospital')
            ->where('donor_id', $donor->id)
            ->latest()
            ->get()
            ->map(function($d) {
                return [
                    'date'          => $d->created_at->format('Y-m-d'),
                    'hospital_name' => $d->hospital->facility_name ?? 'مستشفى مسعف',
                    'blood_type'    => $d->blood_type ?? 'غير محدد',
                    'units'         => $d->units_donated ?? 1,
                    'status'        => $d->status == 'successful' ? 'مكتمل' : 'قيد المعالجة',
                    'points_earned' => '+50'
                ];
            });

        return $this->successResponse($donations, 'تم جلب سجل التبرعات بنجاح');
    }

    public function notifications(Request $request)
    {
        $userId = $request->user()->id;

        $notifications = Notification::where('user_id', $userId)
            ->latest()
            ->take(15)
            ->get()
            ->map(function($n) {
                return [
                    'id'      => $n->id,
                    'title'   => $n->title ?? 'تنبيه طوارئ',
                    'message' => $n->message ?? $n->content,
                    'time'    => $n->created_at->diffForHumans(),
                    'read'    => (bool) $n->is_read
                ];
            });

        return $this->successResponse([
            'notifications' => $notifications,
            'unread_count'  => Notification::where('user_id', $userId)->where('is_read', false)->count()
        ], 'تم جلب الإشعارات بنجاح');
    }

    public function markNotificationsAsRead(Request $request)
    {
        Notification::where('user_id', $request->user()->id)->update(['is_read' => true]);
        return $this->successResponse(null, 'تم تحديث جميع الإشعارات كمقروءة بنجاح');
    }
}
