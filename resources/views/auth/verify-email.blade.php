<x-guest-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
            </div>
        @endif

        <x-form action="{{ route('verification.send') }}">

            <div class="buttonrow mt-4">

                <x-link href="{{ route('profile.show') }}">
                    {{ __('Edit Profile') }}</x-link>

                <x-link href="{{ route('logout') }}">
                    {{ __('Logout') }}</x-link>

                <x-button class="button" primary type="submit">
                    {{ __('Resend Verification Email') }}
                </x-button>

            </div>
        </x-form>

    </x-authentication-card>
</x-guest-layout>
