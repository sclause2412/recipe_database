<div>
    <x-form wire:submit="saveProject">

        <div>
            <x-select :options="$projects" label=" {{ __('Source project') }}" option-label="name" option-value="id" required
                wire:model.live="source" />
        </div>

        <div class="mt-4">
            <x-input autocomplete="off" label=" {{ __('Project name') }}" required wire:model="name" />
        </div>

        <div class="mt-4">
            <x-input autocomplete="off" hint="{{ __('Only A-Z and 0-9 are allowed.') }}" label="{{ __('Short code') }}"
                maxlength="10" required wire:model="short" />
        </div>

        <div class="mt-4">
            <x-textarea label="{{ __('Description') }}" wire:model="description" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Active') }}" wire:model="active" />
        </div>

        <div class="mt-4 border-b border-gray-400 dark:border-gray-600"></div>

        <div class="md:grid md:grid-flow-row md:grid-cols-2 md:gap-2">

            <div class="mt-4">
                <x-checkbox label="{{ __('Rights') }}" wire:model="copy_rights" />
            </div>

            <div class="mt-4">
                <x-checkbox label="{{ __('Public registration settings') }}" wire:model="copy_project_registrations" />
            </div>

            <div class="mt-4">
                <x-checkbox label="{{ __('Stations') }}" wire:model="copy_stations" />
            </div>
        </div>

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
