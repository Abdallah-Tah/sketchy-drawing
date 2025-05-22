<div x-data="{
        alpineScore: @entangle('score'),
        alpineProblemString: @entangle('problemString'),
        triggerStarAnimation() {
            const container = this.$refs.starsContainer;
            if (!container) return;

            container.style.display = 'block';
            const numberOfStars = 15; // Increased for more sparkle

            // Clear previous stars if any (though timeout should handle it)
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }

            for (let i = 0; i < numberOfStars; i++) {
                const star = document.createElement('div');
                star.classList.add('star');
                // Randomize position within the container
                star.style.left = Math.random() * 100 + '%';
                star.style.top = Math.random() * 80 + '%'; // Allow some vertical spread too
                // Randomize animation delay for staggered effect
                star.style.animationDelay = Math.random() * 0.7 + 's';
                // Randomize size slightly
                const size = Math.random() * 6 + 4; // Stars between 4px and 10px
                star.style.width = size + 'px';
                star.style.height = size + 'px';
                container.appendChild(star);
            }

            setTimeout(() => {
                container.style.display = 'none';
                while (container.firstChild) {
                    container.removeChild(container.firstChild);
                }
            }, 2000); // Increased to allow longer animation/delays (1s animation + up to 0.7s delay + buffer)
        },
        init() {
            this.$watch('alpineScore', (newValue, oldValue) => {
                if (newValue > oldValue) {
                    const scoreEl = this.$refs.scoreDisplay;
                    if (scoreEl) {
                        scoreEl.classList.add('score-updated');
                        setTimeout(() => {
                            scoreEl.classList.remove('score-updated');
                        }, 500); // Match CSS animation duration
                    }
                    this.triggerStarAnimation(); // Call star animation on correct answer
                }
            });

            this.$watch('alpineProblemString', (newVal, oldVal) => {
                if (newVal !== oldVal && this.$refs.answerInput) {
                    this.$refs.answerInput.focus();
                    this.$refs.answerInput.classList.add('input-new-problem');
                    setTimeout(() => {
                        this.$refs.answerInput.classList.remove('input-new-problem');
                    }, 700); // Match CSS animation duration
                }
            });

            // Initial focus if problem already loaded
            if (this.alpineProblemString && this.$refs.answerInput) {
                 this.$refs.answerInput.focus();
            }
        }
    }"
    class="container mx-auto p-4 text-center bg-gradient-to-r from-blue-300 via-purple-400 to-pink-400 min-h-screen flex flex-col items-center justify-center selection:bg-purple-500 selection:text-white">

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl w-full max-w-lg">
        <h1 class="text-4xl sm:text-5xl font-bold text-purple-600 mb-8 tracking-tight">Math Whiz!</h1>

        <!-- Operation Selection -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8">
            <button wire:click="setOperation('addition')" class="p-3 sm:p-4 text-base sm:text-lg font-semibold rounded-lg shadow-md transition-all duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 {{ $currentOperation === 'addition' ? 'bg-green-500 text-white ring-green-300' : 'bg-green-200 hover:bg-green-300 text-green-800 ring-green-200 hover:ring-green-300' }}">
                <span class="text-2xl sm:text-3xl mr-1">+</span> Add
            </button>
            <button wire:click="setOperation('subtraction')" class="p-3 sm:p-4 text-base sm:text-lg font-semibold rounded-lg shadow-md transition-all duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 {{ $currentOperation === 'subtraction' ? 'bg-yellow-500 text-white ring-yellow-300' : 'bg-yellow-200 hover:bg-yellow-300 text-yellow-800 ring-yellow-200 hover:ring-yellow-300' }}">
                <span class="text-2xl sm:text-3xl mr-1">−</span> Subtract
            </button>
            <button wire:click="setOperation('multiplication')" class="p-3 sm:p-4 text-base sm:text-lg font-semibold rounded-lg shadow-md transition-all duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 {{ $currentOperation === 'multiplication' ? 'bg-red-500 text-white ring-red-300' : 'bg-red-200 hover:bg-red-300 text-red-800 ring-red-200 hover:ring-red-300' }}">
                <span class="text-2xl sm:text-3xl mr-1">×</span> Multiply
            </button>
            <button wire:click="setOperation('division')" class="p-3 sm:p-4 text-base sm:text-lg font-semibold rounded-lg shadow-md transition-all duration-150 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-4 {{ $currentOperation === 'division' ? 'bg-blue-500 text-white ring-blue-300' : 'bg-blue-200 hover:bg-blue-300 text-blue-800 ring-blue-200 hover:ring-blue-300' }}">
                <span class="text-2xl sm:text-3xl mr-1">÷</span> Divide
            </button>
        </div>

        <!-- Score -->
        <div class="text-3xl sm:text-4xl font-bold text-gray-700 mb-6">
            Score: <span x-ref="scoreDisplay" class="text-pink-500 tabular-nums">{{ $score }}</span>
        </div>

        <!-- Problem -->
        <div class="text-4xl sm:text-5xl font-mono text-gray-800 mb-8 p-5 sm:p-6 bg-purple-50 rounded-lg shadow-inner min-h-[80px] sm:min-h-[100px] flex items-center justify-center overflow-hidden">
            <div wire:key="problem-{{ $problemString }}-{{ rand() }}"  {{-- Adding rand() to ensure key changes if problem string is identical for some reason --}}
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-90"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-90"
                 class="w-full">
                {{ $problemString }}
            </div>
        </div>

        <!-- Answer Input -->
        <div class="mb-6">
            <input type="number" wire:model.lazy="userAnswer" wire:keydown.enter="checkAnswer"
                   pattern="[0-9]*" inputmode="numeric"
                   class="w-full p-4 text-2xl sm:text-3xl text-center border-2 border-purple-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400"
                   placeholder="Your Answer"
                   autocomplete="off"
                   x-ref="answerInput" {{-- Removed autofocus and separate x-data for focus --}}
            >
        </div>

        <!-- Submit Button -->
        <button wire:click="checkAnswer"
                class="w-full p-4 sm:p-5 text-xl sm:text-2xl font-bold text-white bg-purple-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-4 focus:ring-purple-300 transition-all duration-150 ease-in-out transform hover:scale-105 mb-6">
            Check Answer
        </button>

        <!-- Message Feedback & Stars -->
        <div class="min-h-[60px] flex items-center justify-center relative"> {{-- Added position:relative for star container --}}
            <div x-ref="starsContainer" class="star-container"></div>
            @if ($message)
                <div wire:key="{{ rand() }}" class="p-3 sm:p-4 text-lg sm:text-xl font-semibold rounded-lg shadow-md w-full animate-pop-in
                    {{ str_contains(strtolower($message), 'correct') ? 'bg-green-100 text-green-700' : (str_contains(strtolower($message), 'please') ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $message }}
                </div>
            @endif
        </div>
    </div>

    {{-- Simple animation for feedback message --}}
    <style>
        @keyframes pop-in {
            0% { opacity: 0; transform: scale(0.9); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-pop-in {
            animation: pop-in 0.3s ease-out;
        }

        @keyframes pulseScore {
            0% { transform: scale(1); color: #ec4899; } /* Initial color (pink-500) */
            50% { transform: scale(1.25); color: #22c55e; } /* Highlight color (green-500) */
            100% { transform: scale(1); color: #ec4899; } /* Back to initial */
        }
        .score-updated {
            animation: pulseScore 0.5s ease-out;
        }

        @keyframes pulseInput {
            0% { border-color: #a855f7; } /* Initial border (purple-300) */
            50% { border-color: #d946ef; box-shadow: 0 0 12px #d946ef; } /* Highlight border (fuchsia-500) */
            100% { border-color: #a855f7; }
        }
        .input-new-problem {
            animation: pulseInput 0.7s ease-in-out;
        }

        /* Star Animation Styles */
        .star-container {
            position: absolute; /* Positioned within the relative feedback container */
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 150px; /* Increased size for more spread */
            height: 80px; /* Increased size */
            pointer-events: none; /* So it doesn't interfere with clicks on message */
            display: none; /* Hidden by default */
            z-index: 10; /* Above the message potentially */
        }

        .star {
            position: absolute;
            /* width: 8px; height: 8px; /* Base size, will be randomized by JS */
            background-color: gold;
            border-radius: 50%;
            opacity: 0;
            animation: sparkle 1s ease-out forwards;
            /* box-shadow: 0 0 5px gold, 0 0 10px gold; /* Optional extra glow */
        }
        /* Add a more star-like shape using pseudo-elements */
        .star::before, .star::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background-color: gold;
            border-radius: 50%;
            /* box-shadow: 0 0 5px gold, 0 0 10px gold; */
        }
        .star::before {
            transform: rotate(45deg);
        }
        /* .star::after { transform: rotate(-45deg); } /* Could add more points, but simple circles are fine too */


        @keyframes sparkle {
            0% { transform: translateY(10px) scale(0.3); opacity: 0.5; }
            25% { transform: scale(1.1); opacity: 1; } /* Brighter, bigger quickly */
            100% { transform: translateY(-30px) scale(0.5); opacity: 0; } /* Float up further and fade */
        }
    </style>
</div>
