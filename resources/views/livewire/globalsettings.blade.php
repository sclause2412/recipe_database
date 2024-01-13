<div>
    <x-form wire:submit="saveSettings">

        <div class="font-md font-bold">{{ __('Color mode') }}</div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Light mode') }}" wire:model="color.light" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Dark mode') }}" wire:model="color.dark" />
        </div>

        <div class="font-md mt-4 font-bold">{{ __('Font style') }}</div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Sans serif') }}" wire:model="font.sans" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Serif') }}" wire:model="font.serif" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Monospace') }}" wire:model="font.mono" />
        </div>

        <div class="font-md mt-4 font-bold">{{ __('Registration') }}</div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Registration allowed') }}" wire:model="register" />
        </div>

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
