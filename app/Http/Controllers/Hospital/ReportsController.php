<?php

namespace App\Http\Controllers\Hospital;

use App\Repositories\DonationRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ReportsController extends Controller
{
    use ApiResponseTrait;

    protected $donationRepo;

    public function __construct(DonationRepository $donationRepo)
    {
        $this->donationRepo = $donationRepo;
    }

    /**
     * جلب سجل عمليات التبرع الناجحة داخل المستشفى
     */
    public function index(Request $request)
    {
        $hospitalId = $request->user()->hospital->id;
        $donations = $this->donationRepo->getDonationsByHospital($hospitalId);
        
        return $this->successResponse($donations, 'تم جلب تقارير التبرع الخاصة بالمستشفى');
    }
}