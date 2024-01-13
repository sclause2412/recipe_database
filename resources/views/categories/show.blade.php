<x-app-layout>
    <x-slot name="title">{{ __('Category details') }}</x-slot>
    <x-slot name="subtitle">{{ $category->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="categories.index">{{ __('Categories') }}</x-link> &gt; <x-link
            route="categories.show,{{ $category->id }}">
            {{ __('Details') }}</x-link>
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Category information') }}
        </x-slot>

        <x-list>
            <x-list.item>
                <x-slot name="term">{{ __('Name') }}</x-slot>
                {{ $category->name }}
            </x-list.item>
        </x-list>
    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Recipes') }}
        </x-slot>

        @livewire('categories.recipes', ['category' => $category])

    </x-page-card>

</x-app-layout>
