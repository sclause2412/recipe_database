<div class="space-y-2">
    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Reference') }}</x-table.head>
            <x-table.head>{{ __('Group') }}</x-table.head>
            <x-table.head>{{ __('Ingredient') }}</x-table.head>
            <x-table.head>{{ __('Amount') }}</x-table.head>
            <x-table.head>{{ __('Unit') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($ingredients as $ingredient)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell><x-badge teal>{{ $ingredient->reference }}</x-badge></x-table.cell>
                <x-table.cell>{{ $ingredient->group }}</x-table.cell>
                <x-table.cell>{{ $ingredient->ingredient->name }}</x-table.cell>
                <x-table.cell>{{ $ingredient->amount }}</x-table.cell>
                <x-table.cell>{{ $ingredient->unit?->unit }}</x-table.cell>
                <x-table.cell>
                    <div class="flex justify-end space-x-2 text-lg">
                        <x-button :disabled="$loop->first" icon="arrow-up" secondary title="{{ __('Up') }}"
                            wire:click="stepUp('{{ $ingredient->id }}')" />
                        <x-button :disabled="$loop->last" icon="arrow-down" secondary title="{{ __('Up') }}"
                            wire:click="stepDown('{{ $ingredient->id }}')" />
                        <x-button icon="pencil" primary title="{{ __('Edit') }}"
                            wire:click="editIngredient('{{ $ingredient->id }}')" />
                        <x-deletebutton icon wire:click="deleteIngredient('{{ $ingredient->id }}')" />
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

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != '');">
        <x-form wire:submit="saveIngredient">

            <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add ingredient') }}</div>
            <div class="mb-2 text-lg font-medium" x-cloak x-show="edit">{{ __('Edit ingredient') }}</div>

            <input type="hidden" wire:model="rid" />

            <div class="">
                <x-input hint="{{ __('optional') }}" label="{{ __('Group') }}" wire:model="group" />
            </div>
            <div class="mt-4">
                <x-select :async-data="route('search', [
                    'area' => 'recipe',
                    'model' => 'ingredient',
                ])" hide-empty-message label="{{ __('Ingredient') }}" option-label="name"
                    option-value="id" required wire:model="ingredient">
                </x-select>
            </div>
            <div class="mt-4">
                <x-number label="{{ __('Amount') }}" min="0" step="0.05" wire:model="amount" />
            </div>
            <div class="mt-4">
                <x-select :async-data="route('search', [
                    'area' => 'recipe',
                    'model' => 'unit',
                ])" hide-empty-message label="{{ __('Unit') }}" option-label="name"
                    option-value="id" wire:model="unit">
                </x-select>
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
