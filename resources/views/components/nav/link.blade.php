@props(['active' => null, 'route', 'require' => '', 'show' => true])
@php
    $link = '';
    if (isset($route)) {
        $params = explode(',', $route);
        $route = array_shift($params);
        $link = route($route, $params);
        $active = $active ?? request()->routeIs($route);
    }

    $active = $active ?? false;

    switch (strtolower($require)) {
        case 'loggedout':
            $show = is_null(Auth::user());
            break;
        case 'loggedin':
            $show = !is_null(Auth::user());
            break;
        case 'admin':
            $show = Auth::user()?->admin ?? false;
            break;
        case 'unelevated':
            $show = (Auth::user()?->admin ?? false) && !(Auth::user()?->elevated ?? false);
            break;
        case 'elevated':
            $show = Auth::user()?->elevated ?? false;
            break;
    }

    if ($link) {
        $tag = 'a';
    } else {
        $tag = 'span';
    }

@endphp
@if ($show)
    <{{ $tag }}
        {{ $attributes->merge(['href' => $link])->class([
                'block inline-flex items-center w-full sm:w-auto px-3 py-2 border-l-4 sm:border-l-0 sm:border-b-2 text-left text-base sm:text-sm font-medium focus:outline-none transition duration-150 ease-in-out',
                'border-indigo-400 dark:border-indigo-600 text-indigo-700 dark:text-indigo-300 bg-indigo-50 sm:bg-transparent dark:bg-indigo-900 sm:dark:bg-transparent focus:text-indigo-800 dark:focus:text-indigo-200 focus:bg-indigo-100 dark:focus:bg-indigo-900 focus:border-indigo-700 dark:focus:border-indigo-300' => $active,
                'border-transparent text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-gray-100 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600' => !$active,
            ]) }}>
        {{ $slot }}
        </{{ $tag }}>
@endif
