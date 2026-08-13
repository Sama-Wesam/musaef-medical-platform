<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class DonorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح بالوصول، يرجى تسجيل الدخول أولاً.'
            ], 401);
        }

        $user = auth()->user();
        $userRole = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;
        $roleValue = strtolower((string) $userRole);

        $donorRoles = [
            strtolower(UserRole::DONOR->value ?? 'donor'),
            'donor',
            'متبرع'
        ];

        if (!in_array($roleValue, $donorRoles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'هذا المسار مخصص للمتبرعين فقط.'
            ], 403);
        }

        return $next($request);
    }
}
