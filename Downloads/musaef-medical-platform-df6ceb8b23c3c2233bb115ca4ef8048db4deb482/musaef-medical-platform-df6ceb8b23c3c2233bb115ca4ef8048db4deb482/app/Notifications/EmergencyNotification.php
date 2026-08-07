<?php

namespace App\Notifications;

use App\Models\BloodRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmergencyNotification extends Notification implements ShouldQueue
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
     * يمكن إضافة قنوات أخرى لاحقاً مثل 'fcm' (Firebase) أو SMS
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $hospitalName = $this->bloodRequest->hospital->user->name ?? 'أحد المستشفيات';
        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        return (new MailMessage)
                    ->error() // يعطي لون أحمر للإيميل ليدل على الطوارئ
                    ->subject('🚨 نداء طوارئ عاجل من مسعف!')
                    ->greeting('مرحباً يا بطل،')
                    ->line("مستشفى {$hospitalName} في حاجة ماسة لمتبرعين بفصيلة دم {$bloodType}.")
                    ->line("مستوى الحالة: " . strtoupper($this->bloodRequest->emergency_level))
                    ->action('عرض التفاصيل والتبرع', url("/emergency/{$this->bloodRequest->id}"))
                    ->line('تذكر: كل دقيقة قد تنقذ حياة.');
    }

    /**
     * Get the array representation of the notification (Database).
     */
    public function toArray(object $notifiable): array
    {
        $hospitalName = $this->bloodRequest->hospital->user->name ?? 'مستشفى';
        $bloodType = $this->bloodRequest->bloodType->name ?? '';

        return [
            'title' => '🚨 نداء طوارئ عاجل!',
            'body' => "مستشفى {$hospitalName} بحاجة ماسة لـ {$this->bloodRequest->units_required} وحدات من فصيلة {$bloodType}.",
            'type' => 'emergency',
            'related_id' => $this->bloodRequest->id,
            'related_type' => get_class($this->bloodRequest),
            'emergency_level' => $this->bloodRequest->emergency_level,
        ];
    }
}