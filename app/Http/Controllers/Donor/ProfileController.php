<?php

namespace App\Http\Controllers\Donor;

use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\HealthInfo;

class ProfileController extends Controller
{
    use ApiResponseTrait;

    /**
     * عرض بيانات الملف الشخصي والبيانات الصحية
     */
    public function show(Request $request)
    {
        $donor = $request->user()->donor;

        if (!$donor) {
            return $this->notFoundResponse('بيانات المتبرع غير موجودة');
        }

        $donor->load(['healthInfo', 'bloodType']);

        return $this->successResponse($donor, 'تم جلب الملف الشخصي');
    }

    /**
     * تحديث بيانات الموقع والتوفر
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'is_available' => 'boolean',
        ]);

        $donor = $request->user()->donor;
        $donor->update($validated);

        return $this->successResponse($donor, 'تم تحديث الملف الشخصي بنجاح');
    }

    /**
     * تحديث أو إنشاء البيانات الصحية
     */
    public function updateHealthInfo(Request $request)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:40',
            'height' => 'required|numeric',
            'has_chronic_diseases' => 'required|boolean',
            'diseases_description' => 'nullable|string',
        ]);

        $donor = $request->user()->donor;

        $healthInfo = HealthInfo::updateOrCreate(
            ['donor_id' => $donor->id],
            $validated
        );

        return $this->successResponse($healthInfo, 'تم تحديث البيانات الصحية بنجاح');
    }
}
