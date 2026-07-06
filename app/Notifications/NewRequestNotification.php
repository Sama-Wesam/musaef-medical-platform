<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $bloodRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(BloodRequest $bloodRequest)
    {
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
        $hospitalName = $this->bloodRequest->hospital->user->name ?? 'مستشفى غير معروف';
        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        return [
            'title' => '🔔 طلب توفير دم جديد',
            'body' => "قام مستشفى {$hospitalName} بإنشاء طلب جديد لعدد {$this->bloodRequest->units_required} وحدات من فصيلة {$bloodType}.",
            'type' => 'system',
            'related_id' => $this->bloodRequest->id,
            'related_type' => get_class($this->bloodRequest),
        ];
    }
}