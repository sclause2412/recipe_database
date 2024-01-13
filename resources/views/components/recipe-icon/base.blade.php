@if ($phosphor)
    <x-icon :attributes="$attributes" :name="$name" class="inline h-5 w-5" />
@else
    <x-dynamic-component :attributes="$attributes" :component="'recipe-icon.' . $name" class="inline h-5 w-5" />
@endif
