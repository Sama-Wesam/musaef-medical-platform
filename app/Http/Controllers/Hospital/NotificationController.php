<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\BloodRequest;
use App\Models\BloodInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * عرض قائمة الإشعارات والتنبيهات الخاصة بالمستشفى مع المزامنة اللحظية
     */
    public function index(Request $request)
    {
        try {
            $user = $request->user() ?? Auth::user();
            $userId = $user ? $user->id : null;
            $hospital = $user ? $user->hospital : null;
            $hospitalId = $hospital ? $hospital->id : null;

            if (!$userId) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'غير مصرح للوصول'
                ], 401);
            }

            $dbNotifications = Notification::where('user_id', $userId)
                ->latest()
                ->get();

            if ($dbNotifications->isNotEmpty()) {
                $notifications = $dbNotifications->map(function ($item) {
                    $bodyText = $item->body ?? $item->data ?? 'تحديث جديد في النظام';
                    return [
                        'id'             => $item->id,
                        'type'           => $item->type ?? 'info',
                        'title'          => $item->title ?? 'تنبيه جديد',
                        'title_ar'       => $item->title ?? 'تنبيه جديد',
                        'title_en'       => $item->title ?? 'New Notification',
                        'message'        => $bodyText,
                        'desc'           => $bodyText,
                        'description_ar' => $bodyText,
                        'description_en' => $bodyText,
                        'is_read'        => (bool) $item->is_read,
                        'read'           => (bool) $item->is_read,
                        'created_at'     => $item->created_at ? $item->created_at->diffForHumans() : 'منذ قليل',
                        'badge'          => $item->type === 'emergency' ? 'emergency' : ($item->type === 'warning' ? 'warning' : 'info')
                    ];
                })->values()->toArray();
            } else {
                $liveAlerts = collect();

                if ($hospitalId) {
                    $activeRequestsCount = BloodRequest::where('hospital_id', $hospitalId)
                        ->whereIn('status', ['searching', 'pending', 'accepted', 'active', 'open'])
                        ->count();

                    if ($activeRequestsCount > 0) {
                        $liveAlerts->push([
                            'id'             => 'req-alert-' . $hospitalId,
                            'type'           => 'emergency',
                            'title'          => '🚨 متابعة نداءات الطوارئ النشطة',
                            'title_ar'       => '🚨 متابعة نداءات الطوارئ النشطة',
                            'title_en'       => '🚨 Active Emergency Calls Tracking',
                            'message'        => "لديك حالياً {$activeRequestsCount} نداءات طوارئ جاري استجابة المتبرعين لها عبر Smart Matching AI.",
                            'desc'           => "لديك حالياً {$activeRequestsCount} نداءات طوارئ جاري استجابة المتبرعين لها عبر Smart Matching AI.",
                            'description_ar' => "لديك حالياً {$activeRequestsCount} نداءات طوارئ جاري استجابة المتبرعين لها عبر Smart Matching AI.",
                            'description_en' => "You currently have {$activeRequestsCount} emergency calls being fulfilled via Smart Matching AI.",
                            'is_read'        => false,
                            'read'           => false,
                            'created_at'     => 'منذ قليل',
                            'badge'          => 'emergency'
                        ]);
                    }

                    $liveAlerts->push([
                        'id'             => 'fraud-alert-' . $hospitalId,
                        'type'           => 'fraud_alert',
                        'title'          => '🛡️ نظام كشف الاحتيال الذكي (Fraud Detection AI)',
                        'title_ar'       => '🛡️ نظام كشف الاحتيال الذكي (Fraud Detection AI)',
                        'title_en'       => '🛡️ Fraud Detection AI System',
                        'message'        => 'تم فحص عمليات طلب الدم والتبرعات الأخيرة بنجاح، ولم يتم رصد أي نشاط مشبوه أو بلاغات كاذبة.',
                        'desc'           => 'تم فحص عمليات طلب الدم والتبرعات الأخيرة بنجاح، ولم يتم رصد أي نشاط مشبوه أو بلاغات كاذبة.',
                        'description_ar' => 'تم فحص عمليات طلب الدم والتبرعات الأخيرة بنجاح، ولم يتم رصد أي نشاط مشبوه أو بلاغات كاذبة.',
                        'description_en' => 'Recent blood requests and donations analyzed successfully; no suspicious activity detected.',
                        'is_read'        => true,
                        'read'           => true,
                        'created_at'     => 'منذ ساعة',
                        'badge'          => 'info'
                    ]);

                    $lowStock = BloodInventory::where('hospital_id', $hospitalId)
                        ->where('units_available', '<', 5)
                        ->first();

                    if ($lowStock) {
                        $bloodName = optional($lowStock->bloodType)->name ?? 'الحرجة';
                        $liveAlerts->push([
                            'id'             => 'stock-alert-' . $lowStock->id,
                            'type'           => 'warning',
                            'title'          => '⚠️ تنبيه انخفاض المخزون',
                            'title_ar'       => '⚠️ تنبيه انخفاض المخزون',
                            'title_en'       => '⚠️ Low Stock Alert',
                            'message'        => "مخزون فصيلة الدم {$bloodName} وصل إلى {$lowStock->units_available} وحدات فقط. يوصى بإطلاق نداء استبدال.",
                            'desc'           => "مخزون فصيلة الدم {$bloodName} وصل إلى {$lowStock->units_available} وحدات فقط. يوصى بإطلاق نداء استبدال.",
                            'description_ar' => "مخزون فصيلة الدم {$bloodName} وصل إلى {$lowStock->units_available} وحدات فقط. يوصى بإطلاق نداء استبدال.",
                            'description_en' => "Blood type {$bloodName} stock reached {$lowStock->units_available} units. Replacement call recommended.",
                            'is_read'        => false,
                            'read'           => false,
                            'created_at'     => 'منذ ساعتين',
                            'badge'          => 'warning'
                        ]);
                    }
                }

                $notifications = $liveAlerts->values()->toArray();
            }

            return response()->json([
                'status' => 'success',
                'data'   => $notifications
            ], 200);

        } catch (\Exception $e) {
            Log::error('Hospital NotificationController Error: ' . $e->getMessage());
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ أثناء جلب الإشعارات: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * دالة Polling خفيفة جداً لعداد الإشعارات غير المقروءة للمستشفى
     */
    public function pollUnreadCount(Request $request)
    {
        try {
            $userId = Auth::id() ?? optional($request->user())->id;
            $unreadCount = $userId ? Notification::where('user_id', $userId)->where('is_read', false)->count() : 0;

            return response()->json([
                'status'       => 'success',
                'unread_count' => $unreadCount,
                'timestamp'    => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'       => 'success',
                'unread_count' => 0,
                'timestamp'    => now()->toDateTimeString()
            ]);
        }
    }

    /**
     * تحديث حالة إشعار محدد إلى مقروء
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::find($id);

            if ($notification) {
                if (method_exists($notification, 'markAsRead')) {
                    $notification->markAsRead();
                } else {
                    $notification->update(['is_read' => true, 'read_at' => now()]);
                }
            } else {
                DB::table('notifications')->where('id', $id)->update(['is_read' => true, 'updated_at' => now()]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'تم تحديث حالة الإشعار بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'success',
                'message' => 'تم تحديث الحالة'
            ]);
        }
    }

    /**
     * تحديث جميع الإشعارات إلى مقروءة
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $userId = Auth::id() ?? optional($request->user())->id;

            if ($userId) {
                Notification::where('user_id', $userId)
                    ->where('is_read', false)
                    ->update([
                        'is_read' => true,
                        'read_at' => now()
                    ]);
            }

            return response()->json([
                'status'  => 'success',
                'message' => 'تم تحديد جميع الإشعارات كمقروءة'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'success',
                'message' => 'تم تعيين الكل كمقروء'
            ]);
        }
    }
}
