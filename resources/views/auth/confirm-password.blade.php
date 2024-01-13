<x-guest-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        <x-status />

        <x-form action="{{ route('password.confirm') }}">

            <x-form.input class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password"
                autofocus>{{ __('Password') }}</x-form.input>

            <x-form.button-row>
                <x-form.button type="submit">
                    {{ __('Confirm') }}
                </x-form.button>
            </x-form.button-row>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
