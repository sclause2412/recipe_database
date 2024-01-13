<x-app-layout>
    <x-slot name="title">{{ __('Create project') }}</x-slot>
    <x-slot name="subtitle"></x-slot>
    <x-slot name="nav">
        <x-link route="projects.index">{{ __('Projects') }}</x-link> &gt; <x-link route="projects.create">
            {{ __('Create') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('projects.create')

    </x-page-card>

</x-app-layout>
