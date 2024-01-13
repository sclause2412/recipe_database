<x-guest-layout>
    <div class="flex flex-col items-center">
        <div>
            <x-logo.auth />
        </div>

        <div
            class="w-full sm:max-w-2xl mt-6 p-6 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg prose dark:prose-invert">
            {!! $policy !!}
        </div>
    </div>
</x-guest-layout>
