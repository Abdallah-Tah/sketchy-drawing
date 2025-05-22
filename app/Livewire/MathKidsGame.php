<?php

namespace App\Livewire;

use Livewire\Component;

class MathKidsGame extends Component
{
    public $currentOperation = 'addition'; // Default operation
    public $difficulty = 'kindergarten'; // Default difficulty
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

    public function setDifficulty($level)
    {
        $this->difficulty = $level;
        // Reset score when difficulty changes
        $this->score = 0; 
        $this->message = "Level set to " . str_replace('_', ' ', ucwords($this->difficulty, '_')) . "!";
        $this->startGame($this->currentOperation);
    }

    public function setOperation($operation)
    {
        // Prevent unsupported operations for certain difficulties
        if ($this->difficulty === 'kindergarten' && ($operation === 'multiplication' || $operation === 'division')) {
            $this->message = "Multiplication/Division is not available for Kindergarten. Switching to Addition.";
            $this->currentOperation = 'addition';
        } else {
            $this->currentOperation = $operation;
        }
        $this->startGame($this->currentOperation);
    }

    public function startGame($operation = null)
    {
        if ($operation) {
            $this->currentOperation = $operation;
        }

        // Adjust operations for Kindergarten if current is not supported
        if ($this->difficulty === 'kindergarten' && ($this->currentOperation === 'multiplication' || $this->currentOperation === 'division')) {
            $this->currentOperation = 'addition'; // Default to addition for K if current op is multi/div
        }

        // Define number ranges based on difficulty
        $range1_max = 10;
        $range2_max = 10;
        $allow_multiplication = false;
        $allow_division = false;

        switch ($this->difficulty) {
            case 'kindergarten':
                $range1_max = 10;
                $range2_max = 10;
                break;
            case '1st_grade':
                $range1_max = 20;
                $range2_max = 20;
                // Optionally allow very simple multiplication later (e.g., x2, x5, x10)
                break;
            case '2nd_grade':
                $range1_max = 100; // For addition/subtraction
                $range2_max = 100; // For addition/subtraction
                $allow_multiplication = true;
                $allow_division = true; // Enable division for 2nd grade
                break;
        }

        $this->number1 = rand(1, $range1_max);
        $this->number2 = rand(1, $range2_max);

        switch ($this->currentOperation) {
            case 'addition':
                $this->correctAnswer = $this->number1 + $this->number2;
                $this->problemString = "{$this->number1} + {$this->number2} =";
                break;
            case 'subtraction':
                if ($this->difficulty === 'kindergarten') {
                    if ($this->number1 < $this->number2) {
                        [$this->number1, $this->number2] = [$this->number2, $this->number1]; // Ensure num1 >= num2
                    }
                }
                // For 1st/2nd grade, allow num2 > num1 if it makes sense for curriculum (e.g. 13-7)
                // Current implementation for 1st/2nd grade will also ensure num1 >= num2 by default if not swapped
                // To ensure problems like 13-7, we might need to generate num1 as sum and num2 as one part.
                // For now, let's keep it simple for all levels: ensure num1 >= num2 for positive results.
                if ($this->number1 < $this->number2) {
                     [$this->number1, $this->number2] = [$this->number2, $this->number1];
                }
                $this->correctAnswer = $this->number1 - $this->number2;
                $this->problemString = "{$this->number1} - {$this->number2} =";
                break;
            case 'multiplication':
                if (!$allow_multiplication && $this->difficulty !== '2nd_grade') { // Default to addition if not allowed
                    $this->currentOperation = 'addition';
                    $this->startGame($this->currentOperation); // Restart with addition
                    return;
                }
                // 2nd Grade: 1-5 times tables
                $this->number1 = rand(1, 12); // Factor 1 (e.g., up to 12)
                $this->number2 = rand(1, 5);  // Factor 2 (1-5 times table)
                $this->correctAnswer = $this->number1 * $this->number2;
                $this->problemString = "{$this->number1} × {$this->number2} =";
                break;
            case 'division':
                if (!$allow_division && $this->difficulty !== '2nd_grade') { // Default to addition if not allowed
                     $this->currentOperation = 'addition';
                     $this->startGame($this->currentOperation); // Restart with addition
                     return;
                }
                // 2nd Grade: Ensure whole number results, based on 1-5 times tables
                $this->number2 = rand(1, 5); // Divisor (from 1-5 times table)
                $quotient = rand(1, 10);    // Result of division
                $this->number1 = $this->number2 * $quotient; // Calculate dividend
                $this->correctAnswer = $quotient;
                $this->problemString = "{$this->number1} ÷ {$this->number2} =";
                break;
            default:
                $this->currentOperation = 'addition'; // Default to addition if unknown operation
                $this->startGame($this->currentOperation);
                return;
        }

        $this->userAnswer = ''; // Clear previous answer
        // $this->message = ''; // Don't clear message if it's a difficulty set confirmation
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
            $this->startGame($this->currentOperation); // Load next problem
        } else {
            $this->message = 'Oops! That\'s not it. Try again!';
        }
    }

    public function render()
    {
        return view('livewire.math-kids-game');
    }
}
