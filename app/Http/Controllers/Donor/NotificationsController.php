<?php

namespace App\Http\Controllers\Donor;

use App\Services\NotificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NotificationsController extends Controller
{
    use ApiResponseTrait;

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * جلب الإشعارات غير المقروءة
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $notifications = $this->notificationService->getUserUnreadNotifications($userId);

        return $this->successResponse($notifications, 'تم جلب الإشعارات');
    }

    /**
     * تحديد إشعار معين كمقروء
     */
    public function update(Request $request, $id)
    {
        $this->notificationService->markAsRead($id);
        return $this->successResponse(null, 'تم تحديد الإشعار كمقروء');
    }

    /**
     * تحديد كافة الإشعارات كمقروءة للمستخدم الحالي
     */
    public function markAllAsRead(Request $request)
    {
        $userId = $request->user()->id;

        if (method_exists($this->notificationService, 'markAllAsReadForUser')) {
            $this->notificationService->markAllAsReadForUser($userId);
        } elseif (method_exists($this->notificationService, 'markAllAsRead')) {
            $this->notificationService->markAllAsRead($userId);
        } else {
            // تحديث الإشعارات عبر العلاقة المباشرة للمستخدم كخيار احتياطي
            $request->user()->unreadNotifications->markAsRead();
        }

        return $this->successResponse(null, 'تم تحديد جميع الإشعارات كمقروءة بنجاح');
    }
}
