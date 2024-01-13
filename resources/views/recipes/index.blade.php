<x-app-layout>
    <x-slot name="title">{{ __('Recipes') }}</x-slot>
    <x-page-card>
        @livewire('recipes.index')

        @if (check_write('recipe'))
            <x-link button icon="plus" route="recipes.create">{{ __('Create recipe') }}</x-link>
        @endif
    </x-page-card>

</x-app-layout>
