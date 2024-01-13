<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    public function style($style)
    {
        $style = from_array(strtolower($style), ['system', 'light', 'dark']);
        session(['theme.style' => $style]);
        if (!is_null(Auth::user())) {
            Settings::set(Auth::user(), null, 'theme.style', $style);
        }
        return backorhome();
    }

    public function font($font)
    {
        $font = from_array(strtolower($font), ['sans', 'serif', 'mono'], 'sans');
        session(['theme.font' => $font]);
        if (!is_null(Auth::user())) {
            Settings::set(Auth::user(), null, 'theme.font', $font);
        }
        return backorhome();
    }

    public function language($locale)
    {
        $locale = from_array($locale, TranslationController::available_locales(), config('app.locale', config('app.fallback_locale', 'en')));
        session(['locale' => $locale]);
        if (!is_null(Auth::user())) {
            Settings::set(Auth::user(), null, 'locale', $locale);
        }
        return backorhome();
    }
    public static function load_user_settings()
    {
        if (is_null(Auth::user())) {
            return;
        }

        $style = Settings::get(Auth::user(), null, 'theme.style');
        $style = from_array(strtolower($style), ['system', 'light', 'dark']);
        session(['theme.style' => $style]);

        $font = Settings::get(Auth::user(), null, 'theme.font');
        $font = from_array(strtolower($font), ['sans', 'serif', 'mono'], 'sans');
        session(['theme.font' => $font]);

        $locale = Settings::get(Auth::user(), null, 'locale');
        $locale = from_array($locale, TranslationController::available_locales(), config('app.locale', config('app.fallback_locale', 'en')));
        session(['locale' => $locale]);

    }
}
