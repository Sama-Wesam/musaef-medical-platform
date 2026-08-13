<?php

namespace App\Notifications;

use App\Models\Reward;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RewardNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public int $pointsEarned,
        public string $message,
        public ?Reward $reward = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $titleKey = $this->reward ? 'notifications.reward_badge_title' : 'notifications.reward_points_title';
        $bodyKey = $this->reward ? 'notifications.reward_badge_body' : 'notifications.reward_points_body';

        return [
            'title_key'   => $titleKey,
            'body_key'    => $bodyKey,
            'body_params' => [
                'message'      => $this->message,
                'points'       => $this->pointsEarned,
                'reward_name'  => $this->reward?->name ?? ''
            ],
            'type'        => 'reward',
            'points'      => $this->pointsEarned,
            'reward_id'   => $this->reward?->id,
        ];
    }
}
