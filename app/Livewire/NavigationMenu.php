<?php

namespace App\Livewire;

use App\Actions\Livewire\CleanupInput;
use App\Http\Controllers\GlobalSettingsController;
use App\Http\Controllers\TranslationController;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Settings;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Closure;

class NavigationMenu extends Component
{
    public function render()
    {

        $color = [
            'light' => GlobalSettingsController::get('color.light', true),
            'dark' => GlobalSettingsController::get('color.dark', true),
        ];
        $font = [
            'sans' => GlobalSettingsController::get('font.sans', true),
            'serif' => GlobalSettingsController::get('font.serif', true),
            'mono' => GlobalSettingsController::get('font.mono', true),
        ];

        $color_on = ($color['light'] ? 1 : 0) + ($color['dark'] ? 1 : 0) > 1;
        $font_on = ($font['sans'] ? 1 : 0) + ($font['serif'] ? 1 : 0) + ($font['mono'] ? 1 : 0) > 1;

        $current_locale = app()->getLocale();
        $available_locales = TranslationController::available_locales();

        return view('navigation-menu', [
            'color' => $color,
            'font' => $font,
            'color_on' => $color_on,
            'font_on' => $font_on,
            'current_locale' => $current_locale,
            'available_locales' => $available_locales
        ]);
    }
}
