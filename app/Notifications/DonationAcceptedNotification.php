<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use App\Models\Donor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DonationAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Donor $donor,
        public BloodRequest $bloodRequest
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title_key'    => 'notifications.donation_accepted_title',
            'body_key'     => 'notifications.donation_accepted_body',
            'body_params'  => [
                'donor_name' => $this->donor->user->name ?? 'متبرع',
                'request_id' => $this->bloodRequest->id
            ],
            'type'         => 'info',
            'related_id'   => $this->bloodRequest->id,
            'related_type' => get_class($this->bloodRequest),
        ];
    }
}
