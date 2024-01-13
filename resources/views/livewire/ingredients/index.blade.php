<div class="space-y-2">
    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Name') }}</x-table.head>
            <x-table.head>{{ __('Recipes') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($ingredients as $ingredient)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{{ $ingredient->name }}</x-table.cell>
                <x-table.cell>{{ $ingredient->recipes->count() }}</x-table.cell>
                <x-table.cell>
                    <div class="flex justify-end space-x-2 text-lg">
                        @if (check_write('recipe'))
                            <x-button icon="pencil" secondary title="{{ __('Edit') }}"
                                wire:click="editIngredient('{{ $ingredient->id }}')" />
                            <x-deletebutton icon wire:click="deleteIngredient('{{ $ingredient->id }}')" />
                        @endif
                        <x-link button icon="eye" route="ingredients.show,{{ $ingredient->id }}"
                            title="{{ __('Show') }}" />

                    </div>
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="3">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>
    {{ $ingredients->links() }}

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != '');">
        <x-form wire:submit="saveIngredient">

            <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add ingredient') }}</div>
            <div class="mb-2 text-lg font-medium" x-cloak x-show="edit">{{ __('Edit ingredient') }}</div>

            <input type="hidden" wire:model="rid" />

            <div class="">
                <x-input label="{{ __('Name') }}" required wire:model="name" />
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
