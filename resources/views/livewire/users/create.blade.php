<div>
    <x-form wire:submit="createUser">

        <div>
            <x-input label="{{ __('Username') }}" required wire:model="name" />
        </div>

        <div class="mt-4">
            <x-input label="{{ __('Email') }}" type="email" wire:model="email" />
        </div>

        <div class="mt-4">
            <x-password
                hint="{{ __('You can leave password empty. In this case the user can request a new password via email.') }}"
                label="{{ __('Password') }}" wire:model="password" />
        </div>

        <div class="mt-4">
            <x-password label="{{ __('Confirm Password') }}" wire:model="password_confirmation" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Email confirmation required / Send password reset link') }}"
                wire:model="email_confirm" />
        </div>

        <div class="mt-4">
            <x-checkbox
                label="{{ __(':terms_of_use and :privacy_policy confirmation required', [
                    'terms_of_use' => __('Terms of Use'),
                    'privacy_policy' => __('Privacy Policy'),
                ]) }}"
                wire:model="policy_confirm" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Active') }}" wire:model="active" />
        </div>

        <div class="buttonrow mt-4">

            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
