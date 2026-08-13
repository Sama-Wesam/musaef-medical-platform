<?php

namespace App\Services;

use App\Models\RewardTransaction;
use App\Models\Reward;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Exception;

class RewardService
{
    /**
     * حساب إجمالي النقاط المتاحة للمتبرع (المكتسبة مطروحاً منها المستهلكة)
     */
    public function getDonorPoints(int $donorId): int
    {
        $earned = (int) RewardTransaction::where('donor_id', $donorId)
            ->where('type', 'earned')
            ->sum('points');

        $redeemed = (int) RewardTransaction::where('donor_id', $donorId)
            ->where('type', 'redeemed')
            ->sum('points');

        return max(0, $earned - $redeemed);
    }

    /**
     * جلب سجل معاملة المكافآت والنقاط للمتبرع
     */
    public function getDonorHistory(int $donorId): Collection
    {
        return RewardTransaction::with('reward')
            ->where('donor_id', $donorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * جلب قائمة المكافآت والشارات المتاحة مرتبة حسب النقاط المطلوبة
     */
    public function getAvailableBadges(): Collection
    {
        return Reward::orderBy('points_required', 'asc')->get();
    }

    /**
     * استبدال النقاط بمكافأة أو وسام مع التحقق من الرصيد وقفل المعاملة
     */
    public function redeemReward(int $donorId, int $rewardId): bool
    {
        return DB::transaction(function () use ($donorId, $rewardId) {
            // استخدام lockForUpdate لمنع السباق التزامني عند الضغط المزدوج
            $reward = Reward::lockForUpdate()->findOrFail($rewardId);
            $currentPoints = $this->getDonorPoints($donorId);

            if ($currentPoints < $reward->points_required) {
                throw new Exception("رصيد النقاط غير كافٍ لاستبدال هذه المكافأة.");
            }

            RewardTransaction::create([
                'donor_id'    => $donorId,
                'reward_id'   => $rewardId,
                'points'      => $reward->points_required,
                'type'        => 'redeemed',
                'description' => "استبدال وسام/مكافأة: {$reward->name}",
            ]);

            return true;
        });
    }
}
