<?php

namespace App\Http\Controllers\Hospital;

use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ActiveRequestsController extends Controller
{
    use ApiResponseTrait;

    /**
     * جلب الطلبات النشطة فقط
     */
    public function index(Request $request)
    {
        $hospitalId = $request->user()->hospital->id;
        
        $activeRequests = BloodRequest::with('bloodType', 'responses')
            ->where('hospital_id', $hospitalId)
            ->whereIn('status', ['pending', 'searching', 'accepted'])
            ->orderBy('emergency_level', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->successResponse($activeRequests, 'تم جلب الطلبات النشطة');
    }
}