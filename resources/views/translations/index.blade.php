<x-app-layout>
    <x-slot name="title">{{ __('Translations') }}</x-slot>
    <x-slot name="nav">
        <x-link route="translations.index">{{ __('Translations') }}</x-link>
    </x-slot>

    <x-page-card>
        @livewire('translations.index')

        @if (check_write('translate'))
            <div class="flex flex-wrap gap-4" x-data="{ locale: '' }">
                <x-input x-model="locale">
                    <x-slot name="append">
                        <div class="absolute inset-y-0 right-0 flex items-center p-0.5">
                            <x-button class="h-full rounded-r-md" flat icon="plus" squared
                                x-on:click="if(locale=='')
                                {
                                    $wireui.notify({
                                        icon: 'error',
                                        title: '{{ __('Missing language!') }}',
                                        description: '{{ __('Please enter a valid language (use locale string, e.g. en or en_US) before pressing the button.') }}',
                                    });
                                }
                                else
                                {
                                    location.href='{{ route('translations.edit', 'LOC') }}'.replace('LOC',locale);
                                }">{{ __('Add new language') }}
                            </x-button>
                        </div>
                    </x-slot>
                </x-input>
            </div>
            <div class="mt-4 flex flex-wrap gap-4">
                <x-link button route="translations.read">{{ __('Scan files for translations') }}</x-link>
                <x-button secondary
                    x-on:confirm="{
                    'title': '{{ __('Write translations files') }}',
                    'icon': 'warning',
                    'description': '{{ __('If you write translations to files all existing files will be overwritten! The changes are visible to users immediately.') }}',
                    'accept': { label: '{{ __('OK') }}', url: '{{ route('translations.write') }}'},
                    'rejectLabel': '{{ __('Cancel') }}'
                }">{{ __('Write translations to files') }}</x-button>
                <x-link button
                    route="translations.mode">{{ session('translate_mode', false) ? __('Disable translation display') : __('Enable translation display') }}</x-link>
            </div>
        @endif
    </x-page-card>

</x-app-layout>
