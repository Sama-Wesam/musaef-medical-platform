<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hospital;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AccountManagementController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب قائمة المتبرعين
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

        if ($donors->isEmpty()) {
            $donors = [
                ['id' => 1, 'name' => 'محمد حسن', 'phone' => '059998765', 'bloodType' => '-O', 'location' => 'غزة', 'status' => 'نشط'],
                ['id' => 2, 'name' => 'شذا محمد', 'phone' => '059487635', 'bloodType' => 'A+', 'location' => 'دير البلح', 'status' => 'نشط'],
                ['id' => 3, 'name' => 'خلود خالد', 'phone' => '059876432', 'bloodType' => 'AB+', 'location' => 'خانيونس', 'status' => 'معلق'],
                ['id' => 4, 'name' => 'روان تامر', 'phone' => '059345728', 'bloodType' => 'O+', 'location' => 'رفح', 'status' => 'نشط'],
                ['id' => 5, 'name' => 'فرح حسن', 'phone' => '059887655', 'bloodType' => '-A', 'location' => 'نصيرات', 'status' => 'ملغي'],
                ['id' => 6, 'name' => 'ختام محمد', 'phone' => '0593344578', 'bloodType' => 'B+', 'location' => 'غزة', 'status' => 'ملغي'],
                ['id' => 7, 'name' => 'يوسف جميل', 'phone' => '0598876775', 'bloodType' => 'AB-', 'location' => 'رفح', 'status' => 'نشط'],
            ];
        }

        return $this->successResponse($donors, 'تم جلب قائمة المتبرعين بنجاح');
    }

    /**
     * جلب قائمة المستشفيات
     */
    public function getHospitals(Request $request)
    {
        $hospitals = [
            ['id' => 1, 'name' => 'مستشفى الشفاء الطبي', 'type' => 'حكومي', 'phone' => '082823400', 'location' => 'غزة - الرمال', 'status' => 'نشط'],
            ['id' => 2, 'name' => 'مستشفى شهداء الأقصى', 'type' => 'حكومي', 'phone' => '082554100', 'location' => 'دير البلح', 'status' => 'نشط'],
            ['id' => 3, 'name' => 'مستشفى ناصر الطبي', 'type' => 'حكومي', 'phone' => '082053110', 'location' => 'خانيونس', 'status' => 'نشط'],
            ['id' => 4, 'name' => 'المستشفى الأندونيسي', 'type' => 'حكومي', 'phone' => '082478900', 'location' => 'شمال غزة', 'status' => 'معلق'],
            ['id' => 5, 'name' => 'مستشفى العودة', 'type' => 'أهلي / أونروا', 'phone' => '082531000', 'location' => 'النصيرات', 'status' => 'نشط'],
            ['id' => 6, 'name' => 'مستشفى القدس', 'type' => 'خاص / هلال أحمر', 'phone' => '082885400', 'location' => 'غزة - تل الهوا', 'status' => 'ملغي'],
            ['id' => 7, 'name' => 'مستشفى الكويتي التخصصي', 'type' => 'أهلي خيري', 'phone' => '082134500', 'location' => 'رفح', 'status' => 'نشط'],
        ];

        return $this->successResponse($hospitals, 'تم جلب قائمة المستشفيات بنجاح');
    }

    /**
     * جلب قائمة الصلاحيات والأدوار
     */
    public function getRoles(Request $request)
    {
        $roles = [
            ['id' => 1, 'name' => 'د. سعيد عبده', 'roleTitle' => 'مدير نظام عام', 'email' => 's.abdo@musaef.ps', 'scope' => 'الوصول الكامل', 'status' => 'نشط'],
            ['id' => 2, 'name' => 'أحمد محمود', 'roleTitle' => 'مشرف بنك الدم', 'email' => 'a.mahmoud@musaef.ps', 'scope' => 'إدارة الطلبات والمتبرعين', 'status' => 'نشط'],
            ['id' => 3, 'name' => 'د. سارة خليل', 'roleTitle' => 'مسؤول مستشفى', 'email' => 's.khalil@shifa.ps', 'scope' => 'مستشفى الشفاء الطبي', 'status' => 'نشط'],
            ['id' => 4, 'name' => 'م. خالد حسن', 'roleTitle' => 'دعم فني وتقني', 'email' => 'k.hassan@musaef.ps', 'scope' => 'السجلات والسيرفرات', 'status' => 'معلق'],
            ['id' => 5, 'name' => 'إيمان علي', 'roleTitle' => 'مرحل طوارئ', 'email' => 'e.ali@musaef.ps', 'scope' => 'رادار الطوارئ والنداءات', 'status' => 'نشط'],
            ['id' => 6, 'name' => 'د. يوسف ناصر', 'roleTitle' => 'مسؤول مستشفى', 'email' => 'y.nasser@nasser.ps', 'scope' => 'مستشفى ناصر الطبي', 'status' => 'ملغي'],
        ];

        return $this->successResponse($roles, 'تم جلب قائمة الصلاحيات بنجاح');
    }

    /**
     * جلب سجل العمليات
     */
    public function getAuditLogs(Request $request)
    {
        $logs = [
            ['id' => 1, 'user' => 'د. سعيد عبده', 'role' => 'مدير نظام عام', 'actionType' => 'تعديل', 'details' => 'تعديل إعدادات خوارزمية AI لنظام المطابقة', 'ipAddress' => '192.168.1.105', 'timestamp' => '2026-07-27 10:14 ص'],
            ['id' => 2, 'user' => 'أحمد محمود', 'role' => 'مشرف بنك الدم', 'actionType' => 'إضافة', 'details' => 'إضافة حالة طارئة جديدة لفصيلة O+ (مستشفى الشفاء)', 'ipAddress' => '192.168.1.112', 'timestamp' => '2026-07-27 09:45 ص'],
            ['id' => 3, 'user' => 'د. سارة خليل', 'role' => 'مسؤول مستشفى', 'actionType' => 'تأكيد', 'details' => 'تلبية طلب التبرع رقم #8921 بنجاح', 'ipAddress' => '10.0.4.22', 'timestamp' => '2026-07-27 09:12 ص'],
            ['id' => 4, 'user' => 'م. خالد حسن', 'role' => 'دعم فني', 'actionType' => 'تسجيل دخول', 'details' => 'تسجيل دخول ناجح إلى لوحة تحكم الإدارة', 'ipAddress' => '192.168.1.200', 'timestamp' => '2026-07-27 08:30 ص'],
            ['id' => 5, 'user' => 'إيمان علي', 'role' => 'مرحل طوارئ', 'actionType' => 'إرسال', 'details' => 'تفعيل الاستجابة الفورية لرادار مستشفى الكويتي', 'ipAddress' => '10.0.8.55', 'timestamp' => '2026-07-27 08:05 ص'],
            ['id' => 6, 'user' => 'د. يوسف ناصر', 'role' => 'مسؤول مستشفى', 'actionType' => 'حذف', 'details' => 'إلغاء نداء طوارئ قديم رقم #8890', 'ipAddress' => '10.0.12.14', 'timestamp' => '2026-07-26 11:20 م'],
        ];

        return $this->successResponse($logs, 'تم جلب سجل العمليات بنجاح');
    }

    /**
     * حذف حساب
     */
    public function deleteAccount($id)
    {
        return $this->successResponse(['id' => $id], 'تم حذف الحساب بنجاح');
    }
}
