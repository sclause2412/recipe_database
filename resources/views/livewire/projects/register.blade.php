<div>
    @if ($showcode)
        <x-page-card>
            <div>{{ __('This registration is not public. Please enter the registration code to continue.') }}</div>
            <x-form wire:submit="enterCode">
                <div class="mt-4">
                    <x-password label="{{ __('Code') }}" required wire:model="code" />
                </div>

                <div class="buttonrow mt-4">
                    <x-button primary type="submit">
                        {{ __('Continue') }}
                    </x-button>
                </div>

            </x-form>
        </x-page-card>
    @endif

    @if (!$showcode)
        <x-page-card>

            @if (is_null($registertype))
                <div class="grid grid-cols-2 gap-4">
                    <x-card
                        class="cursor-pointer border border-solid border-gray-200 hover:bg-gray-100 dark:border-gray-600 hover:dark:bg-gray-700"
                        shadow="none" wire:click="registerSelf">
                        <div class="flex justify-center">
                            <x-icon class="h-32 w-32" name="user" />
                        </div>
                        <div class="text-center text-xl">
                            {{ __('I want to register myself for :project', ['project' => $project->name]) }}
                        </div>
                    </x-card>
                    <x-card
                        class="cursor-pointer border border-solid border-gray-200 hover:bg-gray-100 dark:border-gray-600 hover:dark:bg-gray-700"
                        shadow="none" wire:click="registerTeam">
                        <div class="flex justify-center">
                            <x-icon class="h-32 w-32" name="users" />
                        </div>
                        <div class="text-center text-xl">
                            {{ __('I want to register a team for :project', ['project' => $project->name]) }}
                        </div>
                    </x-card>
                </div>
            @else
                <div class="gap-4">
                    <x-button :style="$registertype == 'self' ? 'info' : 'secondary'" icon="user" wire:click="registerSelf">
                        {{ __('I want to register myself') }}
                    </x-button>
                    <x-button :style="$registertype == 'self' ? 'secondary' : 'info'" icon="users" wire:click="registerTeam">
                        {{ __('I want to register a team') }}
                    </x-button>
                </div>
            @endif

        </x-page-card>

        @isset($employee)
            <x-page-card>
                @livewire('employees.edit', ['employee' => $employee])
            </x-page-card>
        @endisset
    @endif
</div>
