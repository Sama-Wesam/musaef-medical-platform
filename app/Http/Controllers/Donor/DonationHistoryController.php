<?php

namespace App\Http\Controllers\Donor;

use App\Repositories\DonationRepository;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class DonationHistoryController extends Controller
{
    use ApiResponseTrait;

    protected $donationRepo;

    public function __construct(DonationRepository $donationRepo)
    {
        $this->donationRepo = $donationRepo;
    }

    /**
     * جلب سجل التبرعات الناجحة
     */
    public function index(Request $request)
    {
        $donorId = $request->user()->donor->id;
        $history = $this->donationRepo->getDonationsByDonor($donorId);
        
        return $this->successResponse($history, 'تم جلب سجل التبرعات');
    }
}