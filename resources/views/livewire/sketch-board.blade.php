<div>
    <!-- Adding viewport meta tag -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <div class="min-h-screen bg-white" wire:ignore x-data="sketchBoard($wire)">
        <!-- Mobile-friendly Toolbar -->
        <div class="fixed top-0 left-0 right-0 bg-white shadow-md z-10">
            <!-- Color and Size Controls -->
            <div class="p-2 sm:p-3 border-b">
                <div class="max-w-7xl mx-auto flex flex-wrap items-center gap-2 sm:gap-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-xs sm:text-sm font-medium text-gray-700">Color:</label>
                        <input type="color" wire:model.live="currentColor"
                            class="w-8 h-8 sm:w-10 sm:h-10 rounded-full cursor-pointer border-2 border-gray-200 p-0">
                    </div>

                    <div class="flex-1 flex items-center space-x-2 min-w-[150px] sm:min-w-[200px]">
                        <label class="text-xs sm:text-sm font-medium text-gray-700">Size:</label>
                        <input type="range" wire:model.live="brushSize" min="1" max="20"
                            class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <span class="text-xs sm:text-sm text-gray-600 w-8 sm:w-12">{{ $brushSize }}px</span>
                    </div>
                </div>
            </div>

            <!-- Tools -->
            <div class="p-2 sm:p-3">
                <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2 sm:gap-4">
                    <div class="flex items-center">
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button wire:click="$set('isEraser', false)"
                                class="p-2 sm:p-3 rounded-lg {{ !$isEraser ? 'bg-white shadow-sm text-blue-600' : 'text-gray-700' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <button wire:click="$set('isEraser', true)"
                                class="p-2 sm:p-3 rounded-lg {{ $isEraser ? 'bg-white shadow-sm text-blue-600' : 'text-gray-700' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button @click="clearCanvas"
                            class="p-2 sm:p-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all flex items-center space-x-1 sm:space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            <span class="text-xs sm:text-sm">Clear</span>
                        </button>

                        <button @click="saveDrawing"
                            class="p-2 sm:p-3 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-all flex items-center space-x-1 sm:space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <span class="text-xs sm:text-sm">Save</span>
                        </button>

                        <button @click="toggleFullScreen"
                            class="p-2 sm:p-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all flex items-center space-x-1 sm:space-x-2">
                            <svg x-show="!isFullScreen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5v-4m0 4h-4m4 0l-5-5" />
                            </svg>
                            <svg x-show="isFullScreen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 4v3m0 0h3M9 7H6m13 0h-3m0 0v-3m0 0h3M6 17v-3m0 0h3m-3 0v3m0 0h3m8-3v3m0 0h3m-3 0v-3m0 0h3" />
                            </svg>
                            <span class="text-xs sm:text-sm" x-text="isFullScreen ? 'Exit Full' : 'Full Screen'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        @if (session('message'))
            <div
                class="fixed top-28 sm:top-32 right-4 p-3 sm:p-4 bg-green-100 text-green-700 rounded-lg shadow-lg z-50 animate-fade-in-down text-sm sm:text-base">
                {{ session('message') }}
            </div>
        @endif

        <!-- Full Page Canvas Container -->
        <div id="canvasContainer" class="fixed inset-0 bg-white" :class="{ 'z-40': isFullScreen }">
            <div class="absolute inset-0 mt-[110px] sm:mt-[132px]">
                <canvas x-ref="canvas" @mousedown="startDrawing" @mousemove="draw" @mouseup="stopDrawing"
                    @mouseleave="stopDrawing" @touchstart.prevent="startDrawing($event)"
                    @touchmove.prevent="draw($event)" @touchend.prevent="stopDrawing"
                    @touchcancel.prevent="stopDrawing" class="w-full h-full touch-none select-none">
                </canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sketchBoard', ($wire) => ({
                isDrawing: false,
                isFullScreen: false,
                canvas: null,
                ctx: null,
                wire: $wire,
                points: [],
                maxPoints: 5,
                lastTime: 0,
                throttleDelay: 8, // Reduced for smoother lines
                pressure: 1,
                tilt: {
                    x: 0,
                    y: 0
                },
                drawHistory: [],
                maxHistorySteps: 50,

                init() {
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d', {
                        willReadFrequently: true
                    });
                    this.setupContext();
                    this.setupEventListeners();
                    this.setCanvasSize();
                    this.clearCanvas();
                },

                setupContext() {
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                    this.ctx.imageSmoothingEnabled = true;
                    this.ctx.imageSmoothingQuality = 'high';
                },

                setupEventListeners() {
                    const preventDefaults = (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                    };

                    const options = {
                        passive: false
                    };

                    ['touchstart', 'touchmove', 'touchend', 'touchcancel'].forEach(eventName => {
                        this.canvas.addEventListener(eventName, preventDefaults, options);
                    });

                    // Handle pressure sensitivity
                    this.canvas.addEventListener('pointerdown', (e) => {
                        this.pressure = e.pressure !== 0 ? e.pressure : 0.5;
                        this.tilt = {
                            x: e.tiltX,
                            y: e.tiltY
                        };
                    });

                    this.canvas.addEventListener('pointermove', (e) => {
                        this.pressure = e.pressure !== 0 ? e.pressure : 0.5;
                        this.tilt = {
                            x: e.tiltX,
                            y: e.tiltY
                        };
                    });

                    // Undo/Redo support
                    window.addEventListener('keydown', (e) => {
                        if (e.key === 'z' && (e.ctrlKey || e.metaKey)) {
                            this.undo();
                        }
                    });

                    // Auto-save current state periodically
                    setInterval(() => this.saveState(), 5000);

                    // Prevent double-tap zoom
                    let lastTap = 0;
                    this.canvas.addEventListener('touchend', (e) => {
                        const currentTime = new Date().getTime();
                        const tapLength = currentTime - lastTap;
                        if (tapLength < 500 && tapLength > 0) {
                            preventDefaults(e);
                        }
                        lastTap = currentTime;
                    });

                    this.setCanvasSize();

                    // Handle orientation changes and window resizes
                    window.addEventListener('resize', () => {
                        this.setCanvasSize(true);
                    });

                    window.addEventListener('orientationchange', () => {
                        setTimeout(() => this.setCanvasSize(true), 100);
                    });

                    this.clearCanvas();

                    // Handle fullscreen changes
                    document.addEventListener('fullscreenchange', () => {
                        if (!document.fullscreenElement) {
                            this.isFullScreen = false;
                            setTimeout(() => this.setCanvasSize(true), 100);
                        }
                    });
                },

                saveState() {
                    const imageData = this.canvas.toDataURL();
                    this.drawHistory.push(imageData);
                    if (this.drawHistory.length > this.maxHistorySteps) {
                        this.drawHistory.shift();
                    }
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
                    const container = document.getElementById('canvasContainer');

                    if (!document.fullscreenElement) {
                        try {
                            await container.requestFullscreen();
                            this.isFullScreen = true;
                            setTimeout(() => this.setCanvasSize(true), 100);
                        } catch (err) {
                            console.error('Error attempting to enable full-screen mode:', err
                                .message);
                        }
                    } else {
                        document.exitFullscreen();
                        this.isFullScreen = false;
                        setTimeout(() => this.setCanvasSize(true), 100);
                    }
                },

                setCanvasSize(maintainContent = false) {
                    const container = this.canvas.parentElement;
                    const toolbarHeight = window.innerWidth < 640 ? 110 : 132;
                    let containerWidth = container.clientWidth;
                    let containerHeight = window.innerHeight - toolbarHeight;

                    let tempCanvas;
                    if (maintainContent) {
                        tempCanvas = document.createElement('canvas');
                        tempCanvas.width = this.canvas.width;
                        tempCanvas.height = this.canvas.height;
                        const tempCtx = tempCanvas.getContext('2d');
                        tempCtx.drawImage(this.canvas, 0, 0);
                    }

                    // Set canvas size to match container
                    this.canvas.width = containerWidth;
                    this.canvas.height = containerHeight;

                    // Restore content if needed
                    if (maintainContent && tempCanvas) {
                        this.ctx.drawImage(tempCanvas, 0, 0, containerWidth, containerHeight);
                    } else {
                        this.clearCanvas();
                    }

                    // Reset context properties after resize
                    this.setupContext();
                },

                clearCanvas() {
                    if (this.ctx) {
                        this.ctx.fillStyle = 'white';
                        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                    }
                },

                startDrawing(e) {
                    this.isDrawing = true;
                    this.points = [];
                    const pos = this.getPos(e);
                    this.addPoint(pos.x, pos.y);
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                },

                addPoint(x, y) {
                    this.points.push({
                        x,
                        y,
                        pressure: this.pressure
                    });
                    if (this.points.length > this.maxPoints) {
                        this.points.shift();
                    }
                },

                draw(e) {
                    if (!this.isDrawing) return;

                    const currentTime = Date.now();
                    if (currentTime - this.lastTime < this.throttleDelay) return;
                    this.lastTime = currentTime;

                    const pos = this.getPos(e);
                    this.addPoint(pos.x, pos.y);

                    if (this.points.length < 2) return;

                    this.ctx.strokeStyle = this.wire.isEraser ? 'white' : this.wire.currentColor;

                    // Adjust line width based on pressure and brush size
                    const pressureWidth = this.wire.brushSize * (this.pressure * 1.5);
                    this.ctx.lineWidth = Math.max(1, pressureWidth);

                    this.ctx.beginPath();

                    if (this.points.length === 2) {
                        // Draw straight line for two points
                        this.ctx.moveTo(this.points[0].x, this.points[0].y);
                        this.ctx.lineTo(this.points[1].x, this.points[1].y);
                    } else {
                        // Draw smooth curve through points
                        this.ctx.moveTo(this.points[0].x, this.points[0].y);

                        for (let i = 1; i < this.points.length - 2; i++) {
                            const xc = (this.points[i].x + this.points[i + 1].x) / 2;
                            const yc = (this.points[i].y + this.points[i + 1].y) / 2;
                            const pressure = (this.points[i].pressure + this.points[i + 1].pressure) /
                                2;

                            this.ctx.quadraticCurveTo(
                                this.points[i].x,
                                this.points[i].y,
                                xc,
                                yc
                            );
                        }

                        // Curve through the last two points
                        const lastPoint = this.points[this.points.length - 1];
                        this.ctx.quadraticCurveTo(
                            this.points[this.points.length - 2].x,
                            this.points[this.points.length - 2].y,
                            lastPoint.x,
                            lastPoint.y
                        );
                    }

                    this.ctx.stroke();
                },

                stopDrawing() {
                    if (this.isDrawing) {
                        this.isDrawing = false;
                        this.ctx.beginPath();
                        this.saveState();
                    }
                    this.points = [];
                },

                getPos(e) {
                    const rect = this.canvas.getBoundingClientRect();
                    let clientX, clientY;

                    // Handle both mouse and touch events
                    if (e.touches && e.touches[0]) {
                        // Touch event
                        clientX = e.touches[0].clientX;
                        clientY = e.touches[0].clientY;
                    } else {
                        // Mouse event
                        clientX = e.clientX;
                        clientY = e.clientY;
                    }

                    // Get the pixel ratio for high DPI screens
                    const ratio = window.devicePixelRatio || 1;

                    const x = (clientX - rect.left) * (this.canvas.width / rect.width);
                    const y = (clientY - rect.top) * (this.canvas.height / rect.height);

                    return {
                        x,
                        y
                    };
                },

                saveDrawing() {
                    this.wire.imageData = this.canvas.toDataURL();
                    this.wire.saveDrawing();
                }
            }))
        })
    </script>
</div>
