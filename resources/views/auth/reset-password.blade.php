<x-guest-layout>
    <x-authentication-card>

        <x-status />

        <x-form action="{{ route('password.update') }}">

            <input name="token" type="hidden" value="{{ $request->route('token') }}">

            <div class="">
                <x-input :value="$request->name" autocomplete="username" autofocus label="{{ __('Username') }}" name="name"
                    required />
            </div>

            <div class="mt-4">
                <x-password autocomplete="new-password" label="{{ __('Password') }}" name="password" required />
            </div>

            <div class="mt-4">
                <x-password autocomplete="new-password" label="{{ __('Confirm Password') }}"
                    name="password_confirmation" required />
            </div>

            <div class="buttonrow mt-4">
                <x-button primary type="submit">
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
