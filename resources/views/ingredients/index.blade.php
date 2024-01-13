<x-app-layout>
    <x-slot name="title">{{ __('Ingredients') }}</x-slot>
    <x-slot name="nav">
        <x-link route="ingredients.index">{{ __('Ingredients') }}</x-link>
    </x-slot>
    <x-page-card>

        @livewire('ingredients.index')

    </x-page-card>

</x-app-layout>
