<x-app-layout>
    <x-slot name="title">{{ __('Create recipe') }}</x-slot>
    <x-slot name="subtitle"></x-slot>
    <x-slot name="nav">
        <x-link route="recipes.index">{{ __('Recipes') }}</x-link> &gt; <x-link route="recipes.create">
            {{ __('Create') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('recipes.create')

    </x-page-card>

</x-app-layout>
