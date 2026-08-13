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
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى تسجيل الدخول أولاً لتنفيذ هذا الإجراء.'
            ], 401);
        }

        $user = $request->user();
        $hospital = $user?->hospital;

        $isEmergencyActive = $hospital ? (bool) $hospital->is_emergency_mode : false;

        if (!$hospital || !$isEmergencyActive) {
            return response()->json([
                'success' => false,
                'message' => 'هذا الإجراء محظور. يتطلب تفعيل وضع "الطوارئ القصوى" أولاً.'
            ], 403);
        }

        return $next($request);
    }
}
