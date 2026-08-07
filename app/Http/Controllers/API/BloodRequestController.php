<?php

namespace App\Http\Controllers\API;

use App\Repositories\EmergencyRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class BloodRequestController extends Controller
{
    use ApiResponseTrait;

    protected $emergencyRepo;

    public function __construct(EmergencyRepository $emergencyRepo)
    {
        $this->emergencyRepo = $emergencyRepo;
    }

    // عرض الطلبات المفتوحة للعامة
    public function index()
    {
        $requests = $this->emergencyRepo->getActiveEmergencies();
        return $this->successResponse($requests);
    }
}