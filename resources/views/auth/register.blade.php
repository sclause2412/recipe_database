<x-guest-layout>
    <x-authentication-card>

        <x-status />

        <x-form action="{{ route('register') }}">

            <div>
                <x-input autocomplete="username" autofocus label="{{ __('Username') }}" name="name" required />
            </div>

            <div class="mt-4">
                <x-input autocomplete="email" label="{{ __('Email') }}" name="email" required type="email" />
            </div>

            <div class="mt-4">
                <x-password autocomplete="new-password" label="{{ __('Password') }}" name="password" required />
            </div>

            <div class="mt-4">
                <x-password autocomplete="new-password" label="{{ __('Confirm Password') }}"
                    name="password_confirmation" required />
            </div>

            <div class="mt-4">
                <x-checkbox label="{!! __('I agree to the :terms_of_use and :privacy_policy', [
                    'terms_of_use' => __('Terms of Use'),
                    'privacy_policy' => __('Privacy Policy'),
                ]) !!}" name="terms" required />
            </div>

            <div class="buttonrow mt-4">
                <x-link route="legal.terms">
                    {{ __('Terms of Use') }}
                </x-link>

                <x-link route="legal.privacy">
                    {{ __('Privacy Policy') }}
                </x-link>
            </div>

            <div class="buttonrow mt-4">
                <x-link href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </x-link>

                <x-button primary type="submit">
                    {{ __('Register') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
