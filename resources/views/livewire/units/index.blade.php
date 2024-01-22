<div class="space-y-2">

    <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />

    <x-table>
        <x-slot name="header">
            <x-table.head :direction="$sort === 'name' ? $dir : null" sortable wire:click="sortBy('name')">{{ __('Name') }}</x-table.head>
            <x-table.head :direction="$sort === 'unit' ? $dir : null" sortable wire:click="sortBy('unit')">{{ __('Unit') }}</x-table.head>
            <x-table.head :direction="$sort === 'fraction' ? $dir : null" sortable wire:click="sortBy('fraction')">{{ __('Fraction') }}</x-table.head>
            <x-table.head :direction="$sort === 'recipes' ? $dir : null" sortable wire:click="sortBy('recipes')">{{ __('Recipes') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($units as $unit)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{{ $unit->name }}</x-table.cell>
                <x-table.cell>{{ $unit->unit }}</x-table.cell>
                <x-table.cell>{{ $unit->fraction ? __('Yes') : __('No') }}</x-table.cell>
                <x-table.cell>{{ $unit->recipes->count() }}</x-table.cell>
                <x-table.cell>
                    <div class="flex justify-end space-x-2 text-lg">
                        @if (check_write('recipe'))
                            <x-button icon="pencil" secondary title="{{ __('Edit') }}"
                                wire:click="editUnit('{{ $unit->id }}')" />
                            <x-deletebutton icon wire:click="deleteUnit('{{ $unit->id }}')" />
                        @endif
                        <x-link button icon="eye" route="units.show,{{ $unit->id }}"
                            title="{{ __('Show') }}" />
                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="5">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>
    {{ $units->links() }}

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != '');">
        <x-form wire:submit="saveUnit">

            <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add unit') }}</div>
            <div class="mb-2 text-lg font-medium" x-cloak x-show="edit">{{ __('Edit unit') }}</div>

            <input type="hidden" wire:model="rid" />

            <div class="">
                <x-input label="{{ __('Name') }}" required wire:model="name" />
            </div>

            <div class="mt-4">
                <x-input label="{{ __('Unit') }}" required wire:model="unit">
                    <x-slot name="hint">
                        {!! __(
                            'To use pluralization you can divide the units by [n] where n is the filter (e.g. <1 means smaller than one, 0 means exactly zero, ...).',
                        ) !!}<br />
                        {!! __('Common pattern for fraction is [0]plural[<=1]singular[>1]plural') !!}<br />
                        {!! __('Common pattern for decimal is [<1]plural[1]singular[>1]plural') !!}<br />
                        {!! __('Exact matches overwrite ranges, not found numbers are replaced by last entry.') !!}<br />
                        {{ __('Example:') }} {{ '[>0]a[10]b' }}<br />
                        0 b<br />
                        1 a<br />
                        9 a<br />
                        10 b<br />
                        11 a
                    </x-slot>
                </x-input>
            </div>

            <div class="mt-4">
                <x-checkbox label="{{ __('Fraction') }}" wire:model="fraction">
                    <x-slot name="description">
                        {!! str_replace(
                            '1/4',
                            '<span class="diagonal-fractions">1/4</span>',
                            e(__('If this option is set the display of the recipe will try to convert numbers to fractions (e.g. 0.25 = 1/4)')),
                        ) !!}
                    </x-slot>
                </x-checkbox>
            </div>

            <div class="buttonrow mt-4">
                <x-button icon="plus" secondary x-cloak x-on:click.prevent="$wire.rid=''" x-show="edit">
                    {{ __('New') }}
                </x-button>

                <x-button primary type="submit">
                    {{ __('Save') }}
                </x-button>
            </div>
        </x-form>
    </div>

</div>
