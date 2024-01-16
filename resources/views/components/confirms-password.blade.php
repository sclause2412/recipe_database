@props(['success' => null, 'title', 'description'])

@php
    $internalID = md5($success);
@endphp

<span wire:then="{{ $success }}" x-data x-on:click="$wire.startConfirmingPassword('{{ $success }}')"
    x-on:password-confirmed.window="setTimeout(() => $event.detail[0].action === '{{ $success }}' && $refs.span.dispatchEvent(new CustomEvent('then', { bubbles: false })), 250);"
    x-ref="span">{{ $slot }}
</span>

<x-modal wire:model="confirmingPassword"
    x-on:open="setTimeout(()=>document.getElementById('confirmPasswordInput{{ $internalID }}').focus(),250 )">
    <x-card class="w-full">
        <x-slot name="header">
            <div class="flex items-center justify-between border-b px-4 py-2.5 dark:border-0">
                <h3 class="text-md whitespace-normal font-medium text-secondary-700 dark:text-secondary-400">
                    {!! $title ?? __('Security') !!}
                </h3>
            </div>
        </x-slot>
        <div>
            {!! $description ?? __('For your security, please confirm to continue.') !!}
        </div>
        <div class="mt-4">
            <x-password autocomplete="current-password" id="confirmPasswordInput{{ $internalID }}"
                label="{{ __('Please provide your password for confirmation') }}" name="password"
                wire:model="confirmablePassword" x-on:keydown.enter="$wire.confirmPassword" />
        </div>
        <x-slot name="footer">
            <div class="buttonrow">
                <x-button secondary x-on:click="close">
                    {{ __('Cancel') }}
                </x-button>

                <x-button negative wire:click="confirmPassword">
                    {{ __('Confirm') }}
                </x-button>
            </div>
        </x-slot>
    </x-card>
</x-modal>
