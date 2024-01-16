<div>
    <x-form wire:submit="run">

        <div>
            <x-input label="{{ __('JSON file') }}" type="file" wire:model="file" />
        </div>

        <div class="mt-4">
            <x-select :options="[
                ['name' => __('status.ReadFromFile'), 'id' => 'F'],
                ['name' => __('status.Open'), 'id' => 'O'],
                ['name' => __('status.Done'), 'id' => 'D'],
            ]" label="{{ __('Import status as') }}" option-label="name" option-value="id"
                wire:model="status" />
        </div>

        <div class="mt-4">
            <x-select :options="[
                ['name' => __('Import all lines (without overwrite)'), 'id' => 'I'],
                ['name' => __('Import all lines (with overwrite)'), 'id' => 'O'],
                ['name' => __('Update existing keys (only if empty)'), 'id' => 'E'],
                ['name' => __('Update existing keys (all, with overwrite)'), 'id' => 'U'],
            ]" label="{{ __('Import behavior') }}" option-label="name" option-value="id"
                wire:model="mode" />
        </div>

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Import') }}
            </x-button>
        </div>
    </x-form>
</div>
