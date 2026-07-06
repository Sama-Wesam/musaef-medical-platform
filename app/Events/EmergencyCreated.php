<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyCreated implements ShouldBroadcast
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
     * القناة التي سيتم بث الحدث عليها (لتحديث الخريطة المباشرة)
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        // بث على قناة عامة لكي تظهر النقطة الحمراء على خريطة الطوارئ فوراً
        return [
            new Channel('emergencies.live'),
            new PrivateChannel('admin.dashboard')
        ];
    }

    /**
     * اسم الحدث كما سيظهر في الواجهة الأمامية (Vue.js)
     */
    public function broadcastAs(): string
    {
        return 'new.emergency';
    }
}