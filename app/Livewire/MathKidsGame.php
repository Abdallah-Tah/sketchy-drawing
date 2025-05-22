<?php

namespace App\Livewire;

use Livewire\Component;

class MathKidsGame extends Component
{
    public $currentOperation = 'addition'; // Default operation
    public $number1 = 0;
    public $number2 = 0;
    public $userAnswer = '';
    public $message = '';
    public $score = 0;
    public $correctAnswer = 0;
    public $problemString = '';

    public function mount()
    {
        $this->startGame($this->currentOperation);
    }

    public function startGame($operation = null)
    {
        if ($operation) {
            $this->currentOperation = $operation;
        }

        // Keep it simple for now: numbers between 1 and 10
        $this->number1 = rand(1, 10);
        $this->number2 = rand(1, 10);

        switch ($this->currentOperation) {
            case 'addition':
                $this->correctAnswer = $this->number1 + $this->number2;
                $this->problemString = "{$this->number1} + {$this->number2} =";
                break;
            case 'subtraction':
                // Ensure positive result for simplicity, or adjust difficulty
                if ($this->number1 < $this->number2) {
                    // Swap numbers to ensure number1 is greater or equal
                    [$this->number1, $this->number2] = [$this->number2, $this->number1];
                }
                $this->correctAnswer = $this->number1 - $this->number2;
                $this->problemString = "{$this->number1} - {$this->number2} =";
                break;
            case 'multiplication':
                $this->number1 = rand(1, 10); // Can adjust range for multiplication
                $this->number2 = rand(1, 5);  // e.g. smaller numbers for second factor
                $this->correctAnswer = $this->number1 * $this->number2;
                $this->problemString = "{$this->number1} × {$this->number2} =";
                break;
            case 'division':
                // Ensure division results in whole number and no division by zero
                $this->number2 = rand(1, 5); // Divisor
                $quotient = rand(1, 5); // Result
                $this->number1 = $this->number2 * $quotient; // Calculate dividend
                $this->correctAnswer = $quotient;
                $this->problemString = "{$this->number1} ÷ {$this->number2} =";
                break;
            default:
                $this->startGame('addition'); // Default to addition if unknown
                return;
        }

        $this->userAnswer = ''; // Clear previous answer
        $this->message = '';    // Clear previous message
    }

    public function checkAnswer()
    {
        if ($this->userAnswer === '') {
            $this->message = 'Please enter an answer.';
            return;
        }

        $userAnswerInt = (int) $this->userAnswer;

        if ($userAnswerInt == $this->correctAnswer) {
            $this->score++;
            $this->message = 'Correct! Great job!';
            // Optionally add a small delay here before the next problem
            $this->startGame($this->currentOperation); // Load next problem
        } else {
            $this->message = 'Oops! That\'s not it. Try again!';
            // $this->userAnswer = ''; // Optionally clear wrong answer
        }
    }

    public function setOperation($operation)
    {
        $this->startGame($operation);
    }

    public function render()
    {
        return view('livewire.math-kids-game');
    }
}
