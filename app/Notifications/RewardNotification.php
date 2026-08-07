<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Reward;

class RewardNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pointsEarned;
    public $reward;
    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(int $pointsEarned, string $message, ?Reward $reward = null)
    {
        $this->pointsEarned = $pointsEarned;
        $this->message = $message;
        $this->reward = $reward;
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
        $title = $this->reward ? '🏆 حصلت على وسام جديد!' : '🎉 أحسنت صنعاً!';
        $body = $this->message . " لقد حصلت على {$this->pointsEarned} نقطة.";

        if ($this->reward) {
            $body .= " وتم منحك وسام: {$this->reward->name}.";
        }

        return [
            'title' => $title,
            'body' => $body,
            'type' => 'reward',
            'points' => $this->pointsEarned,
            'reward_id' => $this->reward ? $this->reward->id : null,
        ];
    }
}