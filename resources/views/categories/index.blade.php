<x-app-layout>
    <x-slot name="title">{{ __('Categories') }}</x-slot>
    <x-slot name="nav">
        <x-link route="categories.index">{{ __('Categories') }}</x-link>
    </x-slot>
    <x-page-card>

        @livewire('categories.index')

    </x-page-card>

</x-app-layout>
