@props(['sortable', 'direction'])
<th
    {{ $attributes->merge([])->class(['px-1 sm:px-3 py-1 sm:py-2 bg-gray-50 dark:bg-gray-900 text-left text-xs leading-4 font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider', 'cursor-pointer' => isset($sortable)]) }}>
    <div class="flex items-center">
        {{ $slot }}
        @isset($sortable)
            <div class="pl-2">
                @isset($direction)
                    @if ($direction == 'asc')
                        <x-icon class="h-4 w-4 text-green-600 dark:text-green-400" name="sort-ascending" />
                    @else
                        <x-icon class="h-4 w-4 text-green-600 dark:text-green-400" name="sort-descending" />
                    @endif
                @else
                    <x-icon class="h-4 w-4 text-gray-400 dark:text-gray-600" name="sort-ascending" />
                @endisset
            </div>
        @endisset
    </div>
</th>
