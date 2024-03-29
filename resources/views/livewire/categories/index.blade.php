<div class="space-y-2">

    <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />

    <x-table>
        <x-slot name="header">
            <x-table.head :direction="$sort === 'name' ? $dir : null" sortable wire:click="sortBy('name')">{{ __('Name') }}</x-table.head>
            <x-table.head :direction="$sort === 'recipes' ? $dir : null" sortable wire:click="sortBy('recipes')">{{ __('Recipes') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($categories as $category)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{{ $category->name }}</x-table.cell>
                <x-table.cell>{{ $category->recipes->count() }}</x-table.cell>
                <x-table.cell buttons>
                    @if (check_write('recipe'))
                        <x-button icon="pencil" secondary title="{{ __('Edit') }}"
                            wire:click="editCategory('{{ $category->id }}')" />
                        <x-deletebutton icon wire:click="deleteCategory('{{ $category->id }}')" />
                    @endif
                    <x-link button icon="eye" route="categories.show,{{ $category->id }}"
                        title="{{ __('Show') }}" />
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
    {{ $categories->links() }}

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != '');">
        <x-form wire:submit="saveCategory">

            <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add category') }}</div>
            <div class="mb-2 text-lg font-medium" x-cloak x-show="edit">{{ __('Edit category') }}</div>

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
