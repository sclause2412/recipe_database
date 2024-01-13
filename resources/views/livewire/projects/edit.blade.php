<div>
    <x-form wire:submit="saveProject">

        <div>
            <x-input label="{{ __('Project name') }}" required wire:model="name" />
        </div>

        <div class="mt-4">
            <x-input hint="{{ __('Only A-Z and 0-9 are allowed.') }}" label="{{ __('Short code') }}" maxlength="10"
                required wire:model="short" />
        </div>

        <div class="mt-4">
            <x-textarea label="{{ __('Description') }}" wire:model="description" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Active') }}" wire:model="active" />
        </div>

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
