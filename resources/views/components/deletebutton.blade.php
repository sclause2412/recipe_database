@props(['icon' => null, 'title' => null])
@php
    $action = $attributes->whereStartsWith('wire:click')->first();
    if (preg_match('/^([^\(]+)\(?([^\)]*)\)?$/', $action, $matches)) {
        $method = $matches[1];
        $params = $matches[2];
    } else {
        $method = $action;
        $params = '';
    }

    $content = $label ?? (IsSlotEmpty($slot) ? __('Delete') : $slot);

    if ($icon === true) {
        $icon = 'trash';
        $title = $title ?? $content;
        $content = null;
    }

@endphp
<x-button :icon="$icon" :title="$title" {{ $attributes->whereDoesntStartWith('wire:click') }} negative
    x-on:confirm="{
    'title': '{{ __('Really delete?') }}',
    'icon': 'warning',
    'method': '{{ $method }}',
    'params': [{{ $params }}],
    'description': '{{ __('You cannot undo this action!') }}',
    'acceptLabel': '{{ __('Delete') }}',
    'rejectLabel': '{{ __('Cancel') }}'
}">{{ $content }}</x-button>
