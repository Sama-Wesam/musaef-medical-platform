<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BloodRequest;
use Carbon\Carbon;

class CleanExpiredEmergencies extends Command
{
    /**
     * اسم الأمر الذي سيُكتب في التيرمنال
     * @var string
     */
    protected $signature = 'emergencies:clean';

    /**
     * وصف الأمر
     * @var string
     */
    protected $description = 'إلغاء طلبات الطوارئ التي انتهت مدة صلاحيتها ولم يتم تلبيتها.';

    /**
     * تنفيذ الأمر
     */
    public function handle()
    {
        $this->info('Starting to clean expired emergencies...');
        
        $expiredCount = 0;
        
        // جلب الطلبات المعلقة أو التي لا زالت قيد البحث
        $requests = BloodRequest::whereIn('status', ['pending', 'searching'])->get();

        foreach ($requests as $request) {
            $hoursPassed = $request->created_at->diffInHours(Carbon::now());
            $shouldCancel = false;

            // تحديد مدة انتهاء الصلاحية بناءً على مستوى الطوارئ
            if ($request->emergency_level === 'critical' && $hoursPassed > 24) {
                $shouldCancel = true; // الحالات الحرجة تلغى أو تحدث بعد 24 ساعة
            } elseif ($request->emergency_level === 'high' && $hoursPassed > 48) {
                $shouldCancel = true;
            } elseif ($request->emergency_level === 'normal' && $hoursPassed > 72) {
                $shouldCancel = true;
            }

            if ($shouldCancel) {
                $request->update(['status' => 'cancelled']);
                $expiredCount++;
            }
        }

        $this->info("تم بنجاح تنظيف وإلغاء {$expiredCount} طلب طوارئ منتهي الصلاحية.");
    }
}