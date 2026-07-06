<?php

namespace App\AI;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\MatchingResult;

class SmartMatchingEngine
{
    /**
     * تشغيل خوارزمية المطابقة لطلب طوارئ معين
     */
    public function runMatching(BloodRequest $request, int $limit = 10)
    {
        $hospital = $request->hospital;
        
        // 1. جلب المتبرعين المتاحين والمؤهلين طبياً والذين تتوافق فصيلتهم
        // (تجاهلنا هنا تعقيد توافق الفصائل وافترضنا نفس الفصيلة للتسهيل)
        $donors = Donor::where('is_available', true)
            ->where('blood_type_id', $request->blood_type_id)
            ->whereHas('healthInfo', function ($query) {
                $query->where('is_eligible', true);
            })
            ->get();

        $matchingScores = [];

        foreach ($donors as $donor) {
            // التحقق من فترة الحظر الطبي (56 يوم)
            if ($donor->last_donation_date && $donor->last_donation_date->diffInDays(now()) < 56) {
                continue;
            }

            // 2. حساب المسافة باستخدام خوارزمية (Haversine Formula)
            $distanceKm = $this->calculateDistance(
                $hospital->latitude, $hospital->longitude,
                $donor->latitude, $donor->longitude
            );

            // 3. حساب وقت الوصول المتوقع (بافتراض متوسط سرعة 40 كم/س في المدينة)
            $etaMinutes = ceil(($distanceKm / 40) * 60);

            // 4. حساب سكور المطابقة (Match Score من 100)
            // المعايير: القرب الجغرافي 50%، سرعة الاستجابة السابقة للمتبرع 30%، وقت الوصول 20%
            $score = 100;
            
            // خصم نقاط بناءً على المسافة (كل كم يخصم نقطتين)
            $score -= ($distanceKm * 2);
            
            // إضافة نقاط إذا كان المتبرع لديه سجل استجابة ممتاز
            $successfulDonations = $donor->donations()->where('status', 'successful')->count();
            $score += ($successfulDonations * 3); // 3 نقاط لكل تبرع سابق ناجح

            // تقييد السكور بين 0 و 100
            $finalScore = max(10, min(100, $score));

            if ($finalScore > 40) { // تجاهل المتبرعين ذوي السكور الضعيف جداً
                $matchingScores[] = [
                    'blood_request_id' => $request->id,
                    'donor_id' => $donor->id,
                    'match_score' => $finalScore,
                    'eta_minutes' => $etaMinutes,
                    'is_notified' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // ترتيب النتائج تنازلياً حسب السكور وأخذ أفضل المتبرعين
        usort($matchingScores, fn($a, $b) => $b['match_score'] <=> $a['match_score']);
        $topMatches = array_slice($matchingScores, 0, $limit);

        // حفظ النتائج في قاعدة البيانات
        MatchingResult::insert($topMatches);

        return $topMatches;
    }

    /**
     * حساب المسافة بين نقطتين جغرافيتين (خوارزمية Haversine)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) return 999; // مسافة بعيدة افتراضية إذا لم يتوفر الموقع
        
        $earthRadius = 6371; // نصف قطر الأرض بالكيلومتر
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
             
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
}