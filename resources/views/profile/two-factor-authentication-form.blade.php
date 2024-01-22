<div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        @if ($this->enabled)
            @if ($showingConfirmation)
                {{ __('Finish enabling two factor authentication.') }}
            @else
                {{ __('You have enabled two factor authentication.') }}
            @endif
        @else
            {{ __('You have not enabled two factor authentication.') }}
        @endif
    </h3>

    <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
        <p>
            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Authenticator application.') }}
        </p>
    </div>

    @if ($this->enabled)
        @if ($showingQrCode)
            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                <p class="font-semibold">
                    @if ($showingConfirmation)
                        {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                    @else
                        {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                    @endif
                </p>
            </div>

            <div class="mt-4">
                {!! $this->user->twoFactorQrCodeSvg() !!}
            </div>

            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                <p class="font-semibold">
                    {{ __('Setup Key') }}: {{ decrypt($this->user->two_factor_secret) }}
                </p>
            </div>

            @if ($showingConfirmation)
                <div>
                    <x-input autocomplete="one-time-code" autofocus class="mt-1 block w-1/2" inputmode="numeric"
                        label="{{ __('Code') }}" name="code" wire:keydown.enter="confirmTwoFactorAuthentication"
                        wire:model="code" />
                </div>
            @endif
        @endif

        @if ($showingRecoveryCodes)
            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                <p class="font-semibold">
                    {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                </p>
            </div>

            <div class="mt-4 grid max-w-xl gap-1 rounded-lg bg-gray-100 px-4 py-4 font-mono text-sm">
                @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                    <div>{{ $code }}</div>
                @endforeach
            </div>
        @endif
    @endif

    <div class="buttonrow mt-4">
        @if (!$this->enabled)
            <x-confirms-password success="enableTwoFactorAuthentication">
                <x-button primary>
                    {{ __('Enable') }}
                </x-button>
            </x-confirms-password>
        @else
            @if ($showingRecoveryCodes)
                <x-confirms-password success="regenerateRecoveryCodes">
                    <x-button secondary>
                        {{ __('Regenerate Recovery Codes') }}
                    </x-button>
                </x-confirms-password>
            @elseif ($showingConfirmation)
                <x-button primary wire:click="confirmTwoFactorAuthentication">
                    {{ __('Confirm') }}
                </x-button>
            @else
                <x-confirms-password success="showRecoveryCodes">
                    <x-button secondary>
                        {{ __('Show Recovery Codes') }}
                    </x-button>
                </x-confirms-password>
            @endif

            @if ($showingConfirmation)
                <x-button secondary wire:click="disableTwoFactorAuthentication">
                    {{ __('Cancel') }}
                </x-button>
            @else
                <x-confirms-password success="disableTwoFactorAuthentication">
                    <x-button negative>
                        {{ __('Disable') }}
                    </x-button>
                </x-confirms-password>
            @endif

        @endif
    </div>
</div>
