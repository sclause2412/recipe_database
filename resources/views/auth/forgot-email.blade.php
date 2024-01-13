<x-guest-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Forgot your email? Houston we have a problem! Unfortunately it is not possible to recover your user automatically without knowing your email address.') }}
        </div>
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('If you need further support please fill the fields below and send a request.') }}
        </div>

        <x-status />

        <x-form action="{{ route('username.sendemail') }}">

            <div class="mt-4">
                <x-input autocomplete="email" autofocus hint="{{ __('The email address you tried but did not work') }}"
                    label="{{ __('Email') }}" name="email" type="email" />
            </div>

            <div class="mt-4">
                <x-input autocomplete="username" hint="{{ __('The user name you think it should have been') }}"
                    label="{{ __('Username') }}" name="name" />
            </div>

            <div class="mt-4">
                <x-input autocomplete="name" hint="{{ __('Your full name') }}" label="{{ __('Full name') }}"
                    name="fullname" required />
            </div>

            <div class="mt-4">
                <x-input autocomplete="email" hint="{{ __('Your current email address') }}"
                    label="{{ __('Current email') }}" name="emailcurrent" required type="email" />
            </div>

            <div class="mt-4">
                <x-textarea hint="{{ __('Your home address') }}" label="{{ __('Address') }}" name="address" />
            </div>

            <div class="mt-4">
                <x-input autocomplete="tel" hint="{{ __('A phone number - do not forget to add country code') }}"
                    label="{{ __('Phone') }}" name="phone" />
            </div>

            <div class="mt-4">
                <x-input hint="{{ __('Add a picture of your ID card (e.g. passport, driver license)') }}"
                    label="{{ __('ID card') }}" name="file" required type="file" />
            </div>

            <div class="buttonrow mt-4">

                <x-link href="{{ route('login') }}">
                    {{ __('Back to Login') }}
                </x-link>

                <x-link href="{{ route('register') }}">
                    {{ __('Create a new account') }}
                </x-link>

                <x-button primary type="submit">
                    {{ __('Send request') }}
                </x-button>

            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
