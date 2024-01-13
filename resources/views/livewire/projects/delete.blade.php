<div>
    <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
        {{ __('Once the project is deleted, all of its resources and data will be permanently deleted.') }}
    </div>

    <div class="buttonrow">

        <x-deletebutton wire:click="deleteProject">
            {{ __('Delete project') }}
        </x-deletebutton>

    </div>
</div>
