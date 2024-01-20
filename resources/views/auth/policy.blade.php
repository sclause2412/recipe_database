<x-guest-layout>
    <x-authentication-card>

        <x-status />

        <x-form action="{{ route('policy.accept') }}">

            <div class="gray-600 mb-4 text-lg dark:text-gray-400">
                {{ __('You have not accepted yet!') }}
            </div>

            <div class="mt-4">
                <x-link route="legal.terms">
                    {{ __('Terms of Use') }}
                </x-link>
            </div>

            <div class="mt-4">
                <x-link route="legal.privacy">
                    {{ __('Privacy Policy') }}
                </x-link>
            </div>

            <div class="mt-4">
                <x-checkbox label="{!! __('I agree to the :terms_of_use and :privacy_policy', [
                    'terms_of_use' => __('Terms of Use'),
                    'privacy_policy' => __('Privacy Policy'),
                ]) !!}" name="terms" required />
            </div>

            <div class="buttonrow mt-4">
                <x-link href="{{ route('logout.fast') }}">
                    {{ __('Logout') }}</x-link>

                <x-button primary type="submit">
                    {{ __('Save') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
