<div>
    <x-form wire:submit="updatePassword">

        @if ($this->user->current)
            <div class="mb-4">
                <x-password autocomplete="current-password" label="{{ __('Current Password') }}"
                    wire:model="current_password" />
            </div>
        @endif

        <div>
            <x-password label="{{ __('New Password') }}" wire:model="password" />
        </div>

        <div class="mt-4">
            <x-password label="{{ __('Confirm Password') }}" wire:model="password_confirmation" />
        </div>

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
