<?php

namespace App\Services;

use App\AI\BloodDemandForecast;
use App\AI\HeatMapAnalysis;
use App\AI\ResponsePrediction;

class AIService
{
    protected $demandForecast;
    protected $heatMap;
    protected $responsePrediction;

    public function __construct(
        BloodDemandForecast $demandForecast,
        HeatMapAnalysis $heatMap,
        ResponsePrediction $responsePrediction
    ) {
        $this->demandForecast = $demandForecast;
        $this->heatMap = $heatMap;
        $this->responsePrediction = $responsePrediction;
    }

    public function getHospitalDemandForecast(int $hospitalId, int $bloodTypeId)
    {
        // تم التعديل إلى predictShortage لتتطابق مع دالة محرك الذكاء الاصطناعي الفعلي
        return $this->demandForecast->predictShortage($hospitalId, $bloodTypeId);
    }

    public function getLiveHeatMapData()
    {
        return $this->heatMap->generateEmergencyHotspots();
    }

    public function getDonorResponseProbability(int $donorId)
    {
        return $this->responsePrediction->predictProbability($donorId);
    }
}
