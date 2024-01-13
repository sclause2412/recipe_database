<x-guest-layout>
    <x-authentication-card>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="! recovery">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="recovery">
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            </div>

            <x-status />

            <x-form action="{{ route('two-factor.login') }}">

                <div x-show="! recovery">
                    <div>
                        <x-input autocomplete="one-time-code" autofocus inputmode="numeric" label="{{ __('Code') }}"
                            name="code" x-ref="code" />
                    </div>
                </div>

                <div x-cloak x-show="recovery">
                    <div>
                        <x-input autocomplete="off" class="w-full" label="{{ __('Recovery Code') }}"
                            name="recovery_code" x-ref="recovery_code" />
                    </div>
                </div>

                <div class="buttonrow mt-4">

                    <x-button secondary
                        x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    "
                        x-show="! recovery">
                        {{ __('Use a recovery code') }}
                    </x-button>

                    <x-button secondary x-cloak
                        x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    "
                        x-show="recovery">
                        {{ __('Use an authentication code') }}
                    </x-button>

                    <x-button primary type="submit">
                        {{ __('Log in') }}
                    </x-button>
                </div>
            </x-form>
        </div>
    </x-authentication-card>
</x-guest-layout>
