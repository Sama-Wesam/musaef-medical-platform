<?php

namespace App\Services;

use App\Repositories\NotificationRepository;

class NotificationService
{
    protected $notificationRepo;

    public function __construct(NotificationRepository $notificationRepo)
    {
        $this->notificationRepo = $notificationRepo;
    }

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