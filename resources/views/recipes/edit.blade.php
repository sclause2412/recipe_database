<x-app-layout>
    <x-slot name="title">{{ __('Edit recipe') }}</x-slot>
    <x-slot name="subtitle">{{ $recipe->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="recipes.index">{{ __('Recipes') }}</x-link> &gt; <x-link route="recipes.edit,{{ $recipe->slug }}">
            {{ __('Edit') }}</x-link>
        <x-link button class="ml-4" route="recipes.show,{{ $recipe->slug }}" sm>
            {{ __('Details') }}</x-link>
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Recipe information') }}
        </x-slot>

        @livewire('recipes.edit', ['recipe' => $recipe])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Ingredients') }}
        </x-slot>

        @livewire('recipes.ingredients', ['recipe' => $recipe])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Steps') }}
        </x-slot>

        @livewire('recipes.steps', ['recipe' => $recipe])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Comments') }}
        </x-slot>

        @livewire('recipes.comments', ['recipe' => $recipe])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Copy / delete recipe') }}
        </x-slot>

        @livewire('recipes.copydelete', ['recipe' => $recipe])

    </x-page-card>

</x-app-layout>
