<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\AI\ResponsePrediction;

class DonorManagementController extends Controller
{
    use ApiResponseTrait;

    protected $responsePredictionAI;

    public function __construct(ResponsePrediction $responsePredictionAI)
    {
        $this->responsePredictionAI = $responsePredictionAI;
    }

    public function getDonorActivityMetrics(Request $request)
    {
        $query = User::where('role', 'donor')->with('donor.bloodType');

        if ($request->has('blood_type') && $request->blood_type !== 'all') {
            $bloodType = $request->blood_type;
            $query->where(function ($q) use ($bloodType) {
                if (is_numeric($bloodType)) {
                    $q->whereHas('donor', fn($d) => $d->where('blood_type_id', $bloodType));
                } else {
                    $q->where('blood_type', $bloodType)
                      ->orWhereHas('donor.bloodType', fn($b) => $b->where('name', $bloodType));
                }
            });
        }

        $donors = $query->get();

        $metrics = $donors->map(function ($donor) {
            $activityScore = $donor->last_donation_date
                ? max(0, 100 - now()->diffInDays($donor->last_donation_date))
                : 50;

            $bloodTypeName = $donor->donor?->bloodType?->name ?? $donor->blood_type;

            return [
                'donor_id'                  => $donor->id,
                'name'                      => $donor->name,
                'blood_type'                => $bloodTypeName,
                'activity_score'            => $activityScore,
                'ai_category'               => $activityScore >= 60 ? 'active_ai' : 'suspended_ai',
                'recommended_for_emergency' => $activityScore >= 75
            ];
        });

        return $this->successResponse([
            'total_donors'          => $metrics->count(),
            'high_response_donors'  => $metrics->where('recommended_for_emergency', true)->count(),
            'donors_metrics'        => $metrics
        ], 'تم جلب مؤشرات نشاط المتبرعين وتحديث خوارزمية توجيه الحملات بنجاح');
    }

    /**
     * ⚡ دالة Polling سريعة لمتابعة أعداد المتبرعين النشطين والجاهزين للطوارئ
     */
    public function pollDonorMetrics()
    {
        $totalDonors = User::where('role', 'donor')->count();

        return $this->successResponse([
            'total_donors' => $totalDonors,
            'timestamp'    => now()->toDateTimeString()
        ], 'تم تحديث إحصائيات المتبرعين المباشرة بنجاح');
    }
}
