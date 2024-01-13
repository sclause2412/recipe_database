<div class="space-y-2">

    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Start') }}</x-table.head>
            <x-table.head>{{ __('End') }}</x-table.head>
            <x-table.head>{{ __('Type') }}</x-table.head>
            <x-table.head>{{ __('Code') }}</x-table.head>
            @if ($edit)
                <x-table.head />
            @endif
        </x-slot>
        @forelse ($registrations as $registration)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{{ $registration->start->format('d.m.Y H:i') }}</x-table.cell>
                <x-table.cell>{{ $registration->end->format('d.m.Y H:i') }}</x-table.cell>
                <x-table.cell>{{ ProjectRegistrationConstants::description($registration->type) }}</x-table.cell>
                <x-table.cell>
                    @if ($registration->code)
                        <div x-data="{ show: false }">
                            <span x-show="!show">{{ __('Yes') }}</span>
                            <span class="text-red-600 dark:text-red-400" x-cloak
                                x-show="show">{{ $registration->code }}</span>
                            <x-button circle icon="eye" secondary title="{{ __('Show') }}" x-on:click="show=true"
                                x-show="!show" xs />
                        </div>
                    @else
                        {{ __('No') }}
                    @endif
                </x-table.cell>
                @if ($edit)
                    <x-table.cell>
                        <div class="flex space-x-2 text-lg">
                            <x-button icon="pencil" secondary title="{{ __('Edit') }}"
                                wire:click="editRegistration('{{ $registration->id }}')" />
                            <x-deletebutton icon wire:click="deleteRegistration('{{ $registration->id }}')" />
                        </div>
                    </x-table.cell>
                @endif
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="4">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>
    {{ $registrations->links() }}
    @if ($edit)
        <div x-data="{ edit: false }" x-init="$watch('$wire.id', (value, ov) => edit = value != '');">
            <x-form wire:submit="saveRegistration">

                <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add registration') }}</div>
                <div class="mb-2 text-lg font-medium" x-show="edit">{{ __('Edit registration') }}</div>

                <input type="hidden" wire:model="id" />

                <div>
                    <x-datetime-picker :user-timezone="config('app.timezone')" display-format="DD.MM.YYYY HH:mm"
                        label="{{ __('Begin of public registration') }}" parse-format="YYYY-MM-DD HH:mm" required
                        time-format="24" wire:model="start" without-timezone />
                </div>

                <div class="mt-4">
                    <x-datetime-picker :user-timezone="config('app.timezone')" display-format="DD.MM.YYYY HH:mm"
                        label="{{ __('End of public registration') }}" parse-format="YYYY-MM-DD HH:mm" time-format="24"
                        wire:model="end" without-timezone />
                </div>

                <div class="mt-4">
                    <x-select :options="ProjectRegistrationConstants::array(true, 'id', 'name')" label="{{ __('Type') }}" option-label="name" option-value="id"
                        required wire:model="type" />
                </div>

                <div class="mt-4">
                    <x-textarea label="{{ __('Description') }}" wire:model="description" />
                </div>

                <div class="mt-4">
                    <x-input hint="{{ __('Use only if you want to protect from public access') }}"
                        label="{{ __('Code') }}" wire:model="code" />
                </div>

                <div class="buttonrow mt-4">
                    <x-button icon="plus" secondary x-on:click.prevent="$wire.id=''" x-show="edit">
                        {{ __('New') }}
                    </x-button>

                    <x-button primary type="submit">
                        {{ __('Save') }}
                    </x-button>
                </div>
            </x-form>
        </div>
    @endif
</div>
