<div>
    <x-form wire:submit="run">

        <div>
            <x-select :options="[
                ['name' => __('status.All'), 'id' => 'A'],
                ['name' => __('status.Open'), 'id' => 'O'],
                ['name' => __('status.Done'), 'id' => 'D'],
            ]" label="{{ __('Export only with status') }}" option-label="name" option-value="id"
                wire:model="status" />
        </div>

        <div class="mt-4">
            <x-select :options="[['name' => __('Yes'), 'id' => 'Y'], ['name' => __('No'), 'id' => 'N']]" label="{{ __('Export status column') }}" option-label="name" option-value="id"
                wire:model="done" />
        </div>

        <div class="mt-4">
            <x-select :options="[['name' => __('Yes'), 'id' => 'Y'], ['name' => __('No'), 'id' => 'N']]" label="{{ __('Key as key') }}" option-label="name" option-value="id"
                wire:model="key" />
        </div>

        <div class="mt-4">
            <x-select :options="[
                ['name' => __('Grouped by group'), 'id' => 'G'],
                ['name' => __('Flat with group'), 'id' => 'L'],
                ['name' => __('Flat without group (dot-style)'), 'id' => 'D'],
            ]" label="{{ __('Export type') }}" option-label="name" option-value="id"
                wire:model="type" />
        </div>

        <div class="mt-4">
            <x-select :options="[['name' => __('Yes'), 'id' => 'Y'], ['name' => __('No'), 'id' => 'N']]"
                hint="{{ __('This will create key:value pairs instead of key:{value:value} if there is only a value. This works only if Key as key is used, status column is not exported and export type is grouped or dot-style.') }}"
                label="{{ __('Simplify output') }}" option-label="name" option-value="id" wire:model="simple" />
        </div>

        <div class="buttonrow mt-4">
            <x-button primary spinner type="submit">
                {{ __('Export') }}
            </x-button>
        </div>
    </x-form>
</div>
