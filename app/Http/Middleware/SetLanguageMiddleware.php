<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLanguageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // 1. استخراج الترويسة أو بارامتر lang مع قيمة افتراضية 'ar'
        $rawLang = $request->header('Accept-Language') ?: $request->get('lang', 'ar');

        // 2. تنظيف القيمة في حال كانت الترويسة تحتوي على تفاصيل إضافية (مثل: en-US,en;q=0.9)
        if (str_contains($rawLang, ',')) {
            $rawLang = explode(',', $rawLang)[0];
        }
        $lang = strtolower(trim(substr($rawLang, 0, 2)));

        // 3. اعتماد اللغة   
        if (in_array($lang, ['ar', 'en'])) {
            App::setLocale($lang);
        } else {
            App::setLocale('ar');
        }

        return $next($request);
    }
}
