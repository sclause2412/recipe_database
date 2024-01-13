<x-app-layout>
    <x-slot name="title">{{ __('Register for project') }}</x-slot>
    <x-slot name="subtitle"></x-slot>
    <x-slot name="nav">
        <x-link route="dashboard">{{ __('Dashboard') }}</x-link> &gt; <x-link
            route="projects.register,{{ $project->id }}">
            {{ __('Register') }}</x-link>
    </x-slot>

    @livewire('projects.register', ['project' => $project])

</x-app-layout>
