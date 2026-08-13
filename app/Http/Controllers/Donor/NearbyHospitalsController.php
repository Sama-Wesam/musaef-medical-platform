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
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric'
        ]);

        $radius = $request->radius ?? 15;

        $hospitals = Hospital::with('user')
            ->where('is_verified', true)
            ->nearby($request->latitude, $request->longitude, $radius)
            ->get();

        return $this->successResponse($hospitals, 'تم جلب المستشفيات القريبة بنجاح');
    }
}
