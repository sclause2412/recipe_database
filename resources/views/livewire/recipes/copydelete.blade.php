<div>

    <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        {{ __('Once the recipe is deleted, it cannot be restored.') }}
    </div>

    <div class="buttonrow">

        <x-button primary wire:click="copyRecipe">
            {{ __('Copy recipe') }}
        </x-button>

        <x-deletebutton wire:click="deleteRecipe">
            {{ __('Delete recipe') }}
        </x-deletebutton>

    </div>
</div>
