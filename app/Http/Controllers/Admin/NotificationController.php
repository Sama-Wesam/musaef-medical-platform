<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        $user = $request->user();
        $formattedNotifications = [];

        if ($user && method_exists($user, 'notifications')) {
            $notifications = $user->notifications()->take(20)->get();

            $formattedNotifications = $notifications->map(function ($notif) {
                $data = $notif->data ?? [];
                return [
                    'id'         => $notif->id,
                    'titleAr'    => $data['title_ar'] ?? $data['title'] ?? 'تنبيه النظام الذكي',
                    'titleEn'    => $data['title_en'] ?? $data['title'] ?? 'AI System Alert',
                    'messageAr'  => $data['message_ar'] ?? $data['message'] ?? $data['desc'] ?? 'تحديث جديد في لوحة التحكم',
                    'messageEn'  => $data['message_en'] ?? $data['message'] ?? $data['desc'] ?? 'New system update available',
                    'created_at' => $notif->created_at ? $notif->created_at->diffForHumans() : 'الآن',
                    'is_read'    => !is_null($notif->read_at),
                ];
            });
        }

        return $this->successResponse($formattedNotifications, 'تم جلب الإشعارات بنجاح');
    }

    /**
     * ⚡ دالة Polling سريعة لعداد الإشعارات غير المقروءة لمدير النظام
     */
    public function pollUnreadCount(Request $request)
    {
        $user = $request->user();
        $count = 0;

        if ($user && method_exists($user, 'unreadNotifications')) {
            $count = $user->unreadNotifications()->count();
        }

        return response()->json([
            'status'       => 'success',
            'unread_count' => $count,
            'timestamp'    => now()->toDateTimeString()
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();

        if ($user && method_exists($user, 'unreadNotifications')) {
            $user->unreadNotifications->markAsRead();
        }

        return $this->successResponse(null, 'تم تحديث حالة الإشعارات إلى مقروءة');
    }
}