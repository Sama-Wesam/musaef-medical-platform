<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تسجيل الدخول أولاً للوصول إلى هذه البيانات.'
            ], 401);
        }

        $user = auth()->user();
        $userRole = $user->role;

        // دعم قراءة الـ Enum أو القيمة النصية بشكل آمن بجميع الحالات
        $roleValue = $userRole instanceof \BackedEnum ? $userRole->value : $userRole;
        $roleValue = strtolower((string) $roleValue);

        // الاعتماد على القيم الإنجليزية القياسية فقط بناءً على مبادئ Clean Code
        $adminRoles = [
            strtolower(UserRole::ADMIN->value ?? 'admin'),
            'admin',
            'administrator'
        ];

        if (!in_array($roleValue, $adminRoles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالوصول. هذا المسار مخصص لمديري النظام فقط.'
            ], 403);
        }

        return $next($request);
    }
}
