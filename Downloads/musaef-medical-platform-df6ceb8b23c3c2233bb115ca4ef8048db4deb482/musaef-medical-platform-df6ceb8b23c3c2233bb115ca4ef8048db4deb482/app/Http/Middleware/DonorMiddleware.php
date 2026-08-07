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
        if (!auth()->check() || auth()->user()->role !== UserRole::DONOR->value) {
            return response()->json([
                'success' => false,
                'message' => 'هذا المسار مخصص للمتبرعين فقط.'
            ], 403);
        }

        return $next($request);
    }
}