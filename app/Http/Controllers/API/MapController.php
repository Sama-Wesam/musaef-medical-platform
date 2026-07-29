<?php

namespace App\Http\Controllers\API;

use App\Models\Hospital;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MapController extends Controller
{
    use ApiResponseTrait;

    public function nearbyHospitals(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric' // بالكيلومتر
        ]);

        $radius = $request->radius ?? 15; // 15 كم افتراضي

        // استدعاء Scope_nearby في LocationTrait داخل مودل Hospital
        $hospitals = Hospital::with('user')
            ->where('is_verified', true)
            ->nearby($request->latitude, $request->longitude, $radius)
            ->get();

        return $this->successResponse($hospitals, 'تم جلب المستشفيات القريبة');
    }
}
