@props(['id' => 'table_' . md5(uniqid())])
<div class="overflow-visible rounded-md border-2 border-gray-300 dark:border-gray-700">
    <table {{ $attributes->merge([])->class(['min-w-full divide-y-2 divide-gray-300 dark:divide-gray-700']) }}
        id="{{ $id }}">
        @isset($header)
            <thead>
                <tr>
                    {{ $header }}
                </tr>
            </thead>
        @endisset
        <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
            {{ $slot }}
        </tbody>
        @isset($footer)
            <tfoot>
                {{ $footer }}
            </tfoot>
        @endisset
    </table>
</div>
