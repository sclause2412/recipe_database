<x-mail::message>
# {{$title}}

{!! $content !!}

<x-mail::button :url="$url">
Go to page
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
