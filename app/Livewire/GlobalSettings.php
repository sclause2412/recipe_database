<?php

namespace App\Livewire;

use App\Actions\Livewire\CleanupInput;
use App\Http\Controllers\GlobalSettingsController;
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

class GlobalSettings extends Component
{
    use CleanupInput;
    use WireUiActions;

    public $color = ['light' => true, 'dark' => true];
    public $font = ['sans' => true, 'serif' => true, 'mono' => true];
    public $register = true;

    public function mount()
    {
        $this->color['light'] = GlobalSettingsController::get('color.light', true);
        $this->color['dark'] = GlobalSettingsController::get('color.dark', true);
        $this->font['sans'] = GlobalSettingsController::get('font.sans', true);
        $this->font['serif'] = GlobalSettingsController::get('font.serif', true);
        $this->font['mono'] = GlobalSettingsController::get('font.mono', true);
        $this->register = GlobalSettingsController::get('register', true);
    }

    public function render()
    {
        return view('livewire.globalsettings');
    }

    public function saveSettings()
    {
        if (!is_elevated())
            abort(403);

        $this->color = $this->cleanInput($this->color);
        $this->font = $this->cleanInput($this->font);
        $this->register = $this->cleanInput($this->register);

        $this->validate([
            'color.*' => [
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!($this->color['light'] || $this->color['dark']))
                        $fail(__('At least one color must be selected.'));
                }
            ],
            'font.*' => [
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!($this->font['sans'] || $this->font['serif'] || $this->font['mono']))
                        $fail(__('At least one font must be selected.'));
                }
            ],
        ]);

        GlobalSettingsController::set('color.light', $this->color['light'] ? true : false);
        GlobalSettingsController::set('color.dark', $this->color['dark'] ? true : false);
        GlobalSettingsController::set('font.sans', $this->font['sans'] ? true : false);
        GlobalSettingsController::set('font.serif', $this->font['serif'] ? true : false);
        GlobalSettingsController::set('font.mono', $this->font['mono'] ? true : false);
        GlobalSettingsController::set('register', $this->register ? true : false);

        $this->notification()->success(__('Settings saved'), __('The settings were successfully saved'));
        $this->dispatch('refresh-navigation-menu');
    }
}
