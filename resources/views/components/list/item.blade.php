<div class="py-2 sm:grid sm:grid-cols-3 sm:gap-4">
    <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $term ?? '' }}</dt>
    <dd class="mt-1 text-gray-900 dark:text-gray-100 sm:col-span-2 sm:mt-0">
        @isset($right)
            <div class="sm:flex sm:justify-between sm:space-x-2">
                <div class="w-full">{{ $slot }}</div>
                <div class="mt-1 sm:mt-0">{{ $right }}</div>
            </div>
        @else
            {{ $slot }}
            @endif
        </dd>
    </div>
