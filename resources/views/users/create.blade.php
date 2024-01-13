<x-app-layout>
    <x-slot name="title">{{ __('Add user') }}</x-slot>
    <x-slot name="subtitle"></x-slot>
    <x-slot name="nav">
        <x-link route="users.index">{{ __('Users') }}</x-link> &gt; <x-link route="users.create">
            {{ __('Create') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('users.create')

    </x-page-card>

</x-app-layout>
