<?php

namespace App\Http\Controllers\API;

use App\Services\HospitalService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class HospitalController extends Controller
{
    use ApiResponseTrait;

    protected $hospitalService;

    public function __construct(HospitalService $hospitalService)
    {
        $this->hospitalService = $hospitalService;
    }

    public function inventory(Request $request)
    {
        $hospitalId = $request->user()->hospital->id ?? null;
        if (!$hospitalId) return $this->unauthorizedResponse();

        $inventory = $this->hospitalService->getInventory($hospitalId);
        return $this->successResponse($inventory);
    }
}