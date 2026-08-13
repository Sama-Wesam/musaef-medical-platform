<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class CleanExpiredRequestsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * إعدادات إعادة المحاولة والمهلة الزمنية
     */
    public int $tries = 3;
    public array $backoff = [10, 30];
    public int $timeout = 60;

    public function handle(): void
    {
        Log::info('Starting CleanExpiredRequestsJob background process...');

        try {
            // استدعاء الـ Command الخاص بتنظيف طلبات الطوارئ المنتهية
            Artisan::call('emergencies:clean');
            Log::info('CleanExpiredRequestsJob completed successfully.');
        } catch (\Throwable $e) {
            Log::error("Error in CleanExpiredRequestsJob: {$e->getMessage()}");
            throw $e;
        }
    }
}
