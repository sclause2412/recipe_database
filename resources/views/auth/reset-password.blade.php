<x-guest-layout>
    <x-authentication-card>

        <x-status />

        <x-form action="{{ route('password.update') }}">

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-form.input class="block mt-1 w-full" type="text" name="name" :value="$request->name" required autofocus
                autocomplete="username">{{ __('Username') }}</x-form.input>

            <x-form.input devclass="mt-4" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password">{{ __('Password') }}</x-form.input>

            <x-form.input class="block mt-1 w-full" type="password" name="password_confirmation" required
                autocomplete="new-password">{{ __('Confirm Password') }}</x-form.input>

            <x-form.button-row>
                <x-form.button type="submit">
                    {{ __('Reset Password') }}
                </x-form.button>

            </x-form.button-row>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
