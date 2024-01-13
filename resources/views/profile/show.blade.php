<x-app-layout>
    <x-slot name="title">{{ __('Profile') }}</x-slot>
    <x-slot name="nav">
        <x-link route="profile.show">{{ __('Profile') }}</x-link>
    </x-slot>

    @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        <x-page-card>
            <x-slot name="title">
                {{ __('Profile Information') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Update your account\'s profile information and email address.') }}
            </x-slot>

            @livewire('users.profile', ['user' => Auth::user()])

        </x-page-card>
    @endif

    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        <x-page-card>
            <x-slot name="title">
                {{ __('Update Password') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </x-slot>

            @livewire('users.password', ['user' => Auth::user()])

        </x-page-card>
    @endif

    @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
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

    @if (check_write('admin'))
        <x-page-card>
            <x-slot name="title">
                {{ __('Rights') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Change your rights.') }}
            </x-slot>

            @livewire('users.rights', ['user' => $user])

        </x-page-card>
    @endif

    <x-page-card>
        <x-slot name="title">
            {{ __('Browser Sessions') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Manage and log out your active sessions on other browsers and devices.') }}
        </x-slot>
        @livewire('users.sessions', ['user' => Auth::user()])

    </x-page-card>

    @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        <x-page-card>
            <x-slot name="title">
                {{ __('Delete Account') }}
            </x-slot>

            <x-slot name="description">
                {{ __('Permanently delete your account.') }}
            </x-slot>
            @livewire('users.delete', ['user' => Auth::user()])
        </x-page-card>
    @endif

</x-app-layout>
