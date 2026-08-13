<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
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
        $this->bloodRequest = $bloodRequest->loadMissing(['hospital.user', 'bloodType']);
    }

    /**
     * القناة التي سيتم بث الحدث عليها (لتحديث الخريطة المباشرة)
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('emergencies.live'),
            new PrivateChannel('admin.dashboard')
        ];
    }

    public function broadcastAs(): string
    {
        return 'new.emergency';
    }

    public function broadcastWith(): array
    {
        return [
            'id'               => $this->bloodRequest->id,
            'latitude'         => (float) ($this->bloodRequest->hospital->latitude ?? $this->bloodRequest->latitude ?? 0),
            'longitude'        => (float) ($this->bloodRequest->hospital->longitude ?? $this->bloodRequest->longitude ?? 0),
            'hospital_name'    => $this->bloodRequest->hospital->facility_name ?? $this->bloodRequest->hospital->user->name ?? 'مستشفى غير محدد',
            'blood_type'       => $this->bloodRequest->bloodType->name ?? $this->bloodRequest->blood_type ?? '',
            'emergency_level'  => is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'toArray')
                                  ? $this->bloodRequest->emergency_level->toArray()
                                  : $this->bloodRequest->emergency_level,
            'units_required'   => $this->bloodRequest->units_required ?? 1,
            'created_at'       => $this->bloodRequest->created_at ? $this->bloodRequest->created_at->toIso8601String() : now()->toIso8601String(),
        ];
    }
}
