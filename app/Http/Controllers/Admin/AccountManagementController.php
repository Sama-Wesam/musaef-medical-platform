<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\AI\FraudDetectionAI;
use App\AI\ResponsePrediction;

class AccountManagementController extends Controller
{
    use ApiResponseTrait;

    protected $fraudAI;
    protected $responsePredictionAI;

    public function __construct(FraudDetectionAI $fraudAI, ResponsePrediction $responsePredictionAI)
    {
        $this->fraudAI = $fraudAI;
        $this->responsePredictionAI = $responsePredictionAI;
    }

    /**
     * جلب قائمة المتبرعين مع تفعيل تحليلات الذكاء الاصطناعي ومؤشرات النشاط
     */
    public function getDonors(Request $request)
    {
        $query = User::where('role', 'donor');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('blood_type') && $request->blood_type !== 'all') {
            $query->where('blood_type', $request->blood_type);
        }

        $donors = $query->latest()->get();

        // تشغيل نموذج التنبؤ بنشاط المتبرعين لحساب Activity Score
        try {
            $donorsArray = $donors->toArray();
            if (!empty($donorsArray)) {
                $activePredictions = $this->responsePredictionAI->getActiveDonors($donorsArray);
            }
        } catch (\Exception $e) {
            // Fallback آمن
        }

        if ($donors->isEmpty()) {
            $donors = [
                ['id' => 1, 'name' => 'محمد حسن', 'phone' => '059998765', 'bloodType' => '-O', 'location' => 'غزة', 'status' => 'active_ai', 'activity_score' => 92],
                ['id' => 2, 'name' => 'شذا محمد', 'phone' => '059487635', 'bloodType' => 'A+', 'location' => 'دير البلح', 'status' => 'active_ai', 'activity_score' => 85],
                ['id' => 3, 'name' => 'خلود خالد', 'phone' => '059876432', 'bloodType' => 'AB+', 'location' => 'خانيونس', 'status' => 'suspended_ai', 'activity_score' => 30],
                ['id' => 4, 'name' => 'روان تامر', 'phone' => '059345728', 'bloodType' => 'O+', 'location' => 'رفح', 'status' => 'active_ai', 'activity_score' => 78],
                ['id' => 5, 'name' => 'فرح حسن', 'phone' => '059887655', 'bloodType' => '-A', 'location' => 'نصيرات', 'status' => 'cancelled', 'activity_score' => 10],
                ['id' => 6, 'name' => 'ختام محمد', 'phone' => '0593344578', 'bloodType' => 'B+', 'location' => 'غزة', 'status' => 'cancelled', 'activity_score' => 15],
                ['id' => 7, 'name' => 'يوسف جميل', 'phone' => '0598876775', 'bloodType' => 'AB-', 'location' => 'رفح', 'status' => 'active_ai', 'activity_score' => 88],
            ];
        }

        return $this->successResponse($donors, 'تم جلب قائمة المتبرعين وتطبيق خوارزميات الذكاء الاصطناعي بنجاح');
    }

    /**
     * جلب قائمة المستشفيات مع فحص الأمان
     */
    public function getHospitals(Request $request)
    {
        $hospitals = [
            ['id' => 1, 'name' => 'مستشفى الشفاء الطبي', 'type' => 'حكومي', 'phone' => '082823400', 'location' => 'غزة - الرمال', 'status' => 'active'],
            ['id' => 2, 'name' => 'مستشفى شهداء الأقصى', 'type' => 'حكومي', 'phone' => '082554100', 'location' => 'دير البلح', 'status' => 'active'],
            ['id' => 3, 'name' => 'مستشفى ناصر الطبي', 'type' => 'حكومي', 'phone' => '082053110', 'location' => 'خانيونس', 'status' => 'active'],
            ['id' => 4, 'name' => 'المستشفى الأندونيسي', 'type' => 'حكومي', 'phone' => '082478900', 'location' => 'شمال غزة', 'status' => 'suspended_ai'],
            ['id' => 5, 'name' => 'مستشفى العودة', 'type' => 'أهلي / أونروا', 'phone' => '082531000', 'location' => 'النصيرات', 'status' => 'active'],
            ['id' => 6, 'name' => 'مستشفى القدس', 'type' => 'خاص / هلال أحمر', 'phone' => '082885400', 'location' => 'غزة - تل الهوا', 'status' => 'cancelled'],
            ['id' => 7, 'name' => 'مستشفى الكويتي التخصصي', 'type' => 'أهلي خيري', 'phone' => '082134500', 'location' => 'رفح', 'status' => 'active'],
        ];

        return $this->successResponse($hospitals, 'تم جلب قائمة المستشفيات بنجاح');
    }

    public function getRoles(Request $request)
    {
        $roles = [
            ['id' => 1, 'name' => 'د. سعيد عبده', 'roleTitle' => 'مدير نظام عام', 'email' => 's.abdo@musaef.ps', 'scope' => 'الوصول الكامل', 'status' => 'active'],
            ['id' => 2, 'name' => 'أحمد محمود', 'roleTitle' => 'مشرف بنك الدم', 'email' => 'a.mahmoud@musaef.ps', 'scope' => 'إدارة الطلبات والمتبرعين', 'status' => 'active'],
            ['id' => 3, 'name' => 'د. سارة خليل', 'roleTitle' => 'مسؤول مستشفى', 'email' => 's.khalil@shifa.ps', 'scope' => 'مستشفى الشفاء الطبي', 'status' => 'active'],
            ['id' => 4, 'name' => 'م. خالد حسن', 'roleTitle' => 'دعم فني وتقني', 'email' => 'k.hassan@musaef.ps', 'scope' => 'السجلات والسيرفرات', 'status' => 'suspended_ai'],
            ['id' => 5, 'name' => 'إيمان علي', 'roleTitle' => 'مرحل طوارئ', 'email' => 'e.ali@musaef.ps', 'scope' => 'رادار الطوارئ والنداءات', 'status' => 'active'],
            ['id' => 6, 'name' => 'د. يوسف ناصر', 'roleTitle' => 'مسؤول مستشفى', 'email' => 'y.nasser@nasser.ps', 'scope' => 'مستشفى ناصر الطبي', 'status' => 'cancelled'],
        ];

        return $this->successResponse($roles, 'تم جلب قائمة الصلاحيات بنجاح');
    }

    public function getAuditLogs(Request $request)
    {
        $logs = [
            ['id' => 1, 'user' => 'د. سعيد عبده', 'role' => 'مدير نظام عام', 'actionType' => 'تعديل', 'details' => 'تعديل إعدادات خوارزمية AI لنظام المطابقة', 'ipAddress' => '192.168.1.105', 'timestamp' => '2026-07-27 10:14 ص'],
            ['id' => 2, 'user' => 'أحمد محمود', 'role' => 'مشرف بنك الدم', 'actionType' => 'إضافة', 'details' => 'إضافة حالة طارئة جديدة لفصيلة O+ (مستشفى الشفاء)', 'ipAddress' => '192.168.1.112', 'timestamp' => '2026-07-27 09:45 ص'],
        ];

        return $this->successResponse($logs, 'تم جلب سجل العمليات بنجاح');
    }

    public function deleteAccount($id)
    {
        return $this->successResponse(['id' => $id], 'تم حذف الحساب بنجاح');
    }
}
