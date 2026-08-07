<?php

namespace App\Http\Controllers\Hospital;

use App\Models\Donor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MapController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب المتبرعين المتاحين بالقرب من المستشفى
     */
    public function nearbyDonors(Request $request)
    {
        $hospital = $request->user()->hospital;
        $radius = $request->radius ?? 20; // نطاق البحث الافتراضي 20 كم

        // باستخدام Scope_nearby من LocationTrait
        $donors = Donor::with('user', 'bloodType')
            ->where('is_available', true)
            ->nearby($hospital->latitude, $hospital->longitude, $radius)
            ->get();

        // إخفاء البيانات الحساسة مثل رقم الهاتف إذا لم يكن هناك طلب طوارئ
        $donors->transform(function ($donor) {
            $donor->user->makeHidden(['phone', 'email']);
            return $donor;
        });

        return $this->successResponse($donors, 'تم جلب المتبرعين المتاحين في محيطكم');
    }
}