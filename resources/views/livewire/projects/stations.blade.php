<div class="space-y-2">
    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Name') }}</x-table.head>
            <x-table.head>{{ __('Short') }}</x-table.head>
            <x-table.head>{{ __('Color') }}</x-table.head>
            <x-table.head>{{ __('Active') }}</x-table.head>
            @if ($edit)
                <x-table.head />
            @endif
        </x-slot>
        @forelse ($stations as $station)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{{ $station->name }}</x-table.cell>
                <x-table.cell>{{ $station->short }}</x-table.cell>
                <x-table.cell>
                    <div class="rounded-md"
                        style="color: #{{ $station->color }}; background-color: #{{ $station->color }};">
                        {{ $station->color }}</div>
                </x-table.cell>
                <x-table.cell>{{ $station->active ? __('Yes') : __('No') }}</x-table.cell>
                @if ($edit)
                    <x-table.cell>
                        <div class="flex space-x-2 text-lg">
                            <x-button icon="pencil" secondary title="{{ __('Edit') }}"
                                wire:click="editStation('{{ $station->id }}')" />
                            <x-deletebutton icon wire:click="deleteStation('{{ $station->id }}')" />
                        </div>
                    </x-table.cell>
                @endif
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="4">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>
    {{ $stations->links() }}
    @if ($edit)
        <div x-data="{ edit: false }" x-init="$watch('$wire.id', (value, ov) => edit = value != '');">
            <x-form wire:submit="saveStation">

                <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add station') }}</div>
                <div class="mb-2 text-lg font-medium" x-show="edit">{{ __('Edit station') }}</div>

                <input type="hidden" wire:model="id" />

                <div class="">
                    <x-input label="{{ __('Name') }}" wire:model="name" />
                </div>

                <div class="mt-4">
                    <x-input label="{{ __('Short code') }}" wire:model="short" />
                </div>

                <div class="mt-4">
                    <x-color-picker :colors="default_colors()" label="{{ __('Color') }}" wire:model="color" />
                </div>

                <div class="mt-4">
                    <x-textarea label="{{ __('Description') }}" wire:model="description" />
                </div>

                <div class="mt-4">
                    <x-checkbox label="{{ __('Active') }}" wire:model="active" />
                </div>

                <div class="buttonrow mt-4">
                    <x-button icon="plus" secondary x-on:click.prevent="$wire.id=''" x-show="edit">
                        {{ __('New') }}
                    </x-button>

                    <x-button primary type="submit">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </x-form>
        </div>
    @endif
</div>
