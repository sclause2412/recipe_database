<div class="space-y-2">
    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Comment') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($comments as $comment)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell>{!! text_code_format($comment->text, $ingredients, true) !!}</x-table.cell>
                <x-table.cell buttons>
                    <x-button :disabled="$loop->first" icon="arrow-up" secondary title="{{ __('Up') }}"
                        wire:click="stepUp('{{ $comment->id }}')" />
                    <x-button :disabled="$loop->last" icon="arrow-down" secondary title="{{ __('Up') }}"
                        wire:click="stepDown('{{ $comment->id }}')" />
                    <x-button icon="pencil" primary title="{{ __('Edit') }}"
                        wire:click="editComment('{{ $comment->id }}')" />
                    <x-deletebutton icon wire:click="deleteComment('{{ $comment->id }}')" />
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="2">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != '');">
        <x-form wire:submit="saveComment">

            <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add comment') }}</div>
            <div class="mb-2 text-lg font-medium" x-cloak x-show="edit">{{ __('Edit comment') }}</div>

            <input type="hidden" wire:model="rid" />

            <div class="">
                <x-textarea required wire:model="text">
                    <x-slot name="hint">{!! text_code_hint() !!}</x-slot>
                </x-textarea>
            </div>

            <div class="buttonrow mt-4">
                <x-button icon="plus" secondary x-cloak x-on:click.prevent="$wire.rid=''" x-show="edit">
                    {{ __('New') }}
                </x-button>

                <x-button primary type="submit">
                    {{ __('Save') }}
                </x-button>
            </div>
        </x-form>
    </div>
</div>
