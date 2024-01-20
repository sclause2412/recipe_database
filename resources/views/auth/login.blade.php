@use(App\Http\Middleware\RegistrationIsAllowed)
<x-guest-layout>
    <x-authentication-card>

        <x-status />

        <x-form action="{{ route('login') }}">

            <div>
                <x-input autocomplete="username" autofocus label="{{ __('Username') }}" name="name" required />
            </div>
            <div class="mt-4">
                <x-password autocomplete="current-password" label="{{ __('Password') }}" name="password" required />
            </div>
            <div class="mt-4">
                <x-checkbox label="{{ __('Remember me') }}" name="remember" />
            </div>

            <div class="buttonrow mt-4">

                @if (RegistrationIsAllowed::isAllowed())
                    <x-link class="text-sm" href="{{ route('register') }}">
                        {{ __('Need an account?') }}</x-link>
                @endif

                @if (Route::has('password.request'))
                    <x-link class="text-sm" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}</x-link>
                @endif

                <x-button primary type="submit">{{ __('Log in') }}</x-button>
            </div>

        </x-form>
    </x-authentication-card>
</x-guest-layout>
