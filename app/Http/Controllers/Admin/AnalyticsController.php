<?php

namespace App\Http\Controllers\Admin;

use App\Services\AIService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AnalyticsController extends Controller
{
    use ApiResponseTrait;

    protected $aiService;

    public function __construct(AIService $aiService)
    {
        $this->aiService = $aiService;
    }

    /**
     * جلب بيانات الخريطة الحرارية لأماكن الطوارئ
     */
    public function heatMapData()
    {
        $hotspots = $this->aiService->getLiveHeatMapData();
        return $this->successResponse($hotspots, 'تم جلب بيانات الخريطة الحرارية');
    }

    /**
     * جلب توقعات احتياج المستشفيات للدم
     */
    public function demandForecast(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|integer|exists:hospitals,id',
            'blood_type_id' => 'required|integer|exists:blood_types,id',
        ]);

        $forecast = $this->aiService->getHospitalDemandForecast(
            $request->hospital_id, 
            $request->blood_type_id
        );

        return $this->successResponse($forecast, 'تم جلب التوقعات بنجاح');
    }
}