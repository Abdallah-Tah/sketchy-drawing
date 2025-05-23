<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-gradient-to-b from-purple-50 to-white dark:from-zinc-900 dark:to-zinc-800">

    <!-- Global Top Header with Navigation -->
    <header class="fixed top-0 left-0 w-full bg-white dark:bg-zinc-900 shadow-md z-30">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400"
                    viewBox="0 0 40 42" fill="currentColor">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z" />
                </svg>
                <span class="text-xl font-bold text-gray-900 dark:text-white">Kids App</span>
            </div>

            <!-- Navigation -->
            <nav class="space-x-6">
                <a href="{{ route('sketch') }}"
                    class="text-gray-700 dark:text-gray-200 hover:text-purple-600 dark:hover:text-purple-400 font-medium transition">
                    Sketch
                </a>
                <a href="{{ route('math-kids') }}"
                    class="text-gray-700 dark:text-gray-200 hover:text-green-600 dark:hover:text-green-400 font-medium transition">
                    Math
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
