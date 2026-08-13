<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    // 1. تسجيل وربط مجلد الأوامر المخصصة app/Console/Commands تلقائياً
    ->withCommands([
        __DIR__.'/../app/Console/Commands',
    ])
    // 2. ربط الـ Scheduler لإدارة وجدولة المهام الدورية (بديل Kernel.php)
    ->withSchedule(function (Schedule $schedule): void {
        // يمكنك إدراج المهام المجدولة هنا أو استدعاء الأوامر المخصصة:
        // $schedule->command('musaef:clear-old-requests')->daily();
        // $schedule->command('musaef:ai-predict-demand')->hourly();
    })
    ->withMiddleware(function (Middleware $middleware): void {
        // 1. تفعيل Sanctum Stateful API للتعامل مع الجلسات و SPA
        $middleware->statefulApi();

        // 2. استثناء مسارات الـ API من فحص رموز الـ CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);

        // 3. تسجيل الـ Middleware بأسماء مستعارة
        $middleware->alias([
            'role'           => \App\Http\Middleware\CheckRoleMiddleware::class,
            'admin'          => \App\Http\Middleware\AdminMiddleware::class,
            'hospital'       => \App\Http\Middleware\HospitalMiddleware::class,
            'donor'          => \App\Http\Middleware\DonorMiddleware::class,
            'emergency.mode' => \App\Http\Middleware\EmergencyModeMiddleware::class,
        ]);

        // 4. تحديد اللغة تلقائياً لطلبات الـ API
        $middleware->api(prepend: [
            \App\Http\Middleware\SetLanguageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // 1. منع إرجاع صفحات HTML عند فشل التوثيق في مسارات الـ API
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح بالوصول، يرجى إعادة تسجيل الدخول.'
                ], 401);
            }
        });

        // 2. التقاط جميع الاستثناءات لمسارات API وإرجاع JSON سليم مع CORS Headers
        $exceptions->render(function (\Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage() ?: 'حدث خطأ غير متوقع في السيرفر',
                    'error'   => config('app.debug') ? [
                        'exception' => get_class($e),
                        'file'      => $e->getFile(),
                        'line'      => $e->getLine(),
                        'trace'     => $e->getTraceAsString(),
                    ] : null
                ], $statusCode);
            }
        });

        // ضمان إرجاع استجابة JSON دائماً لمسارات الـ API
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });
    })->create();
