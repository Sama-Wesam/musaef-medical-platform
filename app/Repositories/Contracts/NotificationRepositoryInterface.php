<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface NotificationRepositoryInterface
{
    public function getUnreadNotificationsForUser(int $userId): Collection;
    public function getAllNotificationsForUser(int $userId, int $limit = 50): Collection;
    public function markAsRead(int $notificationId): bool;
    public function markAllAsReadForUser(int $userId): int;
}
