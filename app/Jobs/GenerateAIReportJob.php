<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\AI\BloodDemandForecast;
use App\Models\Hospital;
use App\Models\BloodInventory;
use App\Models\BloodRequest;
use App\Models\Donation;
use Illuminate\Support\Facades\Log;

class GenerateAIReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * إعدادات إعادة المحاولة والمهلة الزمنية
     */
    public int $tries = 3;
    public array $backoff = [10, 30];
    public int $timeout = 120;

    /**
     * تنفيذ مهمة توليد تقارير التنبؤ بالطلب في الخلفية
     */
    public function handle(BloodDemandForecast $forecastEngine): void
    {
        // جلب المستشفيات الموثقة مع مخزونها وعلاقاتها في استعلام واحد لتجنب N+1 Query
        $hospitals = Hospital::query()
            ->where('is_verified', true)
            ->with(['inventories.bloodType'])
            ->get();

        foreach ($hospitals as $hospital) {
            foreach ($hospital->inventories as $inventory) {
                $bloodTypeId = $inventory->blood_type_id;

                // 1. حساب متوسط الاستهلاك الحقيقي لآخر 30 يوماً عبر علاقة المتبرعين والطلبات المرتبطة
                $last30DaysConsumption = Donation::where('hospital_id', $hospital->id)
                    ->whereHas('donor', function ($query) use ($bloodTypeId) {
                        $query->where('blood_type_id', $bloodTypeId);
                    })
                    ->where('created_at', '>=', now()->subDays(30))
                    ->count();

                $avgDailyConsumption = (int) max(1, ceil($last30DaysConsumption / 30));

                // 2. حساب عدد الطلبات المعلقة الحقيقية لنفس المستشفى والفصيلة
                $pendingRequestsCount = BloodRequest::where('hospital_id', $hospital->id)
                    ->where('blood_type_id', $bloodTypeId)
                    ->whereIn('status', ['pending', 'searching'])
                    ->count();

                // 3. التحقق الديناميكي من وجود حالة طوارئ نشطة حالياً
                $hasActiveEmergency = BloodRequest::where('hospital_id', $hospital->id)
                    ->where('blood_type_id', $bloodTypeId)
                    ->whereIn('status', ['pending', 'searching'])
                    ->where('emergency_level', 'critical')
                    ->exists();

                // 4. حساب الموسم الحالي ديناميكياً (1: شتاء، 2: ربيع، 3: صيف، 4: خريف)
                $currentSeason = (int) (floor((now()->month % 12) / 3) + 1);

                // استدعاء دالة التنبؤ
                $report = $forecastEngine->predictShortage(
                    $inventory->bloodType->name ?? 'A+',
                    $inventory->units_available,
                    $avgDailyConsumption,
                    $pendingRequestsCount,
                    $hasActiveEmergency,
                    $currentSeason
                );

                $reportStatus = $report['status'] ?? 'غير معروف';
                Log::info("AI Report generated for Hospital ID {$hospital->id} - Blood Type: {$inventory->bloodType->name} - Status: {$reportStatus}");
            }
        }
    }
}
