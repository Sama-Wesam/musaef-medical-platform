<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Enums\UserRole;

class HospitalMiddleware
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
        $userRole = $user->role instanceof \BackedEnum ? $user->role->value : $user->role;
        $roleValue = strtolower((string) $userRole);

        // تنظيف مصفوفة الأدوار والاكتفاء بالقيم البرمجية القياسية الإنجليزية
        $hospitalRoles = [
            strtolower(UserRole::HOSPITAL->value ?? 'hospital'),
            'hospital',
            'hospital_admin',
            'blood_bank'
        ];

        if (!in_array($roleValue, $hospitalRoles, true)) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، هذا المسار مخصص لحسابات المستشفيات وبنوك الدم فقط.'
            ], 403);
        }

        return $next($request);
    }
}
