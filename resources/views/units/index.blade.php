<x-app-layout>
    <x-slot name="title">{{ __('Units') }}</x-slot>
    <x-slot name="nav">
        <x-link route="units.index">{{ __('Units') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('units.index')

    </x-page-card>

</x-app-layout>
