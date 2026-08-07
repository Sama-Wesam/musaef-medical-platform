<?php

namespace App\Http\Controllers\Admin;

use App\AI\FraudDetectionAI;
use App\Traits\ApiResponseTrait;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FraudDetectionController extends Controller
{
    use ApiResponseTrait;

    protected $fraudAI;

    public function __construct(FraudDetectionAI $fraudAI)
    {
        $this->fraudAI = $fraudAI;
    }

    /**
     * تقييم سلوك مستشفى معين للكشف عن الطلبات الوهمية
     */
    public function analyzeHospital(Request $request)
    {
        $request->validate([
            'hospital_id' => 'required|exists:hospitals,id',
            'simulated_units' => 'required|integer' // لاختبار النظام
        ]);

        $hospital = Hospital::find($request->hospital_id);
        
        $analysis = $this->fraudAI->analyzeRequest($hospital, $request->simulated_units);

        if ($analysis['is_suspicious']) {
            return $this->successResponse($analysis, 'تم رصد سلوك مشبوه!', 200);
        }

        return $this->successResponse($analysis, 'سلوك المستشفى طبيعي.');
    }
}