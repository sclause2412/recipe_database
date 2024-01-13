<div>
    <x-form wire:submit="saveRecipe">

        <div>
            <x-input label="{{ __('Name') }}" required wire:model="name" />
        </div>

        <div class="mt-4">
            <x-select :async-data="route('search', [
                'area' => 'recipe',
                'model' => 'category',
            ])" hide-empty-message label="{{ __('Category') }}" option-label="name"
                option-value="id" required wire:model="category">
            </x-select>
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Cooked') }}" wire:model="cooked" />
        </div>

        <div class="mt-4">
            <x-input label="{{ __('Source') }}" wire:model="source" />
        </div>

        <div class="mt-4">
            <x-number label="{{ __('Portions') }}" min="0" wire:model="portions" />
        </div>

        <div class="mt-4">
            <x-number hint="{{ __('in minutes') }}" label="{{ __('Time') }}" min="0" wire:model="time" />
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
