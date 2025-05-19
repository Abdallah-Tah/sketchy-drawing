<div>
    <h1 class="text-center">Children's Sketch Board</h1>
    
    <div class="flex justify-center mb-4">
        <livewire:color-picker />
    </div>

    <canvas id="sketchCanvas" width="800" height="600" class="border border-gray-400"></canvas>

    <div class="flex justify-center mt-4">
        <button wire:click="clearCanvas" class="bg-red-500 text-white px-4 py-2 rounded">Eraser</button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('sketchCanvas');
            const ctx = canvas.getContext('2d');
            let drawing = false;
            let penColor = @this.penColor;

            canvas.addEventListener('mousedown', function (e) {
                drawing = true;
                ctx.beginPath();
                ctx.moveTo(e.offsetX, e.offsetY);
            });

            canvas.addEventListener('mousemove', function (e) {
                if (drawing) {
                    ctx.strokeStyle = penColor;
                    ctx.lineTo(e.offsetX, e.offsetY);
                    ctx.stroke();
                }
            });

            canvas.addEventListener('mouseup', function () {
                drawing = false;
                ctx.closePath();
                @this.storeDrawing(canvas.toDataURL());
            });

            canvas.addEventListener('mouseout', function () {
                drawing = false;
                ctx.closePath();
            });

            Livewire.on('updateColor', color => {
                penColor = color;
            });
        });
    </script>
</div>