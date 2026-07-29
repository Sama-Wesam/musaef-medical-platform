<?php

namespace App\Http\Controllers\API;

use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $notifications = $this->notificationService->getUserUnreadNotifications($request->user()->id);
        return $this->successResponse($notifications);
    }

    public function markAsRead(Request $request, $id)
    {
        $this->notificationService->markAsRead($id);
        return $this->successResponse(null, 'تم التحديد كمقروء');
    }

    // إضافة الدالة الناقصة لتحديد جميع إشعارات المستخدم كمقروءة
    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->id;

        // إذا لم توجد الدالة في الخدمة، يمكنك تحديثها مباشرة أو عبر الخدمة
        if (method_exists($this->notificationService, 'markAllAsRead')) {
            $this->notificationService->markAllAsRead($userId);
        } else {
            \App\Models\Notification::where('user_id', $userId)->update(['read_at' => now()]);
        }

        return $this->successResponse(null, 'تم تحديث جميع الإشعارات كمقروءة بنجاح');
    }
}
