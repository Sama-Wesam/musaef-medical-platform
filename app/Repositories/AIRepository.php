<?php

namespace App\Repositories;

use App\Models\MatchingResult;
use Illuminate\Database\Eloquent\Collection;

class AIRepository
{
    /**
     * حفظ نتائج المطابقة في قاعدة البيانات باستخدام Bulk Insert
     */
    public function saveMatchingResults(array $results): bool
    {
        if (empty($results)) {
            return false;
        }

        return MatchingResult::insert($results);
    }

    /**
     * جلب أعلى نتائج المطابقة لطلب محدد
     */
    public function getTopMatchesForRequest(int $requestId, int $limit = 10): Collection
    {
        return MatchingResult::with(['donor.user', 'donor.bloodType'])
            ->where('blood_request_id', $requestId)
            ->orderBy('match_score', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * تحديث حالة النتيجة إلى تم الإشعار
     */
    public function markMatchAsNotified(int $matchId): bool
    {
        $match = MatchingResult::find($matchId);
        if ($match) {
            return $match->update(['is_notified' => true]);
        }
        return false;
    }

    /**
     * مسح النتائج القديمة لنفس الطلب في حال تم إعادة تشغيل الخوارزمية
     */
    public function clearOldResults(int $requestId): bool
    {
        MatchingResult::where('blood_request_id', $requestId)->delete();
        return true;
    }
}
