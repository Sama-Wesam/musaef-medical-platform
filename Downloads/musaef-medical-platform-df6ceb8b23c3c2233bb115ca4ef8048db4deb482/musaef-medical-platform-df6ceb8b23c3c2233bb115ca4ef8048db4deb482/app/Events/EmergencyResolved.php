<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyResolved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bloodRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * القنوات التي سيتم بث الحدث عليها
     */
    public function broadcastOn(): array
    {
        // بث على الخريطة العامة لإخفاء النقطة الحمراء الخاصة بهذه الحالة
        return [
            new Channel('emergencies.live'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'emergency.resolved';
    }
}