<?php

namespace App\Events;

use App\Models\BloodRequest;
use App\Models\Donor;
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
        $this->donor = $donor->loadMissing(['user', 'bloodType']);
        $this->bloodRequest = $bloodRequest->loadMissing(['hospital.user', 'bloodType']);
    }

    /**
     * القنوات التي سيتم بث الحدث عليها
     */
    public function broadcastOn(): array
    {
        $hospitalUserId = $this->bloodRequest->hospital->user_id
            ?? $this->bloodRequest->hospital_id;

        return [
            new PrivateChannel('hospital.' . $hospitalUserId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'donation.accepted';
    }

    /**
     * تخصيص حزمة البيانات المرسلة للواجهة الأمامية
     */
    public function broadcastWith(): array
    {
        return [
            'request_id'   => $this->bloodRequest->id,
            'donor_id'     => $this->donor->id,
            'donor_name'   => $this->donor->user->name ?? 'متبرع',
            'donor_phone'  => $this->donor->user->phone ?? null,
            'blood_type'   => $this->donor->bloodType->name ?? $this->donor->blood_type ?? '',
            'accepted_at'  => now()->toIso8601String(),
        ];
    }
}
