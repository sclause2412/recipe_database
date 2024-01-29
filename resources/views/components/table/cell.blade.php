<td
    {{ $attributes->merge([])->class(['px-1 sm:px-3 py-1 sm:py-2 text-sm leading-5 text-gray-800 dark:text-gray-200 ']) }}>
    @isset($buttons)
        <div class="flex flex-row justify-end gap-1">
        @endisset
        {{ $slot }}
        @isset($buttons)
        </div>
    @endisset
</td>
