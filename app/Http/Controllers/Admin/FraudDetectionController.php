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
     * تقييم سلوك مستشفى معين للكشف عن الطلبات الوهمية وتحديث الحالة تلقائياً
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
            // تحديث حالة الحساب تلقائياً إلى معلق بواسطة الذكاء الاصطناعي
            $hospital->update(['status' => 'suspended_ai']);

            return $this->successResponse([
                'analysis' => $analysis,
                'hospital_status' => 'suspended_ai'
            ], 'تم رصد سلوك مشبوه وتأليق الحساب تلقائياً!', 200);
        }

        return $this->successResponse([
            'analysis' => $analysis,
            'hospital_status' => $hospital->status
        ], 'سلوك المستشفى طبيعي.');
    }

    /**
     * المراجعة الإدارية وتغيير حالة الحساب بناءً على التقييم التفاعلي للذكاء الاصطناعي
     */
    public function reviewAccountStatus(Request $request)
    {
        $request->validate([
            'account_id' => 'required',
            'account_type' => 'required|in:hospital,donor',
            'action' => 'required|in:approve,suspend,re_evaluate'
        ]);

        if ($request->account_type === 'hospital') {
            $account = Hospital::findOrFail($request->account_id);
        } else {
            $account = \App\Models\User::findOrFail($request->account_id);
        }

        if ($request->action === 're_evaluate') {
            // إعادة تقييم تفاعلي عبر الذكاء الاصطناعي
            $analysis = $this->fraudAI->analyzeRequest($account, 0);
            $newStatus = $analysis['is_suspicious'] ? 'suspended_ai' : 'active';
            $account->update(['status' => $newStatus]);

            return $this->successResponse([
                'account' => $account,
                'ai_analysis' => $analysis
            ], 'تم إعادة تقييم الحساب بواسطة الذكاء الاصطناعي بنجاح');
        }

        $newStatus = $request->action === 'approve' ? 'active' : 'suspended_ai';
        $account->update(['status' => $newStatus]);

        return $this->successResponse([
            'account' => $account
        ], 'تم تحديث حالة الحساب والمراجعة الإدارية بنجاح');
    }
}
