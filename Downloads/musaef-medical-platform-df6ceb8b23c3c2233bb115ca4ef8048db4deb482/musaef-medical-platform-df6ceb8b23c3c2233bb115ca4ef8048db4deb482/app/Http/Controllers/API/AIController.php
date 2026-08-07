<?php

namespace App\Http\Controllers\API;

use App\Services\MatchingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class AIController extends Controller
{
    use ApiResponseTrait;

    protected $matchingService;

    public function __construct(MatchingService $matchingService)
    {
        $this->matchingService = $matchingService;
    }

    public function matchResults($requestId)
    {
        $results = $this->matchingService->getMatchingResults($requestId);
        return $this->successResponse($results, 'تم جلب نتائج المطابقة الذكية للطلب');
    }
}