<div class="container mx-auto px-4 py-12">
    <!-- Header -->
    <div class="text-center mb-12">
        <div class="flex justify-center mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 42"
                class="h-16 w-16 fill-current text-purple-600 dark:text-purple-400">
                <path fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.2 5.633 8.6.855 0 5.633v26.51l16.2 9 16.2-9v-8.442l7.6-4.223V9.856l-8.6-4.777-8.6 4.777V18.3l-5.6 3.111V5.633ZM38 18.301l-5.6 3.11v-6.157l5.6-3.11V18.3Zm-1.06-7.856-5.54 3.078-5.54-3.079 5.54-3.078 5.54 3.079ZM24.8 18.3v-6.157l5.6 3.111v6.158L24.8 18.3Zm-1 1.732 5.54 3.078-13.14 7.302-5.54-3.078 13.14-7.3v-.002Zm-16.2 7.89 7.6 4.222V38.3L2 30.966V7.92l5.6 3.111v16.892ZM8.6 9.3 3.06 6.222 8.6 3.143l5.54 3.08L8.6 9.3Zm21.8 15.51-13.2 7.334V38.3l13.2-7.334v-6.156ZM9.6 11.034l5.6-3.11v14.6l-5.6 3.11v-14.6Z" />
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
            Welcome to Kids Drawing & Learning
        </h1>
        <p class="text-xl text-gray-600 dark:text-gray-300">
            Choose your fun activity below!
        </p>
    </div>

    <!-- Activity Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <!-- Sketch Board Card -->
        <a href="{{ route('sketch') }}" wire:navigate
            class="transform hover:scale-105 transition-transform duration-300">
            <div class="bg-white dark:bg-zinc-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div
                        class="flex items-center justify-center h-24 w-24 rounded-full bg-purple-100 dark:bg-purple-900 mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-purple-600 dark:text-purple-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M4 2a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V4a2 2 0 00-2-2H4zm0 2h12v12H4V4zm3 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-2">Sketch Board</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-center">
                        Let your creativity flow! Draw anything you can imagine.
                    </p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/30 px-6 py-4">
                    <div class="text-purple-600 dark:text-purple-400 text-center font-medium">
                        Start Drawing →
                    </div>
                </div>
            </div>
        </a>

        <!-- Math Kids Game Card -->
        <a href="{{ route('math-kids') }}" wire:navigate
            class="transform hover:scale-105 transition-transform duration-300">
            <div class="bg-white dark:bg-zinc-700 rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div
                        class="flex items-center justify-center h-24 w-24 rounded-full bg-green-100 dark:bg-green-900 mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-green-600 dark:text-green-400"
                            viewBox="0 0 20 20" fill="currentColor">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center mb-2">Math Games</h2>
                    <p class="text-gray-600 dark:text-gray-300 text-center">
                        Have fun while learning math with interactive games!
                    </p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/30 px-6 py-4">
                    <div class="text-green-600 dark:text-green-400 text-center font-medium">
                        Start Playing →
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>
