<x-guest-layout>
    <x-authentication-card>

        <div class="gray-600 mb-4 text-lg dark:text-gray-400">
            {{ __('Your user account is locked!') }}
        </div>
        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('If you feel that this is an error please give us a reason. An administrator will contact you.') }}
        </div>

        <x-status />

        <x-form action="{{ route('locked.store') }}">

            <div>
                <x-textarea autocomplete="no" autofocus label="{{ __('Reason') }}" name="reason" required />
            </div>

            <div class="mt-4">
                <x-input label="{{ __('ID Card') }}" name="file" required type="file" />
            </div>

            <div class="buttonrow mt-4">

                <x-link class="text-sm" href="{{ route('logout.fast') }}">
                    {{ __('Logout') }}
                </x-link>

                <x-button primary type="submit">
                    {{ __('Send claim') }}
                </x-button>
            </div>
        </x-form>
    </x-authentication-card>
</x-guest-layout>
