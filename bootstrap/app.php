<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php', // تمت الإضافة: مهم جداً للإشعارات المباشرة (Real-time)
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. تسجيل الـ Middleware بأسماء مستعارة لتسهيل استخدامها في ملفات مسارات الأدوار
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRoleMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'hospital' => \App\Http\Middleware\HospitalMiddleware::class,
            'donor' => \App\Http\Middleware\DonorMiddleware::class,
            'emergency.mode' => \App\Http\Middleware\EmergencyModeMiddleware::class,
        ]);

        // 2. تفعيل الـ Middleware الجديد على مسارات الـ API بالكامل وبشكل تلقائي
        $middleware->api(prepend: [
            \App\Http\Middleware\SetLanguageMiddleware::class, // إضافة متحكم اللغة هنا ليعمل في المقدمة
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // تخصيص شكل رسائل الخطأ لتكون JSON منسقة للـ API
        $exceptions->shouldRenderJsonWhen(function (\Illuminate\Http\Request $request, \Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });
    })->create();
