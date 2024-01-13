<div class="mb-8">
    @isset($title)
        <div class="md:grid md:grid-cols-3 md:gap-6">
            <div class="mb-5 flex justify-between md:col-span-1 md:mb-0">
                <div class="px-4 md:px-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $description ?? '' }}
                    </p>
                </div>

                <div class="px-4 sm:px-0">
                    {{ $aside ?? '' }}
                </div>
            </div>
            <div class="md:col-span-2">
            @endisset
            <div class="bg-white p-6 text-gray-900 shadow-xl dark:bg-gray-800 dark:text-white md:rounded-lg">
                {{ $slot }}
            </div>
            @isset($title)
            </div>
        </div>
    @endisset
</div>
