<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            {{ __(config('app.name')) }}
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            {{ __('© :year :name', ['year' => now()->year, 'name' => config('app.name', 'Laravel')]) }}
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>
