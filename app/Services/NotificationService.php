<?php

namespace App\Services;

use App\Repositories\Contracts\NotificationRepositoryInterface;

class NotificationService
{
    public function __construct(
        protected NotificationRepositoryInterface $notificationRepo
    ) {}

    public function getUserUnreadNotifications(int $userId)
    {
        return $this->notificationRepo->getUnreadNotificationsForUser($userId);
    }

    public function markAsRead(int $notificationId)
    {
        return $this->notificationRepo->markAsRead($notificationId);
    }

    public function markAllAsRead(int $userId)
    {
        return $this->notificationRepo->markAllAsReadForUser($userId);
    }
}
