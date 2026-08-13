<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BloodRequest;

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
    protected $description = 'إلغاء طلبات الطوارئ التي انتهت مدة صلاحيتها ولم يتم تلبيتها عبر استعلامات مباشرة لتحسين الأداء.';

    /**
     * تنفيذ الأمر
     */
    public function handle()
    {
        $this->info('Starting to clean expired emergencies...');

        // إلغاء الحالات الحادة/الحرجة المنتهية بعد 24 ساعة بمختلف المسميات[cite: 19]
        $critical = BloodRequest::whereIn('status', ['pending', 'searching'])
            ->whereIn('emergency_level', ['critical', 'حرج', 'حادة', 'Critical', 'high_priority'])
            ->where('created_at', '<=', now()->subHours(24))
            ->update(['status' => 'cancelled']);

        // إلغاء الحالات العالية المنتهية بعد 48 ساعة بمختلف المسميات[cite: 19]
        $high = BloodRequest::whereIn('status', ['pending', 'searching'])
            ->whereIn('emergency_level', ['high', 'مرتفع', 'عالي', 'High'])
            ->where('created_at', '<=', now()->subHours(48))
            ->update(['status' => 'cancelled']);

        // إلغاء الحالات العادية المنتهية بعد 72 ساعة بمختلف المسميات[cite: 19]
        $normal = BloodRequest::whereIn('status', ['pending', 'searching'])
            ->whereIn('emergency_level', ['normal', 'عادي', 'متوسط', 'Medium', 'low', 'Low'])
            ->where('created_at', '<=', now()->subHours(72))
            ->update(['status' => 'cancelled']);

        $totalExpired = $critical + $high + $normal;

        $this->info("تم بنجاح تنظيف وإلغاء {$totalExpired} طلب طوارئ منتهي الصلاحية مباشرة.");
        return 0;
    }
}
