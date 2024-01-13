@props(['label' => null, 'cornerHint' => null, 'name' => '', 'options' => []])

@if ($label || $cornerHint)
    <div class="{{ !$label && $cornerHint ? 'justify-end' : 'justify-between items-end' }} mb-1 flex">
        @if ($label)
            <x-dynamic-component :component="WireUi::component('label')" :label="$label" :has-error="$errors->has($name)" />
        @endif

        @if ($cornerHint)
            <x-dynamic-component :component="WireUi::component('label')" :label="$cornerHint" :has-error="$errors->has($name)" />
        @endif
    </div>
@endif

<div class="flex flex-wrap justify-between">
    @foreach ($options as $v => $t)
        <div>
            <x-radio {{ $attributes }} label="{{ $t }}" name="{{ $name }}"
                value="{{ $v }}" />
        </div>
    @endforeach
</div>
