<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLanguageMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // قراءة اللغة المرسلة في الـ Header (مثلاً: ar أو en)
        // إذا لم يتم إرسال لغة، يتم اعتماد اللغة العربية الافتراضية للمشروع
        $locale = $request->header('Accept-Language', config('app.locale', 'ar'));

        if (in_array($locale, ['ar', 'en'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}
