<?php

namespace App\Services;

use App\AI\BloodDemandForecast;
use App\AI\HeatMapAnalysis;
use App\AI\ResponsePrediction;

class AIService
{
    public function __construct(
        protected BloodDemandForecast $demandForecast,
        protected HeatMapAnalysis $heatMap,
        protected ResponsePrediction $responsePrediction
    ) {}

    public function getHospitalDemandForecast(int $hospitalId, int $bloodTypeId): mixed
    {
        return $this->demandForecast->predictShortage($hospitalId, $bloodTypeId);
    }

    public function getLiveHeatMapData(): mixed
    {
        return $this->heatMap->generateEmergencyHotspots();
    }

    public function getDonorResponseProbability(int $donorId): mixed
    {
        return $this->responsePrediction->predictProbability($donorId);
    }
}
