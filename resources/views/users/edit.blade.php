<x-app-layout>
    <x-slot name="title">{{ __('Edit user') }}</x-slot>
    <x-slot name="subtitle">{{ $user->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="users.index">{{ __('Users') }}</x-link> &gt; <x-link route="users.edit,{{ $user->id }}">
            {{ __('Edit') }}</x-link>
        <x-link button class="ml-4" icon="eye" route="users.show,{{ $user->id }}" sm>
            {{ __('Details') }}</x-link>
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Profile Information') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Update user account\'s profile information and email address.') }}
        </x-slot>

        @livewire('users.profile', ['user' => $user])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Update Password') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Change the password for the user.') }}
        </x-slot>

        @livewire('users.password', ['user' => $user])

    </x-page-card>

    @if ($user->current && Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        <x-page-card>
            <x-slot name="title">
                {{ __('Two Factor Authentication') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Add additional security to your account using two factor authentication.') }}
            </x-slot>
            @livewire('profile.two-factor-authentication-form')

        </x-page-card>
    @endif

    <x-page-card>
        <x-slot name="title">
            {{ __('Rights') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Change rights of this user.') }}
        </x-slot>

        @livewire('users.rights', ['user' => $user])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Browser Sessions') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Manage and log out sessions on all browsers and devices.') }}
        </x-slot>

        @livewire('users.sessions', ['user' => $user])

    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Delete Account') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Permanently delete user account.') }}
        </x-slot>

        @livewire('users.delete', ['user' => $user])

    </x-page-card>

</x-app-layout>
