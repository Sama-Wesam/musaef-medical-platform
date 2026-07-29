<?php

namespace App\Http\Controllers\API;

use App\Models\BloodRequest;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BloodRequestController extends Controller
{
    use ApiResponseTrait;

    // عرض طلبات التبرع ذات الحالة pending للعامة وللمتبرعين[cite: 14]
    public function index()
    {
        $requests = BloodRequest::with(['hospital', 'bloodType'])
                    ->where('status', 'pending')
                    ->latest()
                    ->get();

        return $this->successResponse($requests, 'تم جلب طلبات التبرع بنجاح');
    }
}
