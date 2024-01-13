<x-app-layout>
    <x-slot name="title">{{ __('Edit translations') }}</x-slot>
    <x-slot name="subtitle">{{ __('LANG:' . $locale) . ' (' . $locale . ')' }}</x-slot>
    <x-slot name="nav">
        <x-link route="translations.index">{{ __('Translations') }}</x-link> &gt; <x-link
            route="translations.edit,{{ $locale }}">
            {{ __('Edit') }}</x-link>
    </x-slot>

    <x-page-card>

        @livewire('translations.edit', ['locale' => $locale])

        <div class="flex gap-4">
            @if (check_write('translate'))
                <x-link button
                    route="translations.sync,{{ $locale }}">{{ __('Sync from other languages') }}</x-link>
                <x-link button
                    route="translations.import,{{ $locale }}">{{ __('Import language from JSON file') }}</x-link>
            @endif
            <x-link button
                route="translations.export,{{ $locale }}">{{ __('Export language to JSON file') }}</x-link>
        </div>

    </x-page-card>

</x-app-layout>
