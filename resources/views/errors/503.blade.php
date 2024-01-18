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

    <title>{{ config('app.name', 'Laravel') }} - {{ __('Service Unavailable') }}</title>

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

                        <h1 class="text-4xl font-semibold text-indigo-800 dark:text-indigo-200">
                            {{ __('Service Unavailable') }}</h1>

                        <p class="text-indigo-800 dark:text-indigo-200">
                            {{ __('Our page is currently in maintenance mode.') }}</p>

                        <p>
                            {{ __('While you are waiting for the page to return online, you can watch our magnificent programmers doing their work.') }}
                        </p>

                        <p><img class="rounded-3xl" src="{{ url('/cat.png') }}" /></p>
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
