@props(['require' => '', 'show' => true, 'circle' => false, 'icon' => null, 'style' => 'secondary'])
@php

    switch (strtolower($require)) {
        case 'loggedout':
            $show = is_null(Auth::user());
            break;
        case 'loggedin':
            $show = !is_null(Auth::user());
            break;
        case 'admin':
            $show = Auth::user()?->admin ?? false;
            $style = Auth::user()?->elevated ?? false ? 'positive' : 'negative';
            break;
        case 'unelevated':
            $show = (Auth::user()?->admin ?? false) && !(Auth::user()?->elevated ?? false);
            $style = 'negative';
            break;
        case 'elevated':
            $show = Auth::user()?->elevated ?? false;
            $style = 'positive';
            break;
    }

    /* for WireUI v2
    $oClass = new ReflectionClass(WireUi\Enum\Packs\Color::class);
    foreach (array_values($oClass->getConstants()) as $color) {
        if ($attributes->has($color)) {
            $style = null;
        }
    }
    */
    $oClass = new \WireUi\View\Components\Button();
    foreach ($oClass->outlineColors() as $color => $value) {
        if ($attributes->has($color)) {
            $style = null;
        }
    }

    if (is_null($style)) {
        $attr = $attributes->merge();
    } else {
        $attr = $attributes->merge([$style => true]);
    }

    $iconSize = 'w-4 h-4';
    if (isset($attr['2xs'])) {
        $iconSize = 'w-2 h-2';
    }
    if (isset($attr['xs'])) {
        $iconSize = 'w-3 h-3';
    }
    if (isset($attr['sm'])) {
        $iconSize = 'w-3.5 h-3.5';
    }
    if (isset($attr['md'])) {
        $iconSize = 'w-4 h-4';
    }
    if (isset($attr['lg'])) {
        $iconSize = 'w-5 h-5';
    }
    if (isset($attr['xl'])) {
        $iconSize = 'w-6 h-6';
    }
    if (isset($attr['2xl'])) {
        $iconSize = 'w-7 h-7';
    }

@endphp
@if ($show)
    @if ($circle)
        <x-mini-button :attributes="$attr" rounded>
            @if ($icon)
                <x-icon :name="$icon" class="{{ $iconSize }} shrink-0" />
            @endif
            {!! $slot !!}
        </x-mini-button>
    @else
        <x-wui-button :attributes="$attr">
            @if ($icon)
                <x-icon :name="$icon" class="{{ $iconSize }} shrink-0" />
            @endif
            {!! $slot !!}
        </x-wui-button>
    @endif
@endif
