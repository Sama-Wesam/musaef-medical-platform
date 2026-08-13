<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Fcm\FcmChannel;
use NotificationChannels\Fcm\FcmMessage;
use NotificationChannels\Fcm\Resources\AndroidConfig;
use NotificationChannels\Fcm\Resources\AndroidNotification;
use NotificationChannels\Fcm\Resources\Notification as FcmNotification;

class EmergencyNotification extends Notification implements ShouldQueue
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
        $channels = ['database', 'mail'];

        // إرسال FCM فقط إذا كان المستخدم يملك FCM Token مسجل
        if (!empty($notifiable->fcm_token) && class_exists(FcmChannel::class)) {
            $channels[] = FcmChannel::class;
        }

        return $channels;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $hospitalName = $this->bloodRequest->hospital->facility_name
            ?? $this->bloodRequest->hospital->user->name
            ?? 'أحد المستشفيات';

        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        $emergencyLevel = is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'value')
            ? $this->bloodRequest->emergency_level->value
            : (string) $this->bloodRequest->emergency_level;

        return (new MailMessage)
            ->error()
            ->subject('🚨 نداء طوارئ عاجل من مسعف!')
            ->greeting('مرحباً يا بطل،')
            ->line("مستشفى {$hospitalName} في حاجة ماسة لمتبرعين بفصيلة دم {$bloodType}.")
            ->line("مستوى الحالة: " . strtoupper($emergencyLevel))
            ->action('عرض التفاصيل والتبرع', url("/emergency/{$this->bloodRequest->id}"))
            ->line('تذكر: كل دقيقة قد تنقذ حياة.');
    }

    public function toFcm(object $notifiable): FcmMessage
    {
        $hospitalName = $this->bloodRequest->hospital->facility_name
            ?? $this->bloodRequest->hospital->user->name
            ?? 'مستشفى';

        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        $emergencyLevel = is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'value')
            ? $this->bloodRequest->emergency_level->value
            : (string) $this->bloodRequest->emergency_level;

        return FcmMessage::create()
            ->setData([
                'id'              => (string) $this->bloodRequest->id,
                'type'            => 'emergency',
                'emergency_level' => (string) $emergencyLevel,
            ])
            ->setNotification(
                FcmNotification::create()
                    ->setTitle('🚨 نداء طوارئ عاجل!')
                    ->setBody("مستشفى {$hospitalName} بحاجة ماسة لـ {$this->bloodRequest->units_required} وحدات من فصيلة {$bloodType}.")
            )
            ->setAndroid(
                AndroidConfig::create()
                    ->setPriority('high')
                    ->setNotification(
                        AndroidNotification::create()
                            ->setSound('emergency_alarm')
                            ->setChannelId('emergency_channel')
                    )
            );
    }

    public function toArray(object $notifiable): array
    {
        $hospitalName = $this->bloodRequest->hospital->facility_name
            ?? $this->bloodRequest->hospital->user->name
            ?? 'مستشفى';

        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        $emergencyLevel = is_object($this->bloodRequest->emergency_level) && method_exists($this->bloodRequest->emergency_level, 'value')
            ? $this->bloodRequest->emergency_level->value
            : (string) $this->bloodRequest->emergency_level;

        return [
            'title_key'       => 'notifications.emergency_title',
            'body_key'        => 'notifications.emergency_body',
            'body_params'     => [
                'hospital_name'  => $hospitalName,
                'units_required' => $this->bloodRequest->units_required ?? 1,
                'blood_type'     => $bloodType
            ],
            'type'            => 'emergency',
            'related_id'      => $this->bloodRequest->id,
            'related_type'    => get_class($this->bloodRequest),
            'emergency_level' => $emergencyLevel,
        ];
    }
}
