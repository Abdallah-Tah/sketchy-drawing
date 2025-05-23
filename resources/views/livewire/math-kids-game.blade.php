<div x-data="{
    alpineScore: @entangle('score'),
    alpineProblemString: @entangle('problemString'),
    triggerConfettiAnimation() {
        const container = this.$refs.confettiContainer;
        if (!container) return;

        container.style.display = 'block';
        const numberOfConfetti = 50;
        const colors = ['red', 'blue', 'green', 'yellow', 'pink', 'purple', 'orange', 'cyan'];

        while (container.firstChild) {
            container.removeChild(container.firstChild);
        }

        for (let i = 0; i < numberOfConfetti; i++) {
            const confettiPiece = document.createElement('div');
            confettiPiece.classList.add('confetti');
            confettiPiece.classList.add(colors[Math.floor(Math.random() * colors.length)]);
            confettiPiece.style.left = Math.random() * 100 + '%';
            confettiPiece.style.top = (Math.random() * 10 - 15) + '%';
            confettiPiece.style.animationDelay = Math.random() * 1.5 + 's';
            container.appendChild(confettiPiece);
        }

        setTimeout(() => {
            container.style.display = 'none';
            while (container.firstChild) {
                container.removeChild(container.firstChild);
            }
        }, 4500);
    },
    init() {
        this.$watch('alpineScore', (newValue, oldValue) => {
            if (newValue > oldValue) {
                const scoreEl = this.$refs.scoreDisplay;
                if (scoreEl) {
                    scoreEl.classList.add('score-updated');
                    setTimeout(() => {
                        scoreEl.classList.remove('score-updated');
                    }, 500);
                }
                this.triggerConfettiAnimation();
            }
        });

        this.$watch('alpineProblemString', (newVal, oldVal) => {
            if (newVal !== oldVal && this.$refs.answerInput) {
                this.$refs.answerInput.focus();
                this.$refs.answerInput.classList.add('input-new-problem');
                setTimeout(() => {
                    this.$refs.answerInput.classList.remove('input-new-problem');
                }, 700);
            }
        });

        if (this.alpineProblemString && this.$refs.answerInput) {
            this.$refs.answerInput.focus();
        }
    }
}"
    class="container mx-auto p-4 text-center bg-gradient-to-r from-blue-300 via-purple-400 to-pink-400 min-h-screen flex flex-col items-center justify-center selection:bg-purple-500 selection:text-white">

    <div class="bg-white p-6 sm:p-8 rounded-xl shadow-2xl w-full max-w-lg relative overflow-hidden">
        <div x-ref="confettiContainer" class="confetti-container"></div>
        <h1 class="text-4xl sm:text-5xl font-bold text-purple-600 mb-6 sm:mb-8 tracking-tight">Math Whiz!</h1>

        <!-- Difficulty Selection -->
        <div class="mb-6 sm:mb-8">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-700 mb-2 sm:mb-3">Select Level:</h3>
            <div class="grid grid-cols-3 gap-2 sm:gap-3">
                <button wire:click="setDifficulty('kindergarten')"
                    class="p-2 sm:p-3 text-sm sm:text-lg rounded-lg shadow-md transition-transform transform hover:scale-105 focus:outline-none focus:ring-4
                               {{ $difficulty === 'kindergarten' ? 'bg-pink-500 text-white ring-pink-300' : 'bg-pink-200 hover:bg-pink-300 text-pink-800 ring-pink-200 hover:ring-pink-300' }}">
                    K
                </button>
                <button wire:click="setDifficulty('1st_grade')"
                    class="p-2 sm:p-3 text-sm sm:text-lg rounded-lg shadow-md transition-transform transform hover:scale-105 focus:outline-none focus:ring-4
                               {{ $difficulty === '1st_grade' ? 'bg-indigo-500 text-white ring-indigo-300' : 'bg-indigo-200 hover:bg-indigo-300 text-indigo-800 ring-indigo-200 hover:ring-indigo-300' }}">
                    1st
                </button>
                <button wire:click="setDifficulty('2nd_grade')"
                    class="p-2 sm:p-3 text-sm sm:text-lg rounded-lg shadow-md transition-transform transform hover:scale-105 focus:outline-none focus:ring-4
                               {{ $difficulty === '2nd_grade' ? 'bg-teal-500 text-white ring-teal-300' : 'bg-teal-200 hover:bg-teal-300 text-teal-800 ring-teal-200 hover:ring-teal-300' }}">
                    2nd
                </button>
            </div>
        </div>

        <!-- Operation Selection -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8">
            @foreach ([
        'addition' => ['+', 'green'],
        'subtraction' => ['−', 'yellow'],
        'multiplication' => ['×', 'red'],
        'division' => ['÷', 'blue'],
    ] as $op => [$symbol, $color])
                <button wire:click="setOperation('{{ $op }}')"
                    class="p-3 sm:p-4 text-base sm:text-lg font-semibold rounded-lg shadow-md transition-all transform hover:scale-105 focus:outline-none focus:ring-4
                        {{ $currentOperation === $op
                            ? "bg-{$color}-500 text-white ring-{$color}-300"
                            : "bg-{$color}-200 hover:bg-{$color}-300 text-{$color}-800 ring-{$color}-200 hover:ring-{$color}-300" }}">
                    <span class="text-2xl sm:text-3xl mr-1">{{ $symbol }}</span> {{ ucfirst($op) }}
                </button>
            @endforeach
        </div>

        <!-- Score -->
        <div class="text-3xl sm:text-4xl font-bold text-gray-700 mb-6">
            Score: <span x-ref="scoreDisplay" class="text-pink-500 tabular-nums">{{ $score }}</span>
        </div>

        <!-- Problem -->
        <div
            class="text-4xl sm:text-5xl font-mono text-gray-800 mb-8 p-5 sm:p-6 bg-purple-50 rounded-lg shadow-inner min-h-[80px] sm:min-h-[100px] flex items-center justify-center overflow-hidden">
            <div wire:key="problem-{{ $problemString }}-{{ rand() }}"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                class="w-full">
                {{ $problemString }}
            </div>
        </div>

        <!-- Answer Input -->
        <div class="mb-6">
            <input type="number" wire:model.lazy="userAnswer" wire:keydown.enter="checkAnswer" pattern="[0-9]*"
                inputmode="numeric"
                class="w-full p-4 text-2xl sm:text-3xl text-center border-2 border-purple-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 placeholder-gray-400"
                placeholder="Your Answer" autocomplete="off" x-ref="answerInput">
        </div>

        <!-- Submit Button -->
        <button wire:click="checkAnswer"
            class="w-full p-4 sm:p-5 text-xl sm:text-2xl font-bold text-white bg-purple-600 rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-4 focus:ring-purple-300 transition-all duration-150 ease-in-out transform hover:scale-105 mb-6">
            Check Answer
        </button>

        <!-- Message Feedback -->
        <div class="min-h-[60px] flex items-center justify-center relative">
            @if ($message)
                <div wire:key="{{ rand() }}"
                    class="p-3 sm:p-4 text-lg sm:text-xl font-semibold rounded-lg shadow-md w-full animate-pop-in
                    {{ str_contains(strtolower($message), 'correct') ? 'bg-green-100 text-green-700' : (str_contains(strtolower($message), 'please') ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ $message }}
                </div>
            @endif
        </div>
    </div>

    {{-- Confetti, score/input animations are left unchanged --}}
</div>
