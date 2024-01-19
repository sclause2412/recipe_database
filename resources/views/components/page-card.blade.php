<div class="print:border-b-solid mb-8 print:border-t-2 print:border-black print:first:border-none">
    @isset($title)
        <div class="print:!block md:grid md:grid-cols-3 md:gap-6">
            <div class="mb-5 flex justify-between md:col-span-1 md:mb-0">
                <div class="px-4 print:!px-0 md:px-0">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $title }}</h3>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ $description ?? '' }}
                    </p>
                </div>
                @isset($aside)
                    <div class="px-4 print:!pl-4 print:!pr-0 sm:px-0">
                        {{ $aside }}
                    </div>
                @endisset
            </div>
            <div class="md:col-span-2">
            @endisset
            <div
                class="bg-white p-6 text-gray-900 shadow-xl dark:bg-gray-800 dark:text-white print:!rounded-none print:!px-0 print:!shadow-none md:rounded-lg">
                {{ $slot }}
            </div>
            @isset($title)
            </div>
        </div>
    @endisset
</div>
