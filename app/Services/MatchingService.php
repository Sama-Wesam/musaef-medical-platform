<?php

namespace App\Services;

use App\AI\SmartMatchingEngine;
use App\Repositories\AIRepository;
use App\Models\BloodRequest;

class MatchingService
{
    protected $matchingEngine;
    protected $aiRepository;

    public function __construct(SmartMatchingEngine $matchingEngine, AIRepository $aiRepository)
    {
        $this->matchingEngine = $matchingEngine;
        $this->aiRepository = $aiRepository;
    }

    public function executeMatch(BloodRequest $request, int $limit = 10)
    {
        // تنظيف أي مطابقة قديمة لهذا الطلب
        $this->aiRepository->clearOldResults($request->id);

        // تشغيل الخوارزمية (التي تقوم بالحفظ تلقائياً في قاعدة البيانات)
        return $this->matchingEngine->runMatching($request, $limit);
    }

    public function getMatchingResults(int $requestId)
    {
        return $this->aiRepository->getTopMatchesForRequest($requestId);
    }
}