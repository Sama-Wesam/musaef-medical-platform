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
        if (!auth()->check() || !in_array(auth()->user()->role, $roles)) {
            return response()->json([
                'success' => false,
                'message' => 'لا تملك الصلاحيات الكافية لتنفيذ هذا الإجراء.'
            ], 403);
        }

        return $next($request);
    }
}