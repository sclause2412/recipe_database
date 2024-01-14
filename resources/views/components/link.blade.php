@props(['route', 'require' => '', 'show' => true, 'button'])
@php
    $link = '';
    if (isset($route)) {
        $params = explode(',', $route);
        $route = array_shift($params);
        $link = route($route, $params);
    }

    $color = 'gray';
    $style = 'secondary';
    switch (strtolower($require)) {
        case 'loggedout':
            $show = is_null(Auth::user());
            break;
        case 'loggedin':
            $show = !is_null(Auth::user());
            break;
        case 'admin':
            $show = Auth::user()?->admin ?? false;
            $color = Auth::user()?->elevated ?? false ? 'green' : 'red';
            $style = Auth::user()?->elevated ?? false ? 'positive' : 'negative';
            break;
        case 'unelevated':
            $show = (Auth::user()?->admin ?? false) && !(Auth::user()?->elevated ?? false);
            $color = 'red';
            $style = 'negative';
            break;
        case 'elevated':
            $show = Auth::user()?->elevated ?? false;
            $color = 'green';
            $style = 'positive';
            break;
    }

    $attr = $attributes->merge(['style' => $style]);

@endphp
@isset($button)
    @if ($show)
        <x-button :attributes="$attr" :href="$link">{!! $slot !!}</x-button>
    @else
        <x-button :attributes="$attr">{!! $slot !!}</x-button>
    @endif
@else
    @if ($show)
        <a {!! $attributes->merge(['href' => $link])->class([
                'inline-flex items-center underline hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800',
                'text-gray-600 dark:text-gray-400' => $color == 'gray',
                'text-green-600 dark:text-green-400' => $color == 'green',
                'text-red-600 dark:text-red-400' => $color == 'red',
            ]) !!}>{!! $slot !!}</a>
    @else
        <span {!! $attributes->class(['inline-flex items-center text-gray-400 dark:text-gray-600']) !!}>{!! $slot !!}</span>
    @endif
@endisset
