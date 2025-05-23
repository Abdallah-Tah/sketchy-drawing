<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-b from-purple-50 to-white dark:from-zinc-900 dark:to-zinc-800">

    <!-- Global Top Header with Navigation -->
    <header class="fixed top-0 left-0 w-full z-30">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-3">
                <img src="{{ asset('icon.png') }}" alt="Kids App Logo" class="h-10 w-10 animate-bounce">
                <span
                    class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-500 bg-clip-text text-transparent dark:from-purple-400 dark:to-pink-300">Kids
                    App</span>
            </div>

            <!-- Navigation -->
            <nav class="flex items-center">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center px-5 py-2.5 text-base font-medium text-white bg-gradient-to-r from-purple-500 to-pink-500 rounded-full shadow-lg hover:shadow-xl hover:scale-105 dark:from-purple-600 dark:to-pink-600 transform transition-all duration-200 ease-in-out group">
                    <span class="relative">
                        <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition-transform duration-200"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </span>
                    Back to Fun!
                </a>
            </nav>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto pt-20">
        {{ $slot }}
    </main>

    @fluxScripts
</body>

</html>
