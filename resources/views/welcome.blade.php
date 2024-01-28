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
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($category['recipes'] as $recipe)
                        <x-link class="no-underline" route="recipes.show,{{ $recipe->slug }}">
                            <div class="w-full rounded-xl bg-gray-100 p-4 shadow-lg dark:bg-gray-900">
                                @if ($recipe->picture)
                                    <img class="mb-2 mt-0 h-auto w-full rounded-md object-cover"
                                        src="{{ $controller->getImage('recipes/' . $recipe->picture) }}">
                                @endif
                                <div>{{ $recipe->name }}</div>
                            </div>
                        </x-link>
                    @endforeach
                </div>
            @endforeach
        </div>

    </x-page-card>

</x-guest-layout>
