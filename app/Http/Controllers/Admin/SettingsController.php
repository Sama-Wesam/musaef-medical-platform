<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
// use App\Models\Setting; // تأكدي من إنشاء نموذج Setting للتعامل مع قاعدة البيانات

class SettingsController extends Controller
{
    /**
     * عرض جميع إعدادات النظام الحالية.
     */
    public function index()
    {
        // جلب جميع الإعدادات من قاعدة البيانات
        // $settings = Setting::pluck('value', 'key'); // يعيد الإعدادات كـ Key-Value pair

        // بيانات وهمية مؤقتة (Mock Data) لتوضيح الهيكلة للواجهة الأمامية
        $settings = [
            'maintenance_mode' => false,
            'auto_registration' => true,
            'two_factor_auth' => false,
            'ai_prediction_enabled' => true,
            'smtp_host' => 'smtp.musaef.org',
            'smtp_port' => '587',
            'system_language' => 'ar',
        ];

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ], 200);
    }

    /**
     * تحديث إعدادات النظام (يستقبل مصفوفة من الإعدادات ويحدثها دفعة واحدة).
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array', // يجب أن تكون البيانات المرسلة عبارة عن مصفوفة إعدادات
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // تحديث الإعدادات في قاعدة البيانات
        /*
        foreach ($request->settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
        */

        return response()->json([
            'status' => 'success',
            'message' => 'تم حفظ التغييرات بنجاح.',
        ], 200);
    }

    /**
     * دالة إضافية ممتازة لجلب حالة النظام (System Health) للوحة الإعدادات المتقدمة.
     * تجلب بيانات استهلاك المعالج، الذاكرة، والتخزين (محاكاة).
     */
    public function getSystemHealth()
    {
        // في بيئة الإنتاج الفعلية، يتم استخدام أوامر الخادم لجلب هذه البيانات
        $healthStats = [
            'cpu_usage' => '34%',
            'memory_usage' => '62%', // 9.8 GB / 16 GB
            'storage_usage' => '45%', // 225 GB / 500 GB
            'response_time' => '120ms',
            'system_status' => 'healthy'
        ];

        return response()->json([
            'status' => 'success',
            'data' => $healthStats
        ], 200);
    }
}