@props(['pagetitle' => '', 'title' => new \Illuminate\View\ComponentSlot(), 'subtitle' => new \Illuminate\View\ComponentSlot(), 'nav' => new \Illuminate\View\ComponentSlot()])
<!DOCTYPE html>
<html class="bg-white font-sans" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <meta content="{{ csrf_token() }}" name="csrf-token">

    <title>{{ config('app.name', 'Laravel') }}{{ $pagetitle ? ' - ' . $pagetitle : '' }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.scss'])

    <!-- Styles -->
</head>

<body class="text-black antialiased">

    <div class="bg-white">

        <!-- Page Content -->
        <main>
            <div class="mx-auto px-2 py-2">
                {{ $slot }}
            </div>
        </main>
    </div>

</body>

</html>
