<?php

namespace App\Services;

use App\Models\RewardTransaction;
use App\Models\Reward;

class RewardService
{
    public function getDonorPoints(int $donorId): int
    {
        $earned = RewardTransaction::where('donor_id', $donorId)->where('type', 'earned')->sum('points');
        $redeemed = RewardTransaction::where('donor_id', $donorId)->where('type', 'redeemed')->sum('points');
        
        return $earned - $redeemed;
    }

    public function getDonorHistory(int $donorId)
    {
        return RewardTransaction::with('reward')
            ->where('donor_id', $donorId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function getAvailableBadges()
    {
        return Reward::orderBy('points_required', 'asc')->get();
    }
}