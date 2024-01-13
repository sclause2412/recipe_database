<x-app-layout>
    <x-slot name="title">{{ __('Project details') }}</x-slot>
    <x-slot name="subtitle">{{ $project->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="projects.index">{{ __('Projects') }}</x-link> &gt; <x-link
            route="projects.show,{{ $project->id }}">
            {{ __('Details') }}</x-link>
        @can('update', $project)
            <x-link button class="ml-4" icon="pencil" route="projects.edit,{{ $project->id }}" sm>
                {{ __('Edit') }}</x-link>
        @endcan
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Project information') }}
        </x-slot>

        <x-list>
            <x-list.item>
                <x-slot name="term">{{ __('Name') }}</x-slot>
                {{ $project->name }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Short code') }}</x-slot>
                {{ $project->short }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Description') }}</x-slot>
                {!! text_format($project->description) !!}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Active') }}</x-slot>
                {{ $project->active ? __('Yes') : __('No') }}
            </x-list.item>
        </x-list>
    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Stations') }}
        </x-slot>

        @livewire('projects.stations', ['project' => $project])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Rights') }}
        </x-slot>

        @livewire('projects.rights', ['project' => $project])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Public registration') }}
        </x-slot>

        @livewire('projects.registrations', ['project' => $project])

    </x-page-card>

</x-app-layout>
