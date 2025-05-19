<div>
    <div class="min-h-screen bg-gray-100" wire:ignore x-data="sketchBoard($wire)">
        <!-- Toolbar -->
        <div class="fixed top-0 left-0 right-0 bg-white shadow-md z-10 px-4 py-3">
            <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Color:</label>
                        <input type="color" wire:model.live="currentColor"
                            class="w-10 h-10 rounded cursor-pointer border-2 border-gray-200">
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Tools:</label>
                        <div class="flex bg-gray-100 rounded-lg p-1">
                            <button wire:click="$set('isEraser', false)"
                                class="px-4 py-2 rounded-lg text-sm {{ !$isEraser ? 'bg-white shadow-sm text-blue-600' : 'text-gray-700' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>

                            <button wire:click="$set('isEraser', true)"
                                class="px-4 py-2 rounded-lg text-sm {{ $isEraser ? 'bg-white shadow-sm text-blue-600' : 'text-gray-700' }} transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <label class="text-sm font-medium text-gray-700">Size:</label>
                        <input type="range" wire:model.live="brushSize" min="1" max="20" class="w-32">
                        <span class="text-sm text-gray-600 w-12">{{ $brushSize }}px</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <button @click="clearCanvas"
                        class="px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Clear</span>
                    </button>

                    <button @click="saveDrawing"
                        class="px-4 py-2 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors flex items-center space-x-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span>Save</span>
                    </button>
                </div>
            </div>
        </div>

        @if (session('message'))
            <div
                class="fixed top-20 right-4 p-4 bg-green-100 text-green-700 rounded-lg shadow-lg z-50 animate-fade-in-down">
                {{ session('message') }}
            </div>
        @endif

        <!-- Canvas Container -->
        <div class="pt-20 px-4 pb-4 flex justify-center items-center min-h-screen">
            <div class="w-full max-w-5xl bg-white rounded-xl shadow-lg p-4">
                <canvas x-ref="canvas" @mousedown="startDrawing" @mousemove="draw" @mouseup="stopDrawing"
                    @mouseleave="stopDrawing" @touchstart.prevent="startDrawing" @touchmove.prevent="draw"
                    @touchend.prevent="stopDrawing"
                    class="w-full aspect-[4/3] bg-white rounded-lg border-2 border-gray-200 cursor-crosshair">
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

                init() {
                    this.canvas = this.$refs.canvas
                    this.ctx = this.canvas.getContext('2d')
                    this.ctx.lineCap = 'round'
                    this.ctx.lineJoin = 'round'
                    this.setCanvasSize()

                    window.addEventListener('resize', () => {
                        this.setCanvasSize(true)
                    })

                    this.clearCanvas()
                },

                setCanvasSize(maintainContent = false) {
                    const containerWidth = this.canvas.parentElement.clientWidth -
                    32; // Account for padding
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
                },

                clearCanvas() {
                    if (this.ctx) {
                        this.ctx.fillStyle = 'white'
                        this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height)
                    }
                },

                startDrawing(e) {
                    this.isDrawing = true
                    this.ctx.beginPath()
                    const pos = this.getPos(e)
                    this.ctx.moveTo(pos.x, pos.y)
                },

                draw(e) {
                    if (!this.isDrawing) return
                    const pos = this.getPos(e)
                    this.ctx.lineTo(pos.x, pos.y)
                    this.ctx.strokeStyle = this.wire.isEraser ? 'white' : this.wire.currentColor
                    this.ctx.lineWidth = this.wire.brushSize
                    this.ctx.stroke()
                },

                stopDrawing() {
                    this.isDrawing = false
                },

                getPos(e) {
                    const rect = this.canvas.getBoundingClientRect()
                    const x = e.type.includes('touch') ? e.touches[0].clientX : e.clientX
                    const y = e.type.includes('touch') ? e.touches[0].clientY : e.clientY
                    return {
                        x: x - rect.left,
                        y: y - rect.top
                    }
                },

                saveDrawing() {
                    this.wire.imageData = this.canvas.toDataURL()
                    this.wire.saveDrawing()
                }
            }))
        })
    </script>
</div>
