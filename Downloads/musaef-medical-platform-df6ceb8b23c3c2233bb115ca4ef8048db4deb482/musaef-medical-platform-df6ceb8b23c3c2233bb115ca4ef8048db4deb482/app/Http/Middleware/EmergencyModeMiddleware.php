<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmergencyModeMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hospital = $request->user()->hospital ?? null;

        // نفترض وجود حقل is_emergency_mode في جدول المستشفيات
        if (!$hospital || !$hospital->is_emergency_mode) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الإجراء محظور. يتطلب تفعيل وضع "الطوارئ القصوى" أولاً.'
            ], 403);
        }

        return $next($request);
    }
}