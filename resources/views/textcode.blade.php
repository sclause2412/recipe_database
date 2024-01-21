<x-app-layout>
    <x-slot name="title">{{ __('Text codes') }}</x-slot>
    <x-slot name="nav">
        <x-link route="textcode">{{ __('Text codes') }}</x-link>
    </x-slot>

    <x-page-card>

        <x-slot name="title">{{ __('Codes') }}</x-slot>
        <x-table>
            <x-slot name="header">
                <x-table.head>{{ __('Type') }}</x-table.head>
                <x-table.head>{{ __('Code') }}</x-table.head>
                <x-table.head>{{ __('Information') }}</x-table.head>
            </x-slot>
            <x-table.row>
                <x-table.cell>{{ __('Ingredient') }}</x-table.cell>
                <x-table.cell>[{{ __('REFERENCE') }}]</x-table.cell>
                <x-table.cell>{{ __('Show ingredient at this point. Use the reference tag since it is unique within a recipe and will not change even if the ingredient is changed (by edit).') }}</x-table.cell>
            </x-table.row>
            <x-table.row>
                <x-table.cell>{{ __('Amount') }}</x-table.cell>
                <x-table.cell>[123]</x-table.cell>
                <x-table.cell>{{ __('Amount of the ingredient. Don\'t use plain numbers without brackets as these will not be recalculated if the portions are changed.') }}</x-table.cell>
            </x-table.row>
            <x-table.row>
                <x-table.cell>{{ __('Temperature') }}</x-table.cell>
                <x-table.cell>[T180]</x-table.cell>
                <x-table.cell>{{ __('Temperature is always given in °C and will be automatically shown in correct format.') }}</x-table.cell>
            </x-table.row>
            <x-table.row>
                <x-table.cell>{{ __('Icon') }}</x-table.cell>
                <x-table.cell>:icon:</x-table.cell>
                <x-table.cell>{{ __('See list below.') }}</x-table.cell>
            </x-table.row>
            <x-table.row>
                <x-table.cell>{{ __('Thermomix instruction') }}</x-table.cell>
                <x-table.cell>~time/temp/dir/speed~</x-table.cell>
                <x-table.cell>{{ __('See list below.') }}</x-table.cell>
            </x-table.row>
            <x-table.row>
                <x-table.cell>{{ __('Color') }}</x-table.cell>
                <x-table.cell>{{ '{' . __('color') . '}' }}</x-table.cell>
                <x-table.cell>{{ __('See list below.') }}</x-table.cell>
            </x-table.row>

        </x-table>

    </x-page-card>

    <x-page-card>

        <x-slot name="title">{{ __('Icons') }}</x-slot>
        <div class="grid grid-cols-4 gap-4">
            @foreach (text_code_icons() as $icon)
                <div>
                    <x-recipe-icon name="{{ $icon }}" /> {{ $icon }}
                </div>
            @endforeach
        </div>

    </x-page-card>

    <x-page-card>

        <x-slot name="title">{{ __('Colors') }}</x-slot>
        <div>{{ __('Use {-} to switch off color') }}</div>
        <div class="grid grid-cols-4 gap-4">
            @foreach (text_code_colors() as $color => $css)
                <div class="{{ $css }}">{{ $color }}</div>
            @endforeach
        </div>

    </x-page-card>

    <x-page-card>

        <x-slot name="title">{{ __('Thermomix instructions') }}</x-slot>
        <x-list>
            <x-list.item term="time">
                {{ __('Time is given in seconds or minutes (M). To indicate a time span use dash (e.g. 1-2M).') }}
            </x-list.item>
            <x-list.item term="temp">
                {{ __('Temperatur is given in °C. To indicate Varoma mode use V. If you don\'t need temperature keep the field empty or set to 0.') }}
            </x-list.item>
            <x-list.item term="speed">
                {{ __('Speed is given in steps of 0.5. To indicate dough mode use D. To indicate stir mode use S. To indicate reverse direction use negative number. If you don\'t need speed keep the field empty or set to 0.') }}
            </x-list.item>
            <x-list.item term="{{ __('Possible combinations') }}">
                time/temp/speed<br />
                time/speed<br />
                speed<br />
            </x-list.item>
        </x-list>
        <x-table>
            <x-slot name="header">
                <x-table.head>{{ __('Instruction') }}</x-table.head>
                <x-table.head>{{ __('Shown as') }}</x-table.head>
            </x-slot>
            @foreach (['0/0/0', '5M/100/1', '3/5', '3M/70/-2', '2M/D', '1M/V/2.5', '2-4M/S', '4', '90/3', '90M/S', '30-60/1', '30-90/-1'] as $inst)
                <x-table.row>
                    <x-table.cell>~{{ $inst }}~</x-table.cell>
                    <x-table.cell>{!! text_code_format('~' . $inst . '~') !!} </x-table.cell>
                </x-table.row>
            @endforeach

        </x-table>

    </x-page-card>

</x-app-layout>
