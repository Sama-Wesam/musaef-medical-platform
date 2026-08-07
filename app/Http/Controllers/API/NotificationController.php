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
}