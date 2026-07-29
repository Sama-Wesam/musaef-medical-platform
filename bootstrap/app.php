<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php', // مهم للإشعارات المباشرة Real-time
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. تفعيل Sanctum Stateful API للتعامل مع الجلسات والـ CORS لمشروع Vue (localhost:5173)
        $middleware->statefulApi();

        // 2. تسجيل الـ Middleware بأسماء مستعارة لحماية المسارات والأدوار
        $middleware->alias([
            'role'           => \App\Http\Middleware\CheckRoleMiddleware::class,
            'admin'          => \App\Http\Middleware\AdminMiddleware::class,
            'hospital'       => \App\Http\Middleware\HospitalMiddleware::class,
            'donor'          => \App\Http\Middleware\DonorMiddleware::class,
            'emergency.mode' => \App\Http\Middleware\EmergencyModeMiddleware::class,
        ]);

        // 3. تحديد اللغة تلقائياً لطلبات الـ API (HandleCors يعالج تلقائياً في Laravel 11)
        $middleware->api(prepend: [
            \App\Http\Middleware\SetLanguageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // تخصيص استجابة الأخطاء لتكون صيغة JSON منسقة للـ API
        $exceptions->shouldRenderJsonWhen(function (\Illuminate\Http\Request $request, \Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });
    })->create();
