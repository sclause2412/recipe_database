<x-app-layout>
    <x-slot name="title">{{ __('User details') }}</x-slot>
    <x-slot name="subtitle">{{ $user->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="users.index">{{ __('Users') }}</x-link> &gt; <x-link route="users.show,{{ $user->id }}">
            {{ __('Details') }}</x-link>
        <x-link :show="true" button class="ml-4" icon="pencil" route="users.edit,{{ $user->id }}" sm>
            {{ __('Edit') }}</x-link>
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Profile Information') }}
        </x-slot>

        <x-list>
            <x-list.item>
                <x-slot name="term">{{ __('Username') }}</x-slot>
                {{ $user->name }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Photo') }}</x-slot>
                <img alt="{{ $user->name }}" class="h-20 w-20 rounded-full object-cover"
                    src="{{ $user->profile_photo_url }}">
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('First name') }}</x-slot>
                {{ $user->firstname }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Last name') }}</x-slot>
                {{ $user->lastname }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Email') }}</x-slot>
                {{ $user->email }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Verified') }}</x-slot>
                {{ is_null($user->email_verified_at) ? __('No') : __('Yes') }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Active') }}</x-slot>
                {{ $user->active ? __('Yes') : __('No') }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('2FA') }}</x-slot>
                {{ is_null($user->two_factor_confirmed_at) ? __('No') : __('Yes') }}
            </x-list.item>
        </x-list>
    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Rights') }}
        </x-slot>

        <x-list>
            <x-list.item>
                <x-slot name="term"> {{ __('Administrator') }}</x-slot>
                {{ $user->admin ? __('Yes') : __('No') }}
            </x-list.item>
        </x-list>

    </x-page-card>

</x-app-layout>
