<x-app-layout>
    <x-slot name="title">{{ __('Unit details') }}</x-slot>
    <x-slot name="subtitle">{{ $unit->name }}</x-slot>
    <x-slot name="nav">
        <x-link route="units.index">{{ __('Units') }}</x-link> &gt; <x-link route="units.show,{{ $unit->id }}">
            {{ __('Details') }}</x-link>
    </x-slot>

    <x-page-card>
        <x-slot name="title">
            {{ __('Unit information') }}
        </x-slot>

        <x-list>
            <x-list.item>
                <x-slot name="term">{{ __('Name') }}</x-slot>
                {{ $unit->name }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Unit') }}</x-slot>
                {{ $unit->unit }}
            </x-list.item>
            <x-list.item>
                <x-slot name="term">{{ __('Fraction') }}</x-slot>
                {{ $unit->fraction ? __('Yes') : __('No') }}
            </x-list.item>
        </x-list>
    </x-page-card>

    <x-page-card>
        <x-slot name="title">
            {{ __('Recipes') }}
        </x-slot>

        @livewire('units.recipes', ['unit' => $unit])

    </x-page-card>

</x-app-layout>
