<x-app-layout>
    <x-slot name="title">{{ __('Global settings') }}</x-slot>
    <x-slot name="nav">
        <x-link route="globalsettings">{{ __('Global settings') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('global_settings')

    </x-page-card>

</x-app-layout>
