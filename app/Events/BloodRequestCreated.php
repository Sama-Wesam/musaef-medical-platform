<?php

namespace App\Events;

use App\Models\BloodRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BloodRequestCreated implements ShouldBroadcast
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
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('hospital-dashboard'),
        ];
    }

    /**
     * اسم الحدث في Vue.js عبر Echo
     */
    public function broadcastAs(): string
    {
        return 'blood.request.created';
    }

    /**
     * تخصيص البيانات المرسلة عبر البث الحي
     */
    public function broadcastWith(): array
    {
        return [
            'id'              => $this->bloodRequest->id,
            'hospital_name'   => $this->bloodRequest->hospital->facility_name ?? $this->bloodRequest->hospital->user->name ?? 'مستشفى غير محدد',
            'blood_type'      => $this->bloodRequest->bloodType->name ?? $this->bloodRequest->blood_type ?? '',
            'emergency_level' => is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'toArray')
                                 ? $this->bloodRequest->emergency_level->toArray()
                                 : $this->bloodRequest->emergency_level,
            'units_required'  => $this->bloodRequest->units_required ?? 1,
            'created_at'      => $this->bloodRequest->created_at ? $this->bloodRequest->created_at->toIso8601String() : now()->toIso8601String(),
        ];
    }
}
