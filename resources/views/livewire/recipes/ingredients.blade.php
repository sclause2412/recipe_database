<div class="space-y-2">
    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Reference') }}</x-table.head>
            <x-table.head>{{ __('Group') }}</x-table.head>
            <x-table.head>{{ __('Ingredient') }}</x-table.head>
            <x-table.head>{{ __('Amount') }}</x-table.head>
            <x-table.head>{{ __('Unit') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @php
            $prev = false;
        @endphp
        @if (count($ingredients))
            @while (($ingredient = current($ingredients)) !== false)
                @php
                    $key = key($ingredients);
                    $next = next($ingredients);

                    $group = $ingredient->group;
                    $lgroup = $prev ? $prev->group : null;
                    $ngroup = $next ? $next->group : null;

                    $disable_up = false;
                    $text_up = __('Up');
                    $style_up = 'secondary';
                    if ($prev === false) {
                        $disable_up = true;
                        $style_up = '';
                    }
                    if ($group != $lgroup && $prev !== false) {
                        $text_up = __('Group up');
                        $style_up = 'lime';
                        if (is_null($lgroup)) {
                            $disable_up = true;
                            $style_up = '';
                        }
                    }

                    $disable_down = false;
                    $text_down = __('Down');
                    $style_down = 'secondary';
                    if ($next === false) {
                        $disable_down = true;
                        $style_down = '';
                    }
                    if ($group != $ngroup && $next !== false) {
                        $text_down = __('Group down');
                        $style_down = 'lime';
                        if (is_null($group)) {
                            $disable_down = true;
                            $style_down = '';
                        }
                    }

                    $prev = $ingredient;
                @endphp
                <x-table.row wire:loading.class.delay="opacity-50">
                    <x-table.cell><x-badge teal>{{ $ingredient->reference }}</x-badge></x-table.cell>
                    <x-table.cell>{{ $ingredient->sort }} / {{ $ingredient->group }}</x-table.cell>
                    <x-table.cell>{{ $ingredient->ingredient?->name }}{{ is_null($ingredient->ingredient?->info) ? '' : ' (' . $ingredient->ingredient?->info . ')' }}</x-table.cell>
                    <x-table.cell>{{ $ingredient->amount }}</x-table.cell>
                    <x-table.cell>{{ $ingredient->unit?->unit }}</x-table.cell>
                    <x-table.cell>
                        <div class="flex justify-end space-x-2 text-lg">
                            <x-button :disabled="$disable_up" :style="$style_up" icon="arrow-up" title="{{ $text_up }}"
                                wire:click="stepUp('{{ $ingredient->id }}')" />
                            <x-button :disabled="$disable_down" :style="$style_down" icon="arrow-down" title="{{ $text_down }}"
                                wire:click="stepDown('{{ $ingredient->id }}')" />
                            <x-button icon="pencil" primary title="{{ __('Edit') }}"
                                wire:click="editIngredient('{{ $ingredient->id }}')" />
                            <x-deletebutton icon wire:click="deleteIngredient('{{ $ingredient->id }}')" />
                        </div>
                    </x-table.cell>
                </x-table.row>
            @endwhile
        @else
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="5">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endif
    </x-table>

    <div x-data="{ edit: false }" x-init="$watch('$wire.rid', (value, ov) => edit = value != '');">
        <x-form wire:submit="saveIngredient">

            <div class="mb-2 text-lg font-medium" x-show="!edit">{{ __('Add ingredient') }}</div>
            <div class="mb-2 text-lg font-medium" x-cloak x-show="edit">{{ __('Edit ingredient') }}</div>

            <input type="hidden" wire:model="rid" />

            <div class="">
                <x-input hint="{{ __('optional') }}" label="{{ __('Group') }}" wire:model="group" />
            </div>
            <div class="mt-4">
                <x-select :async-data="route('search', [
                    'area' => 'recipe',
                    'model' => 'ingredient',
                ])" hide-empty-message label="{{ __('Ingredient') }}" option-label="name"
                    option-value="id" required wire:model="ingredient">
                </x-select>
            </div>
            <div class="mt-4">
                <x-number label="{{ __('Amount') }}" min="0" step="0.05" wire:model="amount" />
            </div>
            <div class="mt-4">
                <x-select :async-data="route('search', [
                    'area' => 'recipe',
                    'model' => 'unit',
                ])" hide-empty-message label="{{ __('Unit') }}" option-label="name"
                    option-value="id" wire:model="unit">
                </x-select>
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
