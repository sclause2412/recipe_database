@props(['url' => false, 'form' => false])
@if ($form)
    <x-form :url="$url" {{ $attributes->class(['table-row']) }}>
        {{ $slot }}
    </x-form>
@else
    <tr {{ $attributes->merge([])->class([]) }} @if ($url) data-url="{{ $url }}" @endif>
        {{ $slot }}
    </tr>
@endif
