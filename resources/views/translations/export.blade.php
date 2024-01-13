<x-app-layout>
    <x-slot name="title">{{ __('Export translations') }}</x-slot>
    <x-slot name="subtitle">{{ __('LANG:' . $locale) . ' (' . $locale . ')' }}</x-slot>
    <x-slot name="nav">
        <x-link route="translations.index">{{ __('Translations') }}</x-link> &gt; <x-link
            route="translations.edit,{{ $locale }}">
            {{ __('Edit') }}</x-link>&gt; <x-link route="translations.export,{{ $locale }}">
            {{ __('Export') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('translations.export', ['locale' => $locale])

    </x-page-card>

</x-app-layout>
