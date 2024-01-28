<x-app-layout hidetitle>
    <x-slot name="title">{{ __('Recipe') }}</x-slot>
    <x-slot name="subtitle">{{ $recipe->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="recipes.index">{{ __('Recipes') }}</x-link> &gt; <x-link route="recipes.show,{{ $recipe->slug }}">
            {{ __('Details') }}</x-link>
        @if (check_write('recipe'))
            <x-link button class="ml-4" icon="pencil" route="recipes.edit,{{ $recipe->slug }}" sm>
                {{ __('Edit') }}</x-link>
        @endif
    </x-slot>

    <x-page-card>
        <div x-data="{
            portions: {{ $recipe->portions }},
            portions_frac: {{ $recipe->portions }},
            temp_type: 'C',
            factor: 1,
            init() {
                $watch('portions', value => { this.recalculate() });
                $watch('temp_type', value => { this.recalculate() });
                $nextTick(() => { this.recalculate() });
            },
            recalculate() {
                this.portions = Math.floor(this.portions * 8) / 8;
                if (this.portions < 0.125)
                    this.portions = 0.125;
                if (this.portions > 10000)
                    this.portions = 10000;
                this.factor = this.portions / {{ $recipe->portions }};
                this.portions_frac = calculate_fraction(this.portions);
                $dispatch('update_ingredients');
                $dispatch('update_numbers');
            }
        }">
            <h1 class="text-4xl font-bold">{{ $recipe->name }}</h1>
            <h2 class="text-xl font-bold">{{ $recipe->category?->name }}</h2>
            @if ($picture)
                <div class="mt-2 print:flex print:justify-center"><img
                        class="h-80 w-auto rounded-md object-cover print:h-auto print:w-full"
                        src="{{ $picture }}"></div>
            @endif
            <div class="mt-2">{{ __('Portions:') }} <span x-html="portions_frac"></span>
                <div class="ml-8 inline-block print:hidden">
                    <x-button secondary sm x-bind:disabled="portions <= 0.125"
                        x-on:click="portions = portions / 2"><span class="diagonal-fractions">1/2</span></x-button>
                    <x-button secondary sm x-bind:disabled="portions <= 1" x-on:click="portions--">-1</x-button>
                    <x-button secondary sm x-on:click="portions++">+1</x-button>
                    <x-button secondary sm x-on:click="portions = portions * 2">&times;2</x-button>
                </div>
            </div>
            <div class="">{{ __('Time:') }} {{ calculate_time($recipe->time) }}</div>
            <div class="mt-2 print:hidden">
                <x-button icon="thermometer" sm x-bind:disabled="temp_type == 'C'"
                    x-on:click="temp_type = 'C'">°C</x-button>
                <x-button icon="thermometer" sm x-bind:disabled="temp_type == 'F'"
                    x-on:click="temp_type = 'F'">°F</x-button>
            </div>
            <div class="mt-8 flex flex-nowrap">
                <div
                    class="min-w-min flex-none border-r-2 border-dotted border-gray-400 pb-4 pr-4 dark:border-gray-600">
                    <table>
                        @php
                            $group = null;
                        @endphp
                        @foreach ($ingredients as $ingredient)
                            @if ($ingredient->group != $group)
                                @php
                                    $group = $ingredient->group;
                                @endphp
                                <tr>
                                    <td class="pb-2 pt-4 font-bold" colspan="3">
                                        {{ $group }}
                                    </td>
                                </tr>
                            @endif
                            <tr x-data="{
                                amount: {{ $ingredient->amount }},
                                unit: '{{ $ingredient->unit?->unit }}',
                                fix: {{ $ingredient->fix ? 'true' : 'false' }},
                                fraction: {{ $ingredient->unit?->fraction ? 'true' : 'false' }},
                                amount_out: {{ $ingredient->amount }},
                                unit_out: '',
                                update() {
                                    if (this.fix)
                                        var amount = this.amount;
                                    else
                                        var amount = this.amount * this.factor;
                                    this.amount_out = calculate_number(amount, this.fraction);
                                    this.unit_out = calculate_unit(this.unit, amount);
                                }
                            }" x-on:update_ingredients.window="update()">
                                @if (is_null($ingredient->amount))
                                    <td class="pb-1 pr-4 align-top" colspan="2">
                                        <span x-html="unit_out"></span>
                                    </td>
                                @else
                                    <td class="pb-1 pr-1 text-right align-top">
                                        {{ $ingredient->approximately ? __('appr.') : '' }} <span
                                            x-html="amount_out"></span>
                                    </td>
                                    <td class="pb-1 pr-4 align-top">
                                        <span x-html="unit_out"></span>
                                    </td>
                                @endif
                                <td class="pb-1 align-top"><span class="ingredient transition-colors"
                                        x-orig="{{ $ingredient->reference }}">{{ $ingredient->ingredient?->name }}{{ is_null($ingredient->ingredient?->info) ? '' : ' (' . $ingredient->ingredient?->info . ')' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="divide-y divide-gray-400 pb-4 pl-4 dark:divide-gray-600">
                    @foreach ($steps as $step)
                        <div class="flex break-inside-avoid gap-4 py-2">
                            <div class="text-4xl text-gray-400 dark:text-gray-600">{{ $step->step }}</div>
                            <div>
                                {!! text_code_format($step->text, $ingredient_list) !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div
                class="w-full divide-y divide-gray-400 border-t-2 border-dotted border-gray-400 pt-8 dark:divide-gray-600 dark:border-gray-600">
                @foreach ($comments as $comment)
                    <div class="py-2">
                        <div>
                            {!! text_code_format($comment->text, $ingredient_list) !!}
                        </div>
                    </div>
                @endforeach
            </div>
            @if ($recipe->source)
                <div class="mt-4 text-sm text-gray-500">
                    {{ __('Source:') }} {{ $recipe->source }}
                </div>
            @endif
        </div>

    </x-page-card>

    <script>
        window.setTimeout(() => {
            var elms = document.getElementsByClassName('ingredient');
            for (var i = 0; i < elms.length; i++) {
                var e = elms[i];
                var o = e.getAttribute('x-orig');
                if (o == '' || o == null)
                    continue;

                elms[i].addEventListener('mouseover', hoverIngredient);
                elms[i].addEventListener('mouseout', clearIngredient);
            }
        }, 1000);


        function hoverIngredient(ev) {
            if (!ev.target)
                return;
            var ref = ev.target.getAttribute('x-orig');
            if (!ref)
                return;

            var elms = document.getElementsByClassName('ingredient');
            for (var i = 0; i < elms.length; i++) {
                var e = elms[i];
                var o = e.getAttribute('x-orig');
                if (o == ref) {
                    elms[i].classList.add('bg-green-200');
                    elms[i].classList.add('dark:bg-green-800');
                } else {
                    elms[i].classList.remove('bg-green-200');
                    elms[i].classList.remove('dark:bg-green-800');
                }
            }
        }

        function clearIngredient(ev) {
            var elms = document.getElementsByClassName('ingredient');
            for (var i = 0; i < elms.length; i++) {
                {
                    elms[i].classList.remove('bg-green-200');
                    elms[i].classList.remove('dark:bg-green-800');
                }
            }
        }
    </script>

</x-app-layout>
