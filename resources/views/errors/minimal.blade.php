@php
    use App\Http\Controllers\TranslationController;
    app()->setLocale(session('locale', request()->getPreferredLanguage(TranslationController::available_locales())));
@endphp
<!DOCTYPE html>
<html class="bg-gray-100 dark:bg-gray-900" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <script>
        @php
            switch (session('theme.style')) {
                case 'dark':
                    print 'localStorage.theme_style = \'dark\';';
                    break;
                case 'light':
                    print 'localStorage.theme_style = \'light\';';
                    break;
                case 'system':
                    print 'localStorage.removeItem(\'theme_style\');';
                    break;
            }
        @endphp

        if (localStorage.theme_style === 'dark' || (!('theme_style' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        };

        @php
            switch (session('theme.font')) {
                case 'sans':
                    print 'localStorage.theme_font = \'sans\';';
                    break;
                case 'serif':
                    print 'localStorage.theme_font = \'serif\';';
                    break;
                case 'mono':
                    print 'localStorage.theme_font = \'mono\';';
                    break;
            }
        @endphp

        if (localStorage.theme_font === 'serif') {
            document.documentElement.classList.add('font-serif');
        } else if (localStorage.theme_font === 'mono') {
            document.documentElement.classList.add('font-mono');
        } else {
            document.documentElement.classList.add('font-sans');
        };
    </script>
</head>

<body class="text-gray-800 antialiased dark:text-gray-200">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- Page Content -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-10">
                <div class="flex flex-col items-center bg-gray-100 dark:bg-gray-900 sm:justify-center">
                    <div
                        class="mt-6 w-full space-y-4 bg-white px-6 py-4 text-center text-gray-800 shadow-md dark:bg-gray-800 dark:text-gray-200 sm:max-w-lg sm:rounded-lg">
                        <p class="text-lg font-medium text-indigo-800 dark:text-indigo-200">{{ __('Oops!') }}</p>
                        <p>{{ __('There went something wrong.') }}</p>
                        <h1 class="text-6xl font-semibold tracking-widest text-indigo-800 dark:text-indigo-200">
                            @yield('code')</h1>
                        <h2 class="text-xl font-medium">
                            @yield('message')</h2>

                        <p class="truncate text-xs text-gray-400 dark:text-gray-600">{{ url()->full() }}</p>

                        <p class="text-indigo-800 dark:text-indigo-200">
                            {{ __('It is a shame that this error occured!') }}</p>
                        <p>
                            {{ __('Even if our programmers are the best in the whole wide world, they are sometimes distracted by a bird. We will inform them to fix the problem.') }}
                        </p>

                        <p><img class="rounded-3xl" src="{{ url('/cat.png') }}" /></p>
                        <p>{{ __('In the meantime you can try to go back to the previous page.') }}
                        </p>

                        <p><a class="inline-flex items-center rounded-md border border-transparent bg-gray-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition duration-150 ease-in-out hover:bg-gray-700 focus:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-900 dark:bg-gray-200 dark:text-gray-800 dark:hover:bg-white dark:focus:bg-white dark:focus:ring-offset-gray-800 dark:focus:ring-offset-gray-800 dark:active:bg-gray-300"
                                href="{{ backorhome()->getTargetUrl() }}">{{ __('Go back') }}</a></p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @if (is_null(session('theme.style')))
        <script type="module">
            if (window.matchMedia !== undefined) {
                if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    document.documentElement.classList.add('dark');
                }
            }
        </script>
    @endif

</body>

</html>
