@props(['method' => 'POST'])
<form {!! $attributes->merge(['enctype' => 'multipart/form-data']) !!} method="POST">
    @csrf
    @method(strtoupper($method))

    @if (false && $errors->any())
        <div class="mb-4">
            <div class="font-medium text-red-600 dark:text-red-400">{{ __('Whoops! Something went wrong.') }}</div>
        </div>
    @else
        <div class="hidden"></div>
    @endif

    {{ $slot }}
</form>
