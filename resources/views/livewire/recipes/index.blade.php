    <div class="space-y-2">

        <x-input placeholder="{{ __('Search...') }}" wire:model.live="search" />

        <x-table>
            <x-slot name="header">
                <x-table.head :direction="$sort === 'name' ? $dir : null" sortable wire:click="sortBy('name')">{{ __('Name') }}</x-table.head>
                <x-table.head :direction="$sort === 'category' ? $dir : null" sortable wire:click="sortBy('category')">{{ __('Category') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'description' ? $dir : null" sortable wire:click="sortBy('description')">{{ __('Description') }}
                </x-table.head>
                <x-table.head :direction="$sort === 'cooked' ? $dir : null" sortable wire:click="sortBy('cooked')">{{ __('Cooked') }}
                </x-table.head>
                @if (check_read('recipe'))
                    <x-table.head :direction="$sort === 'active' ? $dir : null" sortable wire:click="sortBy('active')">{{ __('Active') }}
                    </x-table.head>
                @endif
                <x-table.head />
            </x-slot>
            @forelse ($recipes as $recipe)
                <x-table.row wire:loading.class.delay="opacity-50">
                    <x-table.cell class="align-top">{{ $recipe->name }}</x-table.cell>
                    <x-table.cell class="align-top">{{ $recipe->category?->name }}</x-table.cell>
                    <x-table.cell class="align-top">{!! text_format($recipe->description) !!}</x-table.cell>
                    <x-table.cell class="align-top">{{ $recipe->cooked ? __('Yes') : __('No') }}</x-table.cell>
                    @if (check_read('recipe'))
                        <x-table.cell class="align-top">{{ $recipe->active ? __('Yes') : __('No') }}</x-table.cell>
                    @endif
                    <x-table.cell buttons>
                        <x-link button icon="eye" route="recipes.show,{{ $recipe->slug }}"
                            title="{{ __('Show') }}" />
                        @if (check_write('recipe'))
                            <x-link button icon="pencil" route="recipes.edit,{{ $recipe->slug }}"
                                title="{{ __('Edit') }}" />
                        @endif
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
        {{ $recipes->links() }}
    </div>
