<x-app-layout>
    <x-slot name="title">{{ __('Copy project') }}</x-slot>
    <x-slot name="subtitle"></x-slot>
    <x-slot name="nav">
        <x-link route="projects.index">{{ __('Projects') }}</x-link> &gt; <x-link route="projects.copy">
            {{ __('Copy') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('projects.copy')

    </x-page-card>

</x-app-layout>
