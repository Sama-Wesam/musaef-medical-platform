<?php

namespace App\Http\Controllers\Donor;

use App\Models\Hospital;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class NearbyHospitalsController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {
        // التحقق من إرسال الإحداثيات الحالية للمتبرع من الهاتف المحمول
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric'
        ]);

        $radius = $request->radius ?? 15; // البحث في نطاق 15 كيلومتر افتراضياً

        // استخدام الـ Scope (nearby) الذي أنشأناه في LocationTrait
        $hospitals = Hospital::with('user')
            ->where('is_verified', true)
            ->nearby($request->latitude, $request->longitude, $radius)
            ->get();

        return $this->successResponse($hospitals, 'تم جلب المستشفيات القريبة');
    }
}