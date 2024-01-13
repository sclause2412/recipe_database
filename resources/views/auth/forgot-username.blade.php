<x-guest-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your username? No problem. Just let us know your email address and we will email you a list of all usernames associated with this address.') }}
        </div>

        <x-status />

        <x-form action="{{ route('username.email') }}">

            <div>
                <x-input autocomplete="email" autofocus label="{{ __('Email') }}" name="email" required
                    type="email" />
            </div>

            <div class="buttonrow mt-4">
                <x-link href="{{ route('login') }}">
                    {{ __('Back to Login') }}
                </x-link>
                @if (Route::has('username.requestemail'))
                    <x-link href="{{ route('username.requestemail') }}">
                        {{ __('Forgot your email address?') }}
                    </x-link>
                @endif
                <x-button primary type="submit">
                    {{ __('Email user list') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
