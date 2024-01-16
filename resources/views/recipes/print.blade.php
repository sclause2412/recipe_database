@php
    $factor = $portions / ($recipe->portions ?? 1);
@endphp
<x-print-layout>
    <div>
        <h1 class="text-4xl font-bold">{{ $recipe->name }}</h1>
        <h2 class="text-xl font-bold">{{ $recipe->category?->name }}</h2>
        <div class="mt-2">{{ __('Portions:') }} <div class="inline-block w-12">
                {{ $portions }}</div>
        </div>
        <div class="">{{ __('Time:') }} {{ calculate_time($recipe->time) }}</div>
        <table class="mt-8 w-full">
            <tr>
                <td class="w-1/4 border-r-2 border-dotted border-gray-400 pb-4 pr-4 align-top">
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
                                <td class="pb-1 pr-1 text-right align-top"> {!! is_null($ingredient->amount)
                                    ? ''
                                    : calculate_number($ingredient->amount * $factor, $ingredient->unit?->fraction ?? false) !!} </td>
                                <td class="pb-1 pr-4 align-top">{{ $ingredient->unit?->unit }}</td>
                                <td class="pb-1 align-top"><span class="ingredient transition-colors"
                                        x-orig="{{ $ingredient->reference }}">{{ $ingredient->ingredient?->name }}{{ is_null($ingredient->ingredient?->info) ? '' : ' (' . $ingredient->ingredient?->info . ')' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td class="w-3/4 divide-y divide-gray-400 pb-4 pl-4 align-top">
                    @foreach ($steps as $step)
                        <div class="flex gap-4 py-2">
                            <div class="text-4xl text-gray-400">{{ $step->step }}</div>
                            <div>
                                {!! text_code_format($step->text, $ingredient_list, $factor, $temp) !!}
                            </div>
                        </div>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td class="w-full divide-y divide-gray-400 border-t-2 border-dotted border-gray-400 pt-8 align-top"
                    colspan="2">
                    @foreach ($comments as $comment)
                        <div class="py-2">
                            <div>
                                {!! text_code_format($comment->text, $ingredient_list, $factor, $temp) !!}
                            </div>
                        </div>
                    @endforeach
                </td>
            </tr>
        </table>
    </div>

</x-print-layout>
