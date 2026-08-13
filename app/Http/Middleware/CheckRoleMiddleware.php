<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     * يمكن تمرير الأدوار هكذا في الـ Routes: middleware('role:admin,hospital')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تسجيل الدخول أولاً للوصول إلى هذه البيانات.'
            ], 401);
        }

        $userRole = auth()->user()->role;
        $roleValue = $userRole instanceof \BackedEnum ? $userRole->value : $userRole;
        $roleValue = strtolower((string) $roleValue);

        // تحويل الأدوار الممررة للـ Middleware إلى حروف صغيرة للمطابقة المرنة
        $allowedRoles = array_map(fn($r) => strtolower(trim($r)), $roles);

        if (!in_array($roleValue, $allowedRoles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'لا تملك الصلاحيات الكافية لتنفيذ هذا الإجراء.'
            ], 403);
        }

        return $next($request);
    }
}
