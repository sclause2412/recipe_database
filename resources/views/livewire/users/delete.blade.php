<div>
    @if (is_last_admin($this->user))
        <div class="text-sm font-bold text-red-600 dark:text-red-400">
            {{ __('You are the last admin. It is not possible to delete the account as the page will not work anymore otherwise.') }}
        </div>
    @else
        <div>
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once the account is deleted, all of its resources and data will be permanently deleted.') }}
            </div>

            <div class="buttonrow">

                @if ($this->user->current)
                    <x-confirms-password description="{{ __('Attention: You cannot undo this action!') }}"
                        success="deleteUser">
                        <x-button negative>
                            {{ __('Delete Account') }}
                        </x-button>
                    </x-confirms-password>
                @else
                    <x-deletebutton wire:click="deleteUser">
                        {{ __('Delete Account') }}
                    </x-deletebutton>
                @endif

            </div>
        </div>
    @endif
</div>
