<x-app-layout>
    <x-slot name="title">Users</x-slot>
    <x-page-card>
        @livewire('users.index')

        <x-link button icon="plus" route="users.create">Add new user</x-link>
    </x-page-card>

</x-app-layout>
