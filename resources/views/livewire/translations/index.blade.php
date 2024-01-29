<div class="space-y-2">

    <x-table>
        <x-slot name="header">
            <x-table.head>{{ __('Language') }}</x-table.head>
            <x-table.head>{{ __('Entries') }}</x-table.head>
            <x-table.head>{{ __('status.Done') }}</x-table.head>
            <x-table.head />
        </x-slot>
        @forelse ($locales as $locale)
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell class="align-top">{{ $locale->locale }}</x-table.cell>
                <x-table.cell class="align-top">{{ $locale->_count }}</x-table.cell>
                <x-table.cell class="align-top">{{ $locale->_done }}</x-table.cell>
                <x-table.cell buttons>
                    @if (check_write('translate'))
                        <x-link button icon="pencil" route="translations.edit,{{ $locale->locale }}"
                            title="{{ __('Edit') }}" />
                    @elseif(check_read('translate'))
                        <x-link button icon="eye" route="translations.edit,{{ $locale->locale }}"
                            title="{{ __('Show') }}" />
                    @endif
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row wire:loading.class.delay="opacity-50">
                <x-table.cell colspan="10">
                    <div class="py-10 text-center text-gray-500">{{ __('This table is empty...') }}</div>
                </x-table.cell>
            </x-table.row>
        @endforelse
    </x-table>
    {{ $locales->links() }}
</div>
