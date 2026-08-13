<?php

namespace App\Http\Controllers\Donor;

use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class NotificationsController extends Controller
{
    use ApiResponseTrait;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * جلب الإشعارات المباشرة للمستخدم ودمج طلبات الطوارئ الجديدة مع تجهيز بيانات الترجمة
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        $notifications = [];
        try {
            $notifications = $this->notificationService->getUserUnreadNotifications($userId);
        } catch (\Exception $e) {
            $notifications = [];
        }

        // جلب آخر طلبات طوارئ عاجلة كمصدر إشعارات إضافي لحظي وإرفاق البيانات الهيكلية للترجمة
        $urgentRequests = DB::table('blood_requests')
            ->leftJoin('hospitals', 'blood_requests.hospital_id', '=', 'hospitals.id')
            ->leftJoin('blood_types', 'blood_requests.blood_type_id', '=', 'blood_types.id')
            ->select(
                'blood_requests.id',
                'blood_requests.created_at',
                'blood_requests.emergency_level',
                'hospitals.facility_name',
                'hospitals.address',
                'blood_types.name as blood_type_name'
            )
            ->whereIn('blood_requests.status', ['open', 'active', 'searching', 'pending'])
            ->orderBy('blood_requests.created_at', 'desc')
            ->take(10)
            ->get()
            ->map(function ($req) {
                $hospitalName = $req->facility_name ?? 'مجمع الشفاء الطبي';
                $locationName = $req->address ?? 'غزة - الرمال';

                $bloodType = $req->blood_type_name ?? 'O+';
                if (is_string($bloodType) && str_starts_with(trim($bloodType), '{')) {
                    $decoded = json_decode($bloodType, true);
                    $bloodType = $decoded['name'] ?? 'O+';
                }

                $messageText = 'حالة طارئة عاجلة في ' . $hospitalName . ' بحاجة لفصيلة ' . $bloodType;

                return [
                    'id'            => 'req_' . $req->id,
                    'title'         => 'نداء طوارئ جديد!',
                    'message'       => $messageText,
                    'desc'          => $messageText,
                    'hospital_name' => $hospitalName,
                    'location'      => $locationName,
                    'blood_type'    => (string) $bloodType,
                    'severity'      => ucfirst($req->emergency_level ?? 'Critical'),
                    'time'          => $req->created_at ? \Carbon\Carbon::parse($req->created_at)->diffForHumans() : 'منذ قليل',
                    'created_at'    => $req->created_at,
                    'is_read'       => false,
                    'read'          => false
                ];
            })->toArray();

        $merged = array_merge(
            is_array($notifications) ? $notifications : (method_exists($notifications, 'toArray') ? $notifications->toArray() : []),
            $urgentRequests
        );

        return $this->successResponse($merged, 'تم جلب الإشعارات المباشرة');
    }

    /**
     * ⚡ دالة Polling فائقة الخفة لجرس الإشعارات
     */
    public function pollUnreadCount(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;

        $count = 0;
        try {
            $notifications = $this->notificationService->getUserUnreadNotifications($userId);
            $count = is_array($notifications) || $notifications instanceof \Countable ? count($notifications) : 0;
        } catch (\Exception $e) {
            $count = 0;
        }

        $urgentCount = BloodRequest::whereIn('status', ['open', 'active', 'searching', 'pending'])->count();
        $totalUnread = max($count, $urgentCount);

        return $this->successResponse([
            'unread_count' => $totalUnread,
            'timestamp'    => now()->toDateTimeString()
        ], 'تم جلب عداد الإشعارات المباشر');
    }

    /**
     * تحديد إشعار معين كمقروء
     */
    public function update(Request $request, $id)
    {
        try {
            $this->notificationService->markAsRead($id);
        } catch (\Exception $e) {}

        return $this->successResponse(null, 'تم تحديد الإشعار كمقروء');
    }

    /**
     * تحديد كافة الإشعارات كمقروءة للمستخدم الحالي
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->id;

        try {
            if (method_exists($this->notificationService, 'markAllAsReadForUser')) {
                $this->notificationService->markAllAsReadForUser($userId);
            } elseif (method_exists($this->notificationService, 'markAllAsRead')) {
                $this->notificationService->markAllAsRead($userId);
            } else {
                $request->user()->unreadNotifications->markAsRead();
            }
        } catch (\Exception $e) {}

        return $this->successResponse(null, 'تم تحديد جميع الإشعارات كمقروءة بنجاح');
    }
}
