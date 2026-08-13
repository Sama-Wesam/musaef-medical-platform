<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BloodInventory;
use App\Models\Notification;
use App\Services\FCMService;
use Illuminate\Support\Facades\Log;

class UpdateBloodStatistics extends Command
{
    /**
     * @var string
     */
    protected $signature = 'statistics:update-blood';

    /**
     * @var string
     */
    protected $description = 'فحص مخزون الدم وتحديث الإحصائيات وإرسال تحذيرات النقص للمستشفيات.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking blood inventory levels...');

        $criticalThreshold = 5;

        // تصفية السجلات التي تعاني من النقص فقط من قاعدة البيانات مباشرة
        $inventories = BloodInventory::where('units_available', '<=', $criticalThreshold)
            ->with(['hospital.user', 'bloodType'])
            ->get();

        $alertCount = 0;

        foreach ($inventories as $inventory) {
            // جلب ID المستخدم الحقيقي المرتبط بالجهة الطبية بأمان لمنع استثناءات Null[cite: 21]
            $hospitalUserId = optional($inventory->hospital)->user_id
                ?? optional($inventory->hospital)->id
                ?? $inventory->hospital_id;

            if (!$hospitalUserId) {
                continue;
            }

            $bloodTypeName = optional($inventory->bloodType)->name ?? 'غير معروف';
            $title = '⚠️ تحذير: نقص حاد في مخزون الدم';
            $body = "لقد انخفض مخزون فصيلة ({$bloodTypeName}) لديكم إلى {$inventory->units_available} وحدات فقط. يرجى إنشاء طلب توفير بأقرب وقت.";

            // منع الإشعارات المكررة: الفحص إذا تم إرسال إشعار مشابه لنفس المستشفى خلال آخر 12 ساعة[cite: 21]
            $recentNotificationExists = Notification::where('user_id', $hospitalUserId)
                ->where('type', 'system')
                ->where('title', $title)
                ->where('created_at', '>=', now()->subHours(12))
                ->exists();

            if ($recentNotificationExists) {
                continue;
            }

            try {
                // 1. تسجيل الإشعار في قاعدة البيانات[cite: 21]
                Notification::create([
                    'user_id' => $hospitalUserId,
                    'title'   => $title,
                    'body'    => $body,
                    'type'    => 'system',
                    'is_read' => false,
                ]);

                // 2. إرسال Push Notification حقيقي عبر Firebase (FCM)[cite: 21]
                if (class_exists(FCMService::class)) {
                    app(FCMService::class)->sendToUser($hospitalUserId, $title, $body);
                }

                $alertCount++;
            } catch (\Throwable $e) {
                Log::error("FCM Send Error in UpdateBloodStatistics for User ID {$hospitalUserId}: {$e->getMessage()}");
            }
        }

        $this->info("تم الانتهاء من فحص المخزون. تم إرسال {$alertCount} إشعار تحذيري للمستشفيات.");
        return 0;
    }
}
