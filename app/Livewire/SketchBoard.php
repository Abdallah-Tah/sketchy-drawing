<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class SketchBoard extends Component
{
    public $currentColor = '#000000';
    public $isEraser = false;
    public $brushSize = 5;
    public $imageData;

    public function saveDrawing()
    {
        // Remove the data URL prefix to get just the base64 data
        $base64Data = explode(',', $this->imageData)[1];
        $decodedData = base64_decode($base64Data);

        $filename = 'drawing_' . time() . '.png';
        Storage::disk('public')->put('drawings/' . $filename, $decodedData);

        session()->flash('message', 'Drawing saved successfully!');
    }

    public function render()
    {
        return view('livewire.sketch-board')->layout('components.layouts.app');
    }
}
