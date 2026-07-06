<?php

namespace App\AI;

use App\Models\Donor;

class ResponsePrediction
{
    /**
     * حساب احتمالية استجابة المتبرع (بين 0 و 100%)
     */
    public function predictProbability(int $donorId): float
    {
        $donor = Donor::withCount(['responses', 'donations'])->find($donorId);
        
        if (!$donor) return 0.0;

        $totalNotified = $donor->responses_count;
        $totalAccepted = $donor->donations_count;

        // 1. نسبة القبول التاريخية
        $historicalProbability = $totalNotified > 0 ? ($totalAccepted / $totalNotified) * 100 : 50.0; // 50% افتراضي للمستخدم الجديد

        // 2. تعديل النسبة بناءً على وقت اليوم (Time of Day Modifier)
        $currentHour = (int) now()->format('H');
        $timeModifier = 1.0;

        if ($currentHour >= 1 && $currentHour <= 6) {
            // احتمالية الاستجابة ضعيفة الفجر
            $timeModifier = 0.4;
        } elseif ($currentHour >= 18 && $currentHour <= 22) {
            // احتمالية الاستجابة عالية في المساء
            $timeModifier = 1.2;
        }

        $finalProbability = $historicalProbability * $timeModifier;

        return min(99.9, round($finalProbability, 2));
    }
}