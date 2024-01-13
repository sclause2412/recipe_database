<x-guest-layout>

    <x-page-card>
        <div class="prose max-w-none p-8 dark:prose-invert">
            <h1>{{ __('Recipe database') }}</h1>
            <p>{{ __('This is our private collection of recipes.') }}</p>
            <p>{{ __('Some recipes are taken from other pages and all credits are owned by them. We just put them here to make it easier for us to find our loved recipes again.') }}
            </p>
            <p>{{ __('Some recipes are modified from their original version to reflect our own experience and taste.') }}
            </p>
            <p>{{ __('Feel free to use the recipes. We do not give any guarantee for success.') }}</p>
        </div>

    </x-page-card>

    <x-page-card>
        <div class="prose max-w-none p-8 dark:prose-invert">
            <h1>{{ __('Some random recipes') }}</h1>
            @foreach ($data as $category)
                <h2 claxss="mb-2 mt-8 text-xl font-bold">{{ $category['name'] }}</h2>
                <div class="flex flex-wrap justify-between gap-4">
                    @foreach ($category['recipes'] as $slug => $recipe)
                        <x-link class="no-underline" route="recipes.show,{{ $slug }}">
                            <div class="rounded-xl bg-gray-100 p-4 shadow-lg dark:bg-gray-900">{{ $recipe }}
                            </div>
                        </x-link>
                    @endforeach
                </div>
            @endforeach
        </div>

    </x-page-card>

</x-guest-layout>
