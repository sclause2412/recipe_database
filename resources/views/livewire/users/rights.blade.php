@php
    $options = ['0' => __('No'), '1' => __('Read'), '2' => __('Write')];
@endphp
<div>
    <x-form wire:submit="updateRights">

        @if (is_last_admin($this->user))
            <div class="mb-4 text-sm font-bold text-red-600 dark:text-red-400">
                {{ __('You are the last admin. The fields Administrator and Active are therefore required.') }}
            </div>
        @endif

        <div>
            <x-checkbox label="{{ __('Administrator') }}" wire:model="admin" />
        </div>

        <div class="mt-4">
            <x-checkbox label="{{ __('Active') }}" wire:model="active" />
        </div>

        <div class="mt-4">
            <x-checkbox
                description="{{ __('If this option is activated the user will be logged out on all other devices on login.') }}"
                label="{{ __('Single login') }}" wire:model="single_login" />
        </div>

        @foreach (RightConstants::scope('G')->array() as $right => $label)
            <div class="mt-4">
                <x-radiogroup :options="$options" label="{{ __($label) }}" wire:model="rights.{{ $right }}" />
            </div>
        @endforeach

        <div class="buttonrow mt-4">
            <x-button primary type="submit">
                {{ __('Save') }}
            </x-button>
        </div>
    </x-form>
</div>
