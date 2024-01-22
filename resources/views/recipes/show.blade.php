@php
    $factor = $portions / ($recipe->portions ?? 1);
@endphp
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
        <div>
            <h1 class="text-4xl font-bold">{{ $recipe->name }}</h1>
            <h2 class="text-xl font-bold">{{ $recipe->category?->name }}</h2>
            <div class="mt-2">{{ __('Portions:') }} {{ $portions }}
                <div class="ml-8 inline-block print:hidden">
                    @if ($portions > 1)
                        <x-link button icon="minus"
                            route="recipes.show,{{ $recipe->slug }},portions={{ $portions - 1 }},temp={{ $temp }}"
                            secondary sm />
                    @endif
                    <x-link button icon="plus"
                        route="recipes.show,{{ $recipe->slug }},portions={{ $portions + 1 }},temp={{ $temp }}"
                        secondary sm />
                </div>
            </div>
            <div class="">{{ __('Time:') }} {{ calculate_time($recipe->time) }}</div>
            <div class="mt-2 print:hidden">
                <x-link button icon="thermometer"
                    route="recipes.show,{{ $recipe->slug }},portions={{ $portions }},temp=C" secondary
                    sm>°C</x-link>
                <x-link button icon="thermometer"
                    route="recipes.show,{{ $recipe->slug }},portions={{ $portions }},temp=F" secondary
                    sm>°F</x-link>
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
                            <tr>
                                @if (is_null($ingredient->amount))
                                    <td class="pb-1 pr-4 align-top" colspan="2">
                                        {{ $ingredient->unit?->unit }}</td>
                                @else
                                    @php
                                        $amount = $ingredient->amount * ($ingredient->fix ? 1 : $factor);
                                    @endphp
                                    <td class="pb-1 pr-1 text-right align-top">
                                        {{ $ingredient->approximately ? __('appr.') : '' }} {!! calculate_number($amount, $ingredient->unit?->fraction ?? false) !!}
                                    </td>
                                    <td class="pb-1 pr-4 align-top">
                                        {{ calculate_unit($ingredient->unit?->unit, $amount) }}</td>
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
                                {!! text_code_format($step->text, $ingredient_list, $factor, $temp) !!}
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
                            {!! text_code_format($comment->text, $ingredient_list, $factor, $temp) !!}
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
