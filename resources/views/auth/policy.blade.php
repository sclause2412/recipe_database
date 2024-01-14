<x-guest-layout>
    <x-authentication-card>

        <x-status />

        <x-form action="{{ route('policy.accept') }}">

            <div class="gray-600 mb-4 text-lg dark:text-gray-400">
                {{ __('You have not accepted yet!') }}
            </div>

            <div class="mt-4">
                <x-link href="{{ route('terms.show') }}">
                    {{ __('Terms of Service') }}
                </x-link>
            </div>

            <div class="mt-4">
                <x-link href="{{ route('policy.show') }}">
                    {{ __('Privacy Policy') }}
                </x-link>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-checkbox label="{!! __('I agree to the :terms_of_service and :privacy_policy', [
                        'terms_of_service' => __('Terms of Service'),
                        'privacy_policy' => __('Privacy Policy'),
                    ]) !!}" name="terms" required />
                </div>
            @endif

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
