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
        if (!auth()->check() || auth()->user()->role !== UserRole::ADMIN->value) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بالوصول. هذا المسار مخصص لمديري النظام فقط.'
            ], 403); // 403 Forbidden
        }

        return $next($request);
    }
}