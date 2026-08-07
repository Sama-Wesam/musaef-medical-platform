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
}