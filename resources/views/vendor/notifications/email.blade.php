@php
    if(empty($greeting)) {
        $user = Auth::user();
        if(is_null($user))
        {
            if($level === 'error') {
                $greeting = __('Whoops!');
            }
            else {
                $greeting = __('Hello!');
            }
        }
        else {
            $name = $user->name;
            if($user->firstname)
                $name = $user->firstname;
            if($level === 'error') {
                $greeting = __('Whoops, :name!',['name'=>$name]);
            }
            else {
                $greeting = __('Hello :name!',['name'=>$name]);
            }
        }
    }
@endphp
<x-mail::message>
{{-- Greeting --}}
# {{ $greeting }}

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('Regards'),<br>
{{ config('app.name') }}
@endif

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => $actionText,
    ]
) <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
