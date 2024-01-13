@props(['align' => 'left', 'require' => '', 'show' => true])
@php
    switch ($align) {
        case 'right':
            $alignclass = 'sm:right-0';
            break;
        default:
            $alignclass = 'sm:left-0';
    }

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
        case 'content':
            $show = !IsSlotEmpty($slot);
            break;
    }

@endphp
@if ($show)
    <div @click.away="open = false" @click="open = ! open" @close.stop="open = false"
        {{ $attributes->merge()->class([
                'relative block sm:inline-flex sm:items-center px-3 py-2 border-l-4 sm:border-l-0 sm:border-b-2 text-left text-base sm:text-sm font-medium focus:outline-none transition duration-150 ease-in-out',
                'text-gray-600 dark:text-gray-200 hover:text-gray-800 dark:hover:text-gray-100 focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 cursor-pointer',
            ]) }}
        x-bind:class="open ?
            'border-indigo-400 dark:border-indigo-600 focus:border-indigo-700 dark:focus:border-indigo-300' :
            'border-transparent hover:border-gray-300 dark:hover:border-gray-600 focus:border-gray-300 dark:focus:border-gray-600'"
        x-data="{ open: false }">
        <div class="flex items-center">
            {{ $trigger }}
            <x-icon class="-mr-0.5 ml-2 h-4 w-4" name="caret-down" />
        </div>

        <div :class="{ 'block': open, 'hidden': !open }" @click="open = false"
            class="{{ $alignclass }} z-50 hidden sm:absolute sm:top-full sm:mt-1 sm:w-max sm:min-w-full sm:max-w-sm">
            <div
                class="flex flex-col pl-4 sm:-mt-px sm:rounded-b-md sm:border sm:border-t-0 sm:border-gray-200 sm:bg-white sm:pl-0 sm:dark:border-gray-900 sm:dark:bg-gray-700">
                {{ $slot }}
            </div>
        </div>
    </div>
@endif
