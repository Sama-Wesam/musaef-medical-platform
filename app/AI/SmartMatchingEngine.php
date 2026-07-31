<?php

namespace App\AI;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\MatchingResult;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SmartMatchingEngine
{
    /**
     * تشغيل خوارزمية المطابقة لطلب طوارئ معين عبر ربطه بمحرك الذكاء الاصطناعي (Python)
     */
    public function runMatching(BloodRequest $request, int $limit = 10)
    {
        $hospital = $request->hospital;

        // 1. الفلترة المبدئية: جلب المتبرعين المتاحين والذين تتوافق فصيلتهم
        // (لتخفيف حجم البيانات المرسلة إلى بايثون)
        $donors = Donor::where('is_available', '=', true)
               ->where('blood_type_id', '=', $request->blood_type_id)
               ->with(['healthInfo', 'donations'])
               ->get();

        // 2. تجهيز البيانات (Data Preparation) لتمريرها إلى بايثون
        $donorsData = $donors->map(function ($donor) {
            return [
                'donor_id'             => $donor->id,
                'latitude'             => $donor->latitude,
                'longitude'            => $donor->longitude,
                'date_of_birth'        => $donor->birth_date,
                'last_donation_date'   => $donor->last_donation_date ? $donor->last_donation_date->toDateString() : null,
                'is_eligible'          => $donor->healthInfo->is_eligible ?? false,
                'successful_donations' => $donor->donations->where('status', 'successful')->count(),
            ];
        })->toArray();

        $payload = [
            'hospital' => [
                'latitude'  => $hospital->latitude,
                'longitude' => $hospital->longitude,
            ],
            'limit'  => $limit,
            'donors' => $donorsData,
        ];

        $pythonPath = env('PYTHON_PATH', 'python3');

        // 3. استدعاء سكريبت Python باستخدام Process
        $process = new Process([
            $pythonPath,
            base_path('scripts/python/smart_matching.py'),
            json_encode($payload, JSON_UNESCAPED_UNICODE)
        ]);

        $process->run();

        // التحقق من عدم وجود أخطاء أثناء تشغيل ملف البايثون
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // 4. استقبال النتائج من بايثون ومعالجتها
        $output = $process->getOutput();
        $topMatchesFromAI = json_decode($output, true);

        $finalMatchesToInsert = [];

        // تجهيز مصفوفة النتائج لإدخالها في قاعدة بيانات النظام
        if (is_array($topMatchesFromAI)) {
            foreach ($topMatchesFromAI as $match) {
                $finalMatchesToInsert[] = [
                    'blood_request_id' => $request->id,
                    'donor_id'         => $match['donor_id'],
                    'match_score'      => $match['match_score'],
                    'eta_minutes'      => $match['eta_minutes'],
                    'is_notified'      => false,
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ];
            }
        }

        // 5. حفظ النتائج النهائية في قاعدة البيانات
        if (!empty($finalMatchesToInsert)) {
            MatchingResult::insert($finalMatchesToInsert);
        }

        return $finalMatchesToInsert;
    }
}
