<?php

namespace App\AI;

use App\Models\BloodRequest;
use App\Models\Donor;
use App\Models\MatchingResult;
use App\Models\DonorResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class SmartMatchingEngine
{
    /**
     * تشغيل خوارزمية المطابقة لطلب طوارئ معين عبر ربطه بمحرك الذكاء الاصطناعي (Python)
     */
    public function runMatching(BloodRequest $request, int $limit = 10): array
    {
        try {
            $hospital = $request->hospital;

            // 1. جلب المتبرعين المتاحين والذين تتوافق فصيلتهم
            $donors = Donor::where('is_available', '=', true)
                   ->where('blood_type_id', '=', $request->blood_type_id)
                   ->with(['healthInfo', 'donations', 'user'])
                   ->get();

            if ($donors->isEmpty()) {
                return [];
            }

            // 2. تجهيز البيانات بدقة مطابقة لمتطلبات سكريبت smart_matching.py
            $donorsData = $donors->map(function ($donor) {
                $daysSinceLastDonation = 90;
                if ($donor->last_donation_date) {
                    $daysSinceLastDonation = Carbon::parse($donor->last_donation_date)->diffInDays(now());
                }

                return [
                    'donor_id'                 => $donor->id,
                    'latitude'                 => (float) ($donor->latitude ?? 31.5),
                    'longitude'                => (float) ($donor->longitude ?? 34.45),
                    'date_of_birth'            => $donor->birth_date ? Carbon::parse($donor->birth_date)->toDateString() : '1995-01-01',
                    'days_since_last_donation' => $daysSinceLastDonation,
                    'is_eligible'              => (bool) ($donor->healthInfo->is_eligible ?? true),
                    'successful_donations'     => $donor->donations ? $donor->donations->where('status', 'successful')->count() : 0,
                ];
            })->toArray();

            $payload = [
                'hospital' => [
                    'latitude'  => (float) ($hospital->latitude ?? 31.51),
                    'longitude' => (float) ($hospital->longitude ?? 34.44),
                ],
                'limit'  => $limit,
                'donors' => $donorsData,
            ];

            $pythonPath = env('PYTHON_PATH');
            if (!$pythonPath) {
                $venvWin = base_path('.venv/Scripts/python.exe');
                $venvLinux = base_path('.venv/bin/python');

                if (file_exists($venvWin)) {
                    $pythonPath = $venvWin;
                } elseif (file_exists($venvLinux)) {
                    $pythonPath = $venvLinux;
                } else {
                    $pythonPath = PHP_OS_FAMILY === 'Windows' ? 'python' : 'python3';
                }
            }

            $process = new Process([
                $pythonPath,
                base_path('scripts/python/smart_matching.py'),
                json_encode($payload, JSON_UNESCAPED_UNICODE)
            ]);

            $process->setWorkingDirectory(base_path());
            $process->setTimeout(10);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $output = $process->getOutput();
            $topMatchesFromAI = json_decode($output, true);

            $finalMatchesToInsert = [];

            if (is_array($topMatchesFromAI)) {
                foreach ($topMatchesFromAI as $match) {
                    if (!isset($match['donor_id'])) continue;

                    $finalMatchesToInsert[] = [
                        'blood_request_id' => $request->id,
                        'donor_id'         => $match['donor_id'],
                        'match_score'      => $match['match_score'] ?? 0,
                        'eta_minutes'      => $match['eta_minutes'] ?? 0,
                        'is_notified'      => true,
                        'created_at'       => now(),
                        'updated_at'       => now(),
                    ];

                    // تسجيل الاستجابة المباشرة للمستشفى
                    DonorResponse::updateOrCreate(
                        [
                            'blood_request_id' => $request->id,
                            'donor_id'         => $match['donor_id'],
                        ],
                        [
                            'status'       => 'accepted',
                            'match_score'  => $match['match_score'] ?? 98,
                            'eta_minutes'  => $match['eta_minutes'] ?? 10,
                            'responded_at' => now(),
                        ]
                    );

                    // إدراج الإشعار بجدول notifications متوافق 100% مع أعمدة المايجريشن
                    try {
                        $matchedDonor = $donors->firstWhere('id', $match['donor_id']);
                        if ($matchedDonor && $matchedDonor->user_id) {
                            $notifBody = 'تم ترشيحك بواسطة محرك المطابقة الذكية لنداء طوارئ عاجل في ' . ($hospital->facility_name ?? 'المستشفى');

                            DB::table('notifications')->insert([
                                'id'           => Str::uuid()->toString(),
                                'user_id'      => $matchedDonor->user_id,
                                'title'        => '🚨 نداء تبرع طارئ مطابق بالذكاء الاصطناعي',
                                'body'         => $notifBody,
                                'type'         => 'emergency',
                                'related_id'   => $request->id,
                                'related_type' => BloodRequest::class,
                                'data'         => json_encode([
                                    'request_id'   => $request->id,
                                    'hospital'     => $hospital->facility_name ?? 'المستشفى',
                                    'match_score'  => $match['match_score'] ?? 98,
                                    'eta_minutes'  => $match['eta_minutes'] ?? 10
                                ], JSON_UNESCAPED_UNICODE),
                                'is_read'      => false,
                                'created_at'   => now(),
                                'updated_at'   => now(),
                            ]);
                        }
                    } catch (\Throwable $notifErr) {
                        Log::warning('Notification Insert Warning Handled: ' . $notifErr->getMessage());
                    }
                }
            }

            if (!empty($finalMatchesToInsert)) {
                MatchingResult::insert($finalMatchesToInsert);
            }

            return $finalMatchesToInsert;

        } catch (\Throwable $e) {
            Log::error('SmartMatchingEngine Error: ' . $e->getMessage(), ['request_id' => $request->id]);
            return [];
        }
    }
}
