<?php

namespace App\Http\Controllers\Donor;

use App\Models\DonorResponse;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmergencyNotificationsController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب قائمة بنداءات الطوارئ التي وصلت للمتبرع
     */
    public function index(Request $request)
    {
        $donorId = $request->user()->donor->id;
        
        $responses = DonorResponse::with('bloodRequest.hospital.user', 'bloodRequest.bloodType')
            ->where('donor_id', $donorId)
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($responses, 'تم جلب نداءات الطوارئ الخاصة بك');
    }

    /**
     * تحديث حالة الاستجابة (قبول أو رفض النداء)
     */
    public function update(Request $request, $id)
    {
        $donorResponse = DonorResponse::where('id', $id)
            ->where('donor_id', $request->user()->donor->id)
            ->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:accepted,declined',
            'eta_minutes' => 'nullable|integer'
        ]);

        $donorResponse->update($validated);

        return $this->successResponse($donorResponse, 'تم تسجيل استجابتك، شكراً لتعاونك!');
    }
}