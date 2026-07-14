<?php

namespace App\Repositories;

use App\Models\MatchingResult;
use Illuminate\Database\Eloquent\Collection;

class AIRepository
{
    public function saveMatchingResults(array $results): bool
    {
        return MatchingResult::insert($results);
    }

    public function getTopMatchesForRequest(int $requestId, int $limit = 10): Collection
    {
        return MatchingResult::with(['donor.user', 'donor.bloodType'])
            ->where('blood_request_id', $requestId)
            ->orderBy('match_score', 'desc')
            ->limit($limit)
            ->get();
    }

    public function markMatchAsNotified(int $matchId): bool
    {
        $match = MatchingResult::find($matchId);
        if ($match) {
            return $match->update(['is_notified' => true]);
        }
        return false;
    }

    public function clearOldResults(int $requestId): bool
    {
        // مسح النتائج القديمة لنفس الطلب في حال تم إعادة تشغيل الخوارزمية
        MatchingResult::where('blood_request_id', $requestId)->delete();
        return true;
    }
}
