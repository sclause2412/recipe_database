<x-guest-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-status />

        <x-form action="{{ route('password.confirm') }}">

            <div class="">
                <x-password autocomplete="current-password" autofocus label="{{ __('Password') }}" name="password"
                    required />
            </div>

            <div class="buttonrow mt-4">
                <x-button primary type="submit">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
