<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait LocationTrait
{
    /**
     * Scope لجلب السجلات القريبة من إحداثيات معينة في نطاق دائرة معينة (بالكيلومتر)
     */
    public function scopeNearby(Builder $query, float|string $latitude, float|string $longitude, float|int $radiusKm = 10): Builder
    {
        $table = $query->getModel()->getTable();

        $haversine = "(6371 * acos(cos(radians(?))
                        * cos(radians({$table}.latitude))
                        * cos(radians({$table}.longitude) - radians(?))
                        + sin(radians(?))
                        * sin(radians({$table}.latitude))))";

        return $query->selectRaw("{$table}.*, {$haversine} AS distance", [$latitude, $longitude, $latitude])
                     ->havingRaw("distance < ?", [$radiusKm])
                     ->orderBy('distance');
    }
}
