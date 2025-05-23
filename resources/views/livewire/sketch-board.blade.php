<div>
    <!-- Adding viewport meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <div class="min-h-screen bg-white" wire:ignore x-data="sketchBoard($wire)" @mousemove.window="draw($event)"
        @mouseup.window="stopDrawing" @touchmove.window.prevent="draw($event)" @touchend.window.prevent="stopDrawing">
        <!-- Full Page Canvas Container -->
        <div id="canvasContainer" class="fixed inset-0 bg-white h-screen" :class="{ 'z-10': isFullScreen }">
            <canvas x-ref="canvas" @mousedown="startDrawing($event)" class="w-full h-full touch-none select-none">
            </canvas>
        </div>

        <!-- Floating toolbar -->
        <div class="fixed top-24 left-1/2 transform -translate-x-1/2 z-30">
            <div class="bg-white rounded-full shadow-lg px-4 py-2 flex items-center space-x-4">
                <!-- Color picker -->
                <div class="relative" x-data="{ showColorPicker: false }">
                    <button @click="showColorPicker = !showColorPicker"
                        class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-200"
                            :style="{ backgroundColor: $wire.currentColor }"></div>
                    </button>
                    <div x-show="showColorPicker" @click.away="showColorPicker = false"
                        class="absolute top-full left-0 mt-2 bg-white rounded-lg shadow-lg p-3">
                        <div class="flex flex-col items-center">
                            <input type="color" wire:model.live="currentColor"
                                class="w-12 h-12 rounded-full cursor-pointer border-2 border-gray-200 p-0 mb-2">
                            <span class="text-xs text-gray-600">Pick Color</span>
                        </div>
                    </div>
                </div>

                <!-- Brush size -->
                <div class="relative" x-data="{ showSizePicker: false }">
                    <button @click="showSizePicker = !showSizePicker"
                        class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <div class="w-6 h-6 flex items-center justify-center">
                            <div class="rounded-full bg-gray-800"
                                :style="{ width: $wire.brushSize + 'px', height: $wire.brushSize + 'px' }"></div>
                        </div>
                    </button>
                    <div x-show="showSizePicker" @click.away="showSizePicker = false"
                        class="absolute top-full left-0 mt-2 bg-white rounded-lg shadow-lg p-4 min-w-[200px]">
                        <div class="flex flex-col">
                            <input type="range" wire:model.live="brushSize" min="1" max="40"
                                class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mb-2">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">1px</span>
                                <span class="text-sm font-medium text-gray-700">{{ $brushSize }}px</span>
                                <span class="text-xs text-gray-500">40px</span>
                            </div>
                            <!-- Size presets -->
                            <div class="flex justify-between mt-3 border-t pt-3">
                                <button wire:click="$set('brushSize', 2)"
                                    class="w-4 h-4 rounded-full bg-gray-800 hover:ring-2 ring-blue-300 transition-all"></button>
                                <button wire:click="$set('brushSize', 5)"
                                    class="w-5 h-5 rounded-full bg-gray-800 hover:ring-2 ring-blue-300 transition-all"></button>
                                <button wire:click="$set('brushSize', 10)"
                                    class="w-6 h-6 rounded-full bg-gray-800 hover:ring-2 ring-blue-300 transition-all"></button>
                                <button wire:click="$set('brushSize', 20)"
                                    class="w-7 h-7 rounded-full bg-gray-800 hover:ring-2 ring-blue-300 transition-all"></button>
                                <button wire:click="$set('brushSize', 40)"
                                    class="w-8 h-8 rounded-full bg-gray-800 hover:ring-2 ring-blue-300 transition-all"></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tools -->
                <div class="h-6 w-px bg-gray-200"></div>

                <button @click="$wire.isEraser = false" class="p-2 rounded-full transition-colors"
                    :class="!$wire.isEraser ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                </button>

                <button @click="$wire.isEraser = true" class="p-2 rounded-full transition-colors"
                    :class="$wire.isEraser ? 'bg-blue-100 text-blue-600' : 'hover:bg-gray-100'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>

                <!-- More options dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="p-2 rounded-full hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        class="absolute top-full right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-30">
                        <button @click="clearCanvas(); open = false"
                            class="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span>Clear Canvas</span>
                        </button>
                        <button @click="saveDrawing; open = false"
                            class="w-full px-4 py-2 text-left text-sm text-green-600 hover:bg-green-50 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span>Save Drawing</span>
                        </button>
                        <button @click="toggleFullScreen; open = false"
                            class="w-full px-4 py-2 text-left text-sm text-blue-600 hover:bg-blue-50 flex items-center space-x-2">
                            <svg x-show="!isFullScreen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                            </svg>
                            <svg x-show="isFullScreen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 4v3m0 0h3M9 7H6m13 0h-3m0 0v-3m0 0h3M6 17v-3m0 0h3m-3 0v3m0 0h3m8-3v3m0 0h3m-3 0v-3m0 0h3" />
                            </svg>
                            <span x-text="isFullScreen ? 'Exit Fullscreen' : 'Enter Fullscreen'"></span>
                        </button>
                        <button @click="undo; open = false"
                            class="w-full px-4 py-2 text-left text-sm text-gray-600 hover:bg-gray-50 flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                            <span>Undo (Ctrl+Z)</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (session('message'))
            <div
                class="fixed top-20 right-4 p-3 bg-green-100 text-green-700 rounded-lg shadow-lg z-50 animate-fade-in-down text-sm">
                {{ session('message') }}
            </div>
        @endif

        <!-- Full Page Canvas Container -->
        <div id="canvasContainer" class="fixed inset-0 bg-white h-screen" :class="{ 'z-10': isFullScreen }">
            <canvas x-ref="canvas" @mousedown="startDrawing($event)" class="w-full h-full touch-none select-none">
            </canvas>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sketchBoard', ($wire) => ({
                // Initialize state
                canvas: null,
                ctx: null,
                isDrawing: false,
                isFullScreen: false,
                lastX: 0,
                lastY: 0,
                drawHistory: [],
                maxHistorySteps: 50,
                // State variables
                isDrawing: false,
                isFullScreen: false,
                canvas: null,
                ctx: null,
                lastX: 0,
                lastY: 0,
                drawHistory: [],
                maxHistorySteps: 50,

                init() {
                    // Initialize canvas
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d');

                    // Setup initial canvas state
                    this.setupCanvas();

                    // Setup event listeners
                    window.addEventListener('resize', () => this.setupCanvas());
                    window.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                            this.undo();
                        }
                    });
                },

                // Canvas setup and configuration
                setupCanvas() {
                    const dpr = window.devicePixelRatio || 1;
                    const rect = this.canvas.getBoundingClientRect();

                    // Set canvas size
                    this.canvas.width = rect.width * dpr;
                    this.canvas.height = rect.height * dpr;

                    // Configure context
                    this.ctx.scale(dpr, dpr);
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    this.ctx.strokeStyle = $wire.currentColor;
                    this.ctx.lineWidth = $wire.brushSize;

                    // Clear canvas
                    this.clearCanvas();
                },

                resizeCanvas() {
                    const dpr = window.devicePixelRatio || 1;
                    const rect = this.canvas.getBoundingClientRect();

                    this.canvas.width = rect.width * dpr;
                    this.canvas.height = rect.height * dpr;

                    this.ctx.scale(dpr, dpr);
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    this.ctx.strokeStyle = $wire.currentColor;
                    this.ctx.lineWidth = $wire.brushSize;
                },

                addEventListeners() {
                    window.addEventListener('resize', () => this.setupCanvas());
                    document.addEventListener('keydown', (e) => {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'z') {
                            this.undo();
                        }
                    });
                },

                startDrawing(e) {
                    // Only start drawing if we click on the canvas
                    if (!e.target.classList.contains('touch-none')) return;

                    this.isDrawing = true;
                    const pos = this.getPointerPosition(e);
                    if (pos) {
                        this.lastX = pos.x;
                        this.lastY = pos.y;
                        // Save the current state before starting new drawing
                        this.saveToHistory();
                    }
                },

                draw(e) {
                    if (!this.isDrawing) return;

                    // Get current cursor/touch position
                    const pos = this.getPointerPosition(e);
                    if (!pos) return;

                    // Continue drawing regardless of what element we're over
                    this.ctx.beginPath();
                    this.ctx.moveTo(this.lastX, this.lastY);
                    this.ctx.lineTo(pos.x, pos.y);

                    // Set drawing styles
                    this.ctx.lineWidth = $wire.brushSize;
                    this.ctx.strokeStyle = $wire.currentColor;

                    if ($wire.isEraser) {
                        this.ctx.globalCompositeOperation = 'destination-out';
                    } else {
                        this.ctx.globalCompositeOperation = 'source-over';
                    }

                    this.ctx.stroke();
                    this.lastX = pos.x;
                    this.lastY = pos.y;
                },

                stopDrawing() {
                    this.isDrawing = false;
                },

                getPointerPosition(e) {
                    try {
                        const rect = this.canvas.getBoundingClientRect();
                        let x, y;

                        if (e.touches && e.touches[0]) {
                            // Touch event
                            x = e.touches[0].clientX - rect.left;
                            y = e.touches[0].clientY - rect.top;
                        } else {
                            // Mouse event
                            x = e.clientX - rect.left;
                            y = e.clientY - rect.top;
                        }

                        // Account for device pixel ratio
                        const dpr = window.devicePixelRatio || 1;
                        return {
                            x: x * dpr,
                            y: y * dpr
                        };
                    } catch (error) {
                        console.error('Error getting pointer position:', error);
                        return null;
                    }
                },

                clearCanvas() {
                    if (this.ctx) {
                        this.ctx.fillStyle = '#FFFFFF';
                        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                        this.saveToHistory();
                    }
                },

                saveToHistory() {
                    if (this.drawHistory.length >= this.maxHistorySteps) {
                        this.drawHistory.shift();
                    }
                    this.drawHistory.push(this.canvas.toDataURL());
                },

                undo() {
                    if (this.drawHistory.length > 0) {
                        const previousState = this.drawHistory.pop();
                        const img = new Image();
                        img.onload = () => {
                            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                            this.ctx.drawImage(img, 0, 0);
                        };
                        img.src = previousState;
                    }
                },

                async toggleFullScreen() {
                    try {
                        const container = document.getElementById('canvasContainer');
                        if (!document.fullscreenElement) {
                            await container.requestFullscreen();
                            this.isFullScreen = true;
                        } else {
                            await document.exitFullscreen();
                            this.isFullScreen = false;
                        }
                        // Wait for the resize to complete
                        setTimeout(() => this.setupCanvas(), 100);
                    } catch (err) {
                        console.error('Error toggling fullscreen:', err);
                    }
                },

                saveDrawing() {
                    const imageData = this.canvas.toDataURL();
                    $wire.set('imageData', imageData);
                    $wire.saveDrawing();
                }
            }))
        })
    </script>
</div>
</div>
