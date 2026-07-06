<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\AI\BloodDemandForecast;
use App\Models\Hospital;

class GenerateAIReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(BloodDemandForecast $forecastEngine): void
    {
        $hospitals = Hospital::where('is_verified', true)->get();

        foreach ($hospitals as $hospital) {
            // توليد توقع لاحتياج فصيلة O- (كمثال) للأسبوع القادم
            $report = $forecastEngine->forecastDemand($hospital->id, 1); // 1 = ID for O-

            // هنا يمكن حفظ التقرير في قاعدة البيانات أو إرساله للمستشفى
            \Log::info("AI Report for Hospital {$hospital->id}: Expected Demand = {$report['forecast_next_7_days']} units.");
        }
    }
}