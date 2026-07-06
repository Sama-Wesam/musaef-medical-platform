<?php

namespace App\Events;

use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationAccepted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donor;
    public $bloodRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(Donor $donor, BloodRequest $bloodRequest)
    {
        $this->donor = $donor;
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * القنوات التي سيتم بث الحدث عليها
     */
    public function broadcastOn(): array
    {
        // بث على قناة خاصة بالمستشفى لتحديث لوحة التحكم (Dashboard) لديهم فوراً
        $hospitalUserId = $this->bloodRequest->hospital->user_id;
        
        return [
            new PrivateChannel('hospital.' . $hospitalUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'donation.accepted';
    }
}