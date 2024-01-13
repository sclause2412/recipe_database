<x-app-layout>
    <x-slot name="title">Projects</x-slot>
    <x-page-card>
        @livewire('projects.index')

        @if (check_write('project'))
            <x-link button icon="plus" route="projects.create">{{ __('Create project') }}</x-link>
            <x-link button icon="copy" route="projects.copy">{{ __('Copy project') }}</x-link>
        @endif
    </x-page-card>

</x-app-layout>
