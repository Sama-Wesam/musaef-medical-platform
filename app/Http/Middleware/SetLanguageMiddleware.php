<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->header('Accept-Language') ?: $request->get('lang', 'ar');

        if (in_array($lang, ['ar', 'en'])) {
            App::setLocale($lang);
        } else {
            App::setLocale('ar');
        }

        return $next($request);
    }
}
