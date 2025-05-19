<div>
    <!-- Adding viewport meta tag -->
    <div class="min-h-screen bg-gray-100" wire:ignore x-data="sketchBoard($wire)">
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

        <!-- Canvas Container - Adjust top padding to account for smaller toolbar on mobile -->
        <div class="pt-[110px] sm:pt-[132px] px-1 sm:px-2 pb-2 flex justify-center items-center min-h-screen">
            <div class="w-full max-w-5xl bg-white rounded-xl shadow-lg p-1 sm:p-2 md:p-4">
                <canvas x-ref="canvas" @mousedown="startDrawing" @mousemove="draw" @mouseup="stopDrawing"
                    @mouseleave="stopDrawing" @touchstart.prevent="startDrawing($event)"
                    @touchmove.prevent="draw($event)" @touchend.prevent="stopDrawing" @touchcancel.prevent="stopDrawing"
                    class="w-full aspect-[4/3] bg-white rounded-lg border-2 border-gray-200 touch-none">
                </canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('sketchBoard', ($wire) => ({
                isDrawing: false,
                canvas: null,
                ctx: null,
                wire: $wire,
                lastX: 0,
                lastY: 0,

                init() {
                    this.canvas = this.$refs.canvas;
                    this.ctx = this.canvas.getContext('2d');
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';

                    // Prevent scrolling while drawing
                    this.canvas.addEventListener('touchstart', (e) => {
                        e.preventDefault();
                    }, {
                        passive: false
                    });

                    this.canvas.addEventListener('touchmove', (e) => {
                        e.preventDefault();
                    }, {
                        passive: false
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
                },

                setCanvasSize(maintainContent = false) {
                    const containerWidth = this.canvas.parentElement.clientWidth -
                        (window.innerWidth < 640 ? 8 : 16); // Less padding on mobile
                    const containerHeight = (containerWidth * 3) / 4; // Maintain 4:3 aspect ratio

                    let tempCanvas;
                    if (maintainContent) {
                        tempCanvas = document.createElement('canvas');
                        tempCanvas.width = this.canvas.width;
                        tempCanvas.height = this.canvas.height;
                        const tempCtx = tempCanvas.getContext('2d');
                        tempCtx.drawImage(this.canvas, 0, 0);
                    }

                    this.canvas.width = containerWidth;
                    this.canvas.height = containerHeight;

                    if (maintainContent && tempCanvas) {
                        this.ctx.drawImage(tempCanvas, 0, 0, containerWidth, containerHeight);
                    } else {
                        this.clearCanvas();
                    }

                    // Reset context properties after resize
                    this.ctx.lineCap = 'round';
                    this.ctx.lineJoin = 'round';
                },

                clearCanvas() {
                    if (this.ctx) {
                        this.ctx.fillStyle = 'white';
                        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
                    }
                },

                startDrawing(e) {
                    this.isDrawing = true;
                    const pos = this.getPos(e);
                    this.lastX = pos.x;
                    this.lastY = pos.y;
                    this.ctx.beginPath();
                    this.ctx.moveTo(pos.x, pos.y);
                },

                draw(e) {
                    if (!this.isDrawing) return;

                    const pos = this.getPos(e);

                    // Smoothing for mobile touch events
                    const dx = pos.x - this.lastX;
                    const dy = pos.y - this.lastY;
                    const dist = Math.sqrt(dx * dx + dy * dy);

                    if (dist > 0) {
                        // Create multiple points for smoother lines
                        const steps = Math.max(1, Math.floor(dist / 2));
                        for (let i = 0; i < steps; i++) {
                            const x = this.lastX + (dx * i) / steps;
                            const y = this.lastY + (dy * i) / steps;
                            this.ctx.lineTo(x, y);
                            this.ctx.stroke();
                        }

                        this.ctx.strokeStyle = this.wire.isEraser ? 'white' : this.wire.currentColor;
                        this.ctx.lineWidth = this.wire.brushSize;
                        this.ctx.lineTo(pos.x, pos.y);
                        this.ctx.stroke();

                        this.lastX = pos.x;
                        this.lastY = pos.y;
                    }
                },

                stopDrawing() {
                    this.isDrawing = false;
                    this.ctx.beginPath(); // Reset the path
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
                        clientX = e.clientX || e.pageX;
                        clientY = e.clientY || e.pageY;
                    }

                    const x = clientX - rect.left;
                    const y = clientY - rect.top;

                    return {
                        x: x * (this.canvas.width / rect.width),
                        y: y * (this.canvas.height / rect.height)
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
