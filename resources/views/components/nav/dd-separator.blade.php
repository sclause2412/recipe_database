@props(['require' => '', 'show' => true])
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
            break;
        case 'unelevated':
            $show = (Auth::user()?->admin ?? false) && !(Auth::user()?->elevated ?? false);
            break;
        case 'elevated':
            $show = Auth::user()?->elevated ?? false;
            break;
    }
    
@endphp
@if ($show)
    <div class="border-t border-gray-200 dark:border-gray-600"></div>
@endif
