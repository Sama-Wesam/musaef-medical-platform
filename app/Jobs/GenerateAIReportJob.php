<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\AI\BloodDemandForecast;
use App\Models\Hospital;
use App\Models\BloodInventory; // استيراد الموديل مباشرة في الأعلى
use Illuminate\Support\Facades\Log;

class GenerateAIReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * تنفيذ مهمة توليد تقارير التنبؤ بالطلب في الخلفية
     */
    public function handle(BloodDemandForecast $forecastEngine): void
    {
        $hospitals = Hospital::query()->where('is_verified', true)->get();

        foreach ($hospitals as $hospital) {

            $inventories = BloodInventory::query()
                ->where('hospital_id', '=', $hospital->id)
                ->with('bloodType')
                ->get();

            foreach ($inventories as $inventory) {

                // استدعاء الدالة بالمعاملات الستة الصحيحة والمطابقة للمحرك الذكي
                $report = $forecastEngine->predictShortage(
                    $inventory->bloodType->name,    // فصيلة الدم (string)
                    $inventory->units_available,   // المخزون الحالي (int)
                    15,                            // متوسط الاستهلاك اليومي الافتراضي (int)
                    2,                             // الطلبات المعلقة الحالية (int)
                    false,                         // هل توجد حالة طوارئ نشطة حالياً (bool)
                    1                              // الموسم الحالي: 1 للشتاء (int)
                );

                // تسجيل مخرجات الذكاء الاصطناعي بنجاح
                Log::info("AI Report for Hospital ID {$hospital->id}:");
                Log::info("Blood Type: " . $report['blood_type'] . " | Status: " . $report['status']);
                Log::info("Prediction Result: " . $report['message']);
            }
        }
    }
}
