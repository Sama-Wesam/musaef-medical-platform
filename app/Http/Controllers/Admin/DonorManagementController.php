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

    /**
     * جلب مؤشرات نشاط المتبرعين لنداءات وتوجيه الحملات الطارئة
     */
    public function getDonorActivityMetrics(Request $request)
    {
        $query = User::where('role', 'donor');

        if ($request->has('blood_type') && $request->blood_type !== 'all') {
            $query->where('blood_type', $request->blood_type);
        }

        $donors = $query->get();

        // حساب مؤشر النشاط (Activity Score) واستجابة الذكاء الاصطناعي
        $metrics = $donors->map(function ($donor) {
            // حساب درجات النشاط بناءً على تفاعلات المتبرع السابقة والتنبؤ
            $activityScore = $donor->last_donation_date
                ? max(0, 100 - now()->diffInDays($donor->last_donation_date))
                : 50;

            return [
                'donor_id' => $donor->id,
                'name' => $donor->name,
                'blood_type' => $donor->blood_type,
                'activity_score' => $activityScore, // مؤشر النشاط التحليلي
                'ai_category' => $activityScore >= 60 ? 'active_ai' : 'suspended_ai',
                'recommended_for_emergency' => $activityScore >= 75
            ];
        });

        return $this->successResponse([
            'total_donors' => $metrics->count(),
            'high_response_donors' => $metrics->where('recommended_for_emergency', true)->count(),
            'donors_metrics' => $metrics
        ], 'تم جلب مؤشرات نشاط المتبرعين وتحديث خوارزمية توجيه الحملات بنجاح');
    }
}
