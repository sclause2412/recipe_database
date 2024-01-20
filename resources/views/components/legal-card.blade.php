<div class="flex flex-col items-center bg-gray-100 dark:bg-gray-900 sm:justify-center">
    <div>
        <x-logo.auth />
    </div>

    <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md dark:bg-gray-800 sm:max-w-2xl sm:rounded-lg">
        <div class="prose dark:prose-invert">
            {{ $slot }}
        </div>
    </div>
</div>
