<x-app-layout>
    <x-slot name="title">{{ __('Ingredient details') }}</x-slot>
    <x-slot name="subtitle">{{ $ingredient->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="ingredients.index">{{ __('Ingredients') }}</x-link> &gt; <x-link
            route="ingredients.show,{{ $ingredient->id }}">
            {{ __('Details') }}</x-link>
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Ingredient information') }}
        </x-slot>

        <x-list>
            <x-list.item>
                <x-slot name="term">{{ __('Name') }}</x-slot>
                {{ $ingredient->name }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Information') }}</x-slot>
                {{ $ingredient->info }}
            </x-list.item>
        </x-list>
    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Recipes') }}
        </x-slot>

        @livewire('ingredients.recipes', ['ingredient' => $ingredient])

    </x-page-card>

</x-app-layout>
