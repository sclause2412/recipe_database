<x-guest-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your password? No problem. Just let us know your username and email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <x-status />

        <x-form action="{{ route('password.email') }}">

            <div>
                <x-input autocomplete="username" autofocus label="{{ __('Name') }}" name="name" required />
            </div>

            <div class="mt-4">
                <x-input autocomplete="email" label="{{ __('Email') }}" name="email" required type="email" />
            </div>

            <div class="buttonrow mt-4">

                <x-link href="{{ route('login') }}">
                    {{ __('Back to Login') }}
                </x-link>
                @if (Route::has('username.request'))
                    <x-link href="{{ route('username.request') }}">
                        {{ __('Forgot your username?') }}
                    </x-link>
                @endif
                <x-button primary type="submit">
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
