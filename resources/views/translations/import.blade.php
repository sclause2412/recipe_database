<x-app-layout>
    <x-slot name="title">{{ __('Import translations') }}</x-slot>
    <x-slot name="subtitle">{{ __('LANG:' . $locale) . ' (' . $locale . ')' }}</x-slot>
    <x-slot name="nav">
        <x-link route="translations.index">{{ __('Translations') }}</x-link> &gt; <x-link
            route="translations.edit,{{ $locale }}">
            {{ __('Edit') }}</x-link> &gt; <x-link route="translations.import,{{ $locale }}">
            {{ __('Import') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('translations.import', ['locale' => $locale])

    </x-page-card>

</x-app-layout>
