<x-app-layout>
    <x-authentication-card>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('You want to activate your special admin rights which are deactivated by default for security reasons.') }}
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Please confirm that you know what you do before you continue.') }}
        </div>

        @if (is_null(Auth::user()?->two_factor_confirmed_at))
            <div class="mb-4 text-sm font-bold text-red-600 dark:text-red-400">
                {{ __('To use this feature you must have Two Factor Authentication enabled. Please go to your profile page to enable it.') }}
            </div>
            <x-link route="profile.show">{{ __('Profile') }}</x-link>
        @else
            <x-status />

            <x-form action="{{ route('user.elevate.confirm') }}">

                <input name="forward" type="hidden" value="{{ $forward }}" />

                <div class="mb-4 rounded-md border-2 border-red-500 p-2 text-sm text-red-600 dark:text-red-400">
                    {{ __('I know what I do and I know that I can destroy data by doing things I do not understand. I will think twice before I click any button!') }}
                </div>

                <div class="buttonrow mt-4">
                    <x-button primary type="submit">
                        {{ __('Confirm') }}
                    </x-button>
                </div>
            </x-form>
        @endif
    </x-authentication-card>
</x-app-layout>
