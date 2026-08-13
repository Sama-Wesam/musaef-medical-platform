<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EmergencyStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bloodRequest;

    /**
     * Create a new event instance.
     */
    public function __construct(BloodRequest $bloodRequest)
    {
        $this->bloodRequest = $bloodRequest->loadMissing(['hospital.user', 'bloodType']);
    }

    public function broadcastOn(): array
    {
        $hospitalUserId = $this->bloodRequest->hospital->user_id
            ?? $this->bloodRequest->hospital_id;

        return [
            new Channel('emergencies.live'),
            new PrivateChannel('hospital.' . $hospitalUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'emergency.status.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'id'         => $this->bloodRequest->id,
            'status'     => is_object($this->bloodRequest->status) && method_exists($this->bloodRequest->status, 'toArray')
                            ? $this->bloodRequest->status->toArray()
                            : $this->bloodRequest->status,
            'updated_at' => now()->toIso8601String(),
        ];
    }
}
