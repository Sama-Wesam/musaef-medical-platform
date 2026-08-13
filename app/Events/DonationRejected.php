<?php

namespace App\Events;

use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DonationRejected implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $donor;
    public $bloodRequest;
    public $reason;

    /**
     * Create a new event instance.
     */
    public function __construct(Donor $donor, BloodRequest $bloodRequest, ?string $reason = null)
    {
        $this->donor = $donor->loadMissing('user');
        $this->bloodRequest = $bloodRequest->loadMissing(['hospital.user']);
        $this->reason = $reason;
    }

    /**
     * القناة الخاصة بالمستشفى
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
        return 'donation.rejected';
    }

    public function broadcastWith(): array
    {
        return [
            'request_id'  => $this->bloodRequest->id,
            'donor_id'    => $this->donor->id,
            'donor_name'  => $this->donor->user->name ?? 'متبرع',
            'reason'      => $this->reason ?? 'تم الاعتذار عن التبرع',
            'rejected_at' => now()->toIso8601String(),
        ];
    }
}
