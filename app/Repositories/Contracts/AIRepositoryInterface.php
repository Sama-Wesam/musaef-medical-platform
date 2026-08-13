<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Collection;

interface AIRepositoryInterface
{
    public function saveMatchingResults(array $results): bool;
    public function getTopMatchesForRequest(int $requestId, int $limit = 10): Collection;
    public function markMatchAsNotified(int $matchId): bool;
    public function clearOldResults(int $requestId): bool;
}
