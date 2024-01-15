<div class="space-y-2">

    <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />
    <x-select :options="[
        ['name' => __('status.All'), 'id' => 'A'],
        ['name' => __('status.Open'), 'id' => 'O'],
        ['name' => __('status.Done'), 'id' => 'D'],
    ]" option-label="name" option-value="id" wire:model.live="filter" />

    <x-table>
        <x-slot name="header">
            <x-table.head :direction="$sort === 'group' ? $dir : null" sortable wire:click="sortBy('group')">{{ __('Group') }}</x-table.head>
            <x-table.head :direction="$sort === 'key' ? $dir : null" sortable wire:click="sortBy('key')">{{ __('Key') }}</x-table.head>
            <x-table.head :direction="$sort === 'value' ? $dir : null" sortable wire:click="sortBy('value')">{{ __('Translation') }}</x-table.head>
            <x-table.head :direction="$sort === 'done' ? $dir : null" sortable wire:click="sortBy('done')">{{ __('status.Done') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($entries as $entry)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell class="align-top">{{ $entry->group }}</x-table.cell>
                <x-table.cell class="align-top">{{ $entry->key }}</x-table.cell>
                <x-table.cell class="align-top">{{ $entry->value }}</x-table.cell>
                <x-table.cell class="align-top">{{ $entry->done ? __('Yes') : __('No') }}</x-table.cell>
                <x-table.cell>
                    <div class="flex justify-end space-x-2 text-lg">
                        @if (check_write('translate'))
                            @if (!$entry->done && is_null($entry->value))
                                <x-button icon="fast-forward" positive title="{{ __('Take over') }}"
                                    wire:click="fastEntry('{{ $entry->id }}')" />
                            @endif
                            <x-button icon="pencil" primary title="{{ __('Edit') }}"
                                wire:click="editEntry('{{ $entry->id }}')" />
                            <x-deletebutton icon wire:click="deleteEntry('{{ $entry->id }}')" />
                        @endif
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="10">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>
    {{ $entries->links() }}

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != null);">
        <x-form wire:submit="saveEntry" x-cloak x-show="edit">

            <div class="mb-2 text-lg font-medium">{{ __('Edit translation') }}</div>

            <input type="hidden" wire:model="rid" />

            <div class="">
                <x-textarea disabled label="{{ __('Key') }}" wire:model="key" />
            </div>

            <div class="mt-4">
                <x-textarea label="{{ __('Translation') }}" required wire:model="value" />
            </div>

            <div class="mt-4">
                <x-checkbox label="{{ __('status.Done') }}" wire:model="done" />
            </div>

            <div class="mt-4">
                <x-textarea disabled label="{{ __('Other languages') }}" wire:model="info" />
            </div>

            <div class="buttonrow mt-4">
                <x-button primary type="submit">
                    {{ __('Save') }}
                </x-button>
            </div>
        </x-form>
    </div>

</div>
