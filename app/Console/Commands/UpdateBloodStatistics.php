<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BloodInventory;
use App\Models\Notification;
use Carbon\Carbon;

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

        // جلب جميع سجلات المخزون مع علاقات المستشفى والفصيلة
        $inventories = BloodInventory::with(['hospital.user', 'bloodType'])->get();
        $alertCount = 0;

        foreach ($inventories as $inventory) {
            // الحد الأدنى الآمن للمخزون (يمكن جعله ديناميكياً لاحقاً)
            $criticalThreshold = 5;

            // إذا كان المخزون أقل من الحد الآمن، أرسل إشعاراً للمستشفى
            if ($inventory->units_available <= $criticalThreshold) {
                
                $hospitalUserId = $inventory->hospital->user_id;
                $bloodTypeName = $inventory->bloodType->name;

                Notification::create([
                    'user_id' => $hospitalUserId,
                    'title' => '⚠️ تحذير: نقص حاد في مخزون الدم',
                    'body' => "لقد انخفض مخزون فصيلة ({$bloodTypeName}) لديكم إلى {$inventory->units_available} وحدات فقط. يرجى إنشاء طلب توفير بأقرب وقت.",
                    'type' => 'system',
                    'is_read' => false,
                ]);

                $alertCount++;
            }
        }

        $this->info("تم الانتهاء من فحص المخزون. تم إرسال {$alertCount} إشعار تحذيري للمستشفيات.");
    }
}