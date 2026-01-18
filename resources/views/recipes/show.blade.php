<x-app-layout hidetitle>
    <x-slot name="title">{{ __('Recipe') }}</x-slot>
    <x-slot name="subtitle">{{ $recipe->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="recipes.index">{{ __('Recipes') }}</x-link> &gt; <x-link
            route="recipes.show,{{ $recipe->slug }}">
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
            <div class="text-sm">{{ $recipe->description }}</div>
            @if ($picture)
                <div class="mt-2 print:flex print:justify-center"><img
                        class="h-80 w-auto rounded-md object-cover print:h-auto print:w-full" src="{{ $picture }}"></div>
            @endif
            <div class="mt-2 flex justify-between">
                <div>{{ __('Portions:') }} <span>{{ calculate_fraction($portions) }}</span>
                    <div class="ml-8 inline-block print:hidden">
                        <x-button secondary sm :disabled="$portions <= 0.125" :href="url_with_query_string(['portions' => $portions / 2])">½</x-button>
                        <x-button secondary sm :disabled="$portions <= 1" :href="url_with_query_string(['portions' => $portions - 1])">-1</x-button>
                        <x-button secondary sm
                            :href="url_with_query_string(['portions' => $portions + 1])">+1</x-button>
                        <x-button secondary sm
                            :href="url_with_query_string(['portions' => $portions * 2])">&times;2</x-button>
                    </div>
                </div>
                @if($recipe->thermomix)
                    <div><x-recipe-icon.thermomix class="w-8 h-8 text-green-600" /></div>
                @endif
            </div>
            <div class="">{{ __('Time:') }} {{ calculate_time($recipe->time) }}</div>
            <div class="mt-2 print:hidden">
                <x-button icon="thermometer" sm :disabled="$temp == 'C'" :href="url_with_query_string(['temp' => 'C'])">°C</x-button>
                <x-button icon="thermometer" sm :disabled="$temp == 'F'" :href="url_with_query_string(['temp' => 'F'])">°F</x-button>
            </div>
            <div
                class="flex flex-row flex-wrap items-stretch divide-y-2 divide-dotted divide-gray-400 dark:divide-gray-600 sm:flex-nowrap sm:divide-x-2 sm:divide-y-0">
                <div class="flex-none basis-full pb-4 print:!basis-0 sm:basis-80 sm:pr-4">
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
                                        <span>{{ $ingredient->unit }}</span>
                                    </td>
                                @else
                                    <td class="whitespace-nowrap pb-1 pr-1 text-right align-top">
                                        {{ $ingredient->approximately ? __('appr.') : '' }}
                                        <span>{{ $ingredient->amount }}</span>
                                    </td>
                                    <td class="pb-1 pr-4 align-top">
                                        <span>{{ $ingredient->unit }}</span>
                                    </td>
                                @endif
                                <td class="pb-1 align-top"><span class="ingredient transition-colors"
                                        x-orig="{{ $ingredient->reference }}">{{ $ingredient->name }}{{ wrap_if_not_null($ingredient->info, ' (', ')') }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="divide-y divide-gray-400 pb-4 pt-8 dark:divide-gray-600 sm:pl-4 sm:pt-0">
                    @foreach ($steps as $step)
                        <div class="flex break-inside-avoid gap-4 py-2">
                            <div class="text-4xl text-gray-400 dark:text-gray-600 w-12">{{ $loop->iteration }}</div>
                            <div>
                                {!! $step !!}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div
                class="w-full break-inside-avoid divide-y divide-gray-400 border-t-2 border-dotted border-gray-400 pt-8 dark:divide-gray-600 dark:border-gray-600">
                @foreach ($comments as $comment)
                    <div class="py-2">
                        <div>
                            {!! $comment !!}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 flex justify-between gap-4 text-sm text-gray-500">
                <div>
                    @if ($recipe->source)
                        {{ __('Source:') }} {{ $recipe->source }}
                    @endif
                </div>
                <div>
                    {{ __('Last change:') }}
                    {{ $updated_at }}
                    @if ($updated_by)
                        {{ __('by') }}
                        {{ $updated_by }}
                    @endif
                </div>
            </div>
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