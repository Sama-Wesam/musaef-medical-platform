<?php

namespace App\Notifications;

use App\Models\Donor;
use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DonationAcceptedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $donor;
    public $bloodRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(Donor $donor, BloodRequest $bloodRequest)
    {
        $this->donor = $donor;
        $this->bloodRequest = $bloodRequest;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $donorName = $this->donor->user->name ?? 'متبرع';

        return [
            'title' => '✅ استجابة لطلب طوارئ',
            'body' => "المتبرع البطل {$donorName} وافق على تلبية طلب الطوارئ رقم #{$this->bloodRequest->id} وهو الآن في طريقه إليكم.",
            'type' => 'info',
            'related_id' => $this->bloodRequest->id,
            'related_type' => get_class($this->bloodRequest),
        ];
    }
}