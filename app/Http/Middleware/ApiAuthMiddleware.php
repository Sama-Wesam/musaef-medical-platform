<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiAuthMiddleware
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

        // التحقق من حالة الحساب إذا كان محظوراً أو معلقاً
        if (isset($user->status) && in_array($user->status, ['suspended', 'suspended_ai', 'blocked', 'inactive'], true)) {
            return response()->json([
                'success' => false,
                'message' => 'حسابك معلق حالياً أو تحت المراجعة. يرجى التواصل مع إدارة المنصة.'
            ], 403);
        }

        return $next($request);
    }
}
