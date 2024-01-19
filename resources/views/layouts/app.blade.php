@use(Illuminate\View\ComponentSlot)
@props([
    'pagetitle' => '',
    'title' => new ComponentSlot(),
    'subtitle' => new ComponentSlot(),
    'nav' => new ComponentSlot(),
    'hidetitle' => false,
])
<!DOCTYPE html>
<html class="bg-gray-100 dark:bg-gray-900" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <link href="/favicon.png" rel="icon" type="image/png">
    <link href="/favicon-32.png" rel="icon" sizes="32x32" type="image/png">
    <link href="/favicon-128.png" rel="icon" sizes="128x128" type="image/png">
    <link href="/favicon-180.png" rel="icon" sizes="180x180" type="image/png">
    <link href="/favicon-192.png" rel="icon" sizes="192x192" type="image/png">

    <title>{{ __(config('app.name', 'Laravel')) }}{{ $pagetitle ? ' - ' . $pagetitle : '' }}</title>

    <!-- Scripts -->
    @wireUiScripts
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles

    <script>
        @php
            $color = session('theme.style');
            if (!in_array($color, $colors)) {
                $color = $colors[0];
            }
            switch ($color) {
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
            $font = session('theme.font');
            if (!in_array($font, $fonts)) {
                $font = $fonts[0];
            }
            switch ($font) {
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
    <x-notifications />
    <x-dialog />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 print:!bg-white">
        @livewire('navigation_menu')

        <!-- Page Heading -->
        @if (!IsSlotEmpty($title))
            <header class="{{ $hidetitle ? 'print:hidden' : '' }} bg-white shadow dark:bg-gray-800">
                <div class="mx-auto min-h-full max-w-7xl px-4 py-4">
                    <h1 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                        {{ $title }}
                    </h1>
                    @if (!IsSlotEmpty($subtitle))
                        <h2 class="text-base font-medium leading-tight text-gray-500 dark:text-gray-400">
                            {{ $subtitle }}
                        </h2>
                    @endif
                    @if (!IsSlotEmpty($nav))
                        <nav class="mt-2 text-sm font-medium leading-tight text-gray-500 dark:text-gray-400">
                            {{ $nav }}
                        </nav>
                    @endif
                </div>
            </header>
        @else
            @if (!IsSlotEmpty($nav))
                <header class="{{ $hidetitle ? 'print:hidden' : '' }} bg-white shadow dark:bg-gray-800">
                    <div class="mx-auto min-h-full max-w-7xl px-4 py-4">
                        <nav class="text-sm font-medium leading-tight text-gray-500 dark:text-gray-400">
                            {{ $nav }}
                        </nav>
                    </div>
                </header>
            @endif
        @endif

        <!-- Page Content -->
        <main>
            <div class="mx-auto max-w-7xl px-4 py-10 print:!py-0">
                {{ $slot }}
            </div>
        </main>
    </div>

    @stack('modals')

    @livewireScripts

    @if (session('message'))
        <script type="module">
            window.setTimeout(() => {
                window.$wireui.dialog({
                    title: '{!! session('message')['title'] ?? __(config('app.name')) !!}',
                    description: '{!! session('message')['text'] !!}',
                    icon: '{!! session('message')['icon'] ?? 'info' !!}'
                });

            }, 100);
        </script>
    @endif

</body>

</html>
