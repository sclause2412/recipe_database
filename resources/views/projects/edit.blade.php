<x-app-layout>
    <x-slot name="title">{{ __('Edit project') }}</x-slot>
    <x-slot name="subtitle">{{ $project->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="projects.index">{{ __('Projects') }}</x-link> &gt; <x-link
            route="projects.edit,{{ $project->id }}">
            {{ __('Edit') }}</x-link>
        @can('view', $project)
            <x-link button class="ml-4" route="projects.show,{{ $project->id }}" sm>
                {{ __('Details') }}</x-link>
        @endcan
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Project Information') }}
        </x-slot>

        @livewire('projects.edit', ['project' => $project])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Stations') }}
        </x-slot>

        @livewire('projects.stations', ['project' => $project, 'edit' => true])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Rights') }}
        </x-slot>

        @livewire('projects.rights', ['project' => $project, 'edit' => true])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Public registration') }}
        </x-slot>

        @livewire('projects.registrations', ['project' => $project, 'edit' => true])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Delete project') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Permanently delete this project and all information.') }}
        </x-slot>

        @livewire('projects.delete', ['project' => $project])

    </x-page-card>

</x-app-layout>
