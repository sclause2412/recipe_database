<div>
    <x-form wire:submit="saveRecipe">

        <div>

            <x-input class="hidden" label="{{ __('Picture') }}" type="file" wire:model="picture" x-ref="picture" />

            <div class="mt-2 flex flex-wrap items-center gap-4">
                @if ($actpicture)
                    <img class="h-20 w-60 cursor-pointer rounded-md object-cover" src="{{ $actpicture }}"
                        x-on:click.prevent="$refs.picture.click()">
                @endif
                @if ($picture && $actpicture)
                    <x-icon class="h-4 w-4" name="arrow-right" />
                @endif
                @if ($picture)
                    <img class="h-20 w-60 cursor-pointer rounded-md object-cover" src="{{ $picture->temporaryUrl() }}"
                        x-on:click.prevent="$refs.picture.click()">
                @endif
                <div class="text-yellow-700 dark:text-yellow-300" wire:loading wire:target="picture">
                    {{ __('Uploading...') }}</div>
            </div>

            <x-button class="mr-2 mt-2" secondary x-on:click.prevent="$refs.picture.click()">
                {{ __('Select a picture') }}
            </x-button>

            @isset($recipe)
                <x-button class="mt-2" secondary wire:click="deletePicture">
                    {{ __('Remove picture') }}
                </x-button>
            @endisset

        </div>

        <div class="mt-4">
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
