<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sketch Board') }}</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    @livewireStyles
</head>
<body>
    <header>
        <h1>Children's Sketch Board</h1>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Sketch Board. All rights reserved.</p>
    </footer>

    <script src="{{ mix('js/app.js') }}"></script>
    @livewireScripts
</body>
</html>