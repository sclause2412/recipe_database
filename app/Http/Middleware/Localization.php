<?php

namespace App\Http\Middleware;

use App\Http\Controllers\TranslationController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locales = TranslationController::available_locales();

        $locale = session('locale', $request->getPreferredLanguage($locales));

        if (!in_array($locale, $locales)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);
        return $next($request);
    }
}
