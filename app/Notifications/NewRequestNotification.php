<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class NewRequestNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public BloodRequest $bloodRequest;

    public function __construct(BloodRequest $bloodRequest)
    {
        // ضمان تحميل العلاقات قبل إرسال الكائن للطابور
        $this->bloodRequest = $bloodRequest->loadMissing(['hospital.user', 'bloodType']);
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $hospitalName = $this->bloodRequest->hospital->facility_name
            ?? $this->bloodRequest->hospital->user->name
            ?? 'مستشفى غير معروف';

        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        return [
            'title_key'    => 'notifications.new_request_title',
            'body_key'     => 'notifications.new_request_body',
            'body_params'  => [
                'hospital_name'  => $hospitalName,
                'units_required' => $this->bloodRequest->units_required ?? 1,
                'blood_type'     => $bloodType
            ],
            'type'         => 'system',
            'related_id'   => $this->bloodRequest->id,
            'related_type' => get_class($this->bloodRequest),
        ];
    }
}
