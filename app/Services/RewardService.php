<?php

namespace App\Services;

use App\Models\RewardTransaction;
use App\Models\Reward;

class RewardService
{
    /**
     * حساب إجمالي النقاط المتاحة للمتبرع (المكتسبة مطروحاً منها المستهلكة)
     */
    public function getDonorPoints(int $donorId): int
    {
        $earned = RewardTransaction::where('donor_id', $donorId)->where('type', 'earned')->sum('points');
        $redeemed = RewardTransaction::where('donor_id', $donorId)->where('type', 'redeemed')->sum('points');

        return $earned - $redeemed;
    }

    /**
     * جلب سجل معاملة المكافآت والنقاط للمتبرع
     */
    public function getDonorHistory(int $donorId)
    {
        return RewardTransaction::with('reward')
            ->where('donor_id', $donorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * جلب قائمة المكافآت والشارات المتاحة مرتبة حسب النقاط المطلوبة
     */
    public function getAvailableBadges()
    {
        return Reward::orderBy('points_required', 'asc')->get();
    }
}
