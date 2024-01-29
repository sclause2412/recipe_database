<div class="space-y-2">
    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Name') }}</x-table.head>
            <x-table.head>{{ __('Cooked') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($recipes as $recipe)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{{ $recipe->name }}</x-table.cell>
                <x-table.cell>{{ $recipe->cooked ? __('Yes') : __('No') }}</x-table.cell>
                <x-table.cell buttons>
                    <x-link button icon="eye" route="recipes.show,{{ $recipe->slug }}" title="{{ __('Show') }}" />
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
    {{ $recipes->links() }}

</div>
