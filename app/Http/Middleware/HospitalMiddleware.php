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
        if (!auth()->check() || auth()->user()->role !== UserRole::HOSPITAL->value) {
            return response()->json([
                'success' => false,
                'message' => 'عذراً، هذا المسار مخصص لحسابات المستشفيات وبنوك الدم فقط.'
            ], 403);
        }

        return $next($request);
    }
}