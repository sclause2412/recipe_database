<?php

namespace App\View\Components;

use App\Http\Controllers\GlobalSettingsController;
use Illuminate\View\Component;
use Illuminate\View\View;

class AppLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        $colors = [];
        $color_light = GlobalSettingsController::get('color.light', true);
        $color_dark = GlobalSettingsController::get('color.dark', true);
        if ($color_light && $color_dark) {
            array_push($colors, 'system');
        }
        if ($color_light) {
            array_push($colors, 'light');
        }
        if ($color_dark) {
            array_push($colors, 'dark');
        }

        $fonts = [];
        if (GlobalSettingsController::get('font.sans', true)) {
            array_push($fonts, 'sans');
        }
        if (GlobalSettingsController::get('font.serif', true)) {
            array_push($fonts, 'serif');
        }
        if (GlobalSettingsController::get('font.mono', true)) {
            array_push($fonts, 'mono');
        }

        return view('layouts.app', ['colors' => $colors, 'fonts' => $fonts]);
    }
}
