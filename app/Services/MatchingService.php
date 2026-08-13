<?php

namespace App\Services;

use App\AI\SmartMatchingEngine;
use App\Repositories\Contracts\AIRepositoryInterface;
use App\Models\BloodRequest;

class MatchingService
{
    public function __construct(
        protected SmartMatchingEngine $matchingEngine,
        protected AIRepositoryInterface $aiRepository
    ) {}

    public function executeMatch(BloodRequest $request, int $limit = 10)
    {
        $this->aiRepository->clearOldResults($request->id);

        return $this->matchingEngine->runMatching($request, $limit);
    }

    public function getMatchingResults(int $requestId)
    {
        return $this->aiRepository->getTopMatchesForRequest($requestId);
    }
}
