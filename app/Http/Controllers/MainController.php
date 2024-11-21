<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function generateExercises(Request $request): View
    {
        // form validation
        $request->validate([
            'check_sum' => 'required_without_all:check_subtraction,check_multiplication,check_division',
            'check_subtraction' => 'required_without_all:check_sum,check_multiplication,check_division',
            'check_multiplication' => 'required_without_all:check_subtraction, check_sum,check_division',
            'check_division' => 'required_without_all:check_subtraction, check_multiplication,check_sum',
            'number_one' => 'required|integer|min:0|max:999|lt:number_two',
            'number_two' => 'required|integer|min:0|max:999',
            'number_exercises' => 'required|integer|min:5|max:50',
        ]);

        //get selected operations
        $operations = [];

        if ($request->check_sum) {
            $operations[] = 'sum';
        }
        if ($request->check_subtraction) {
            $operations[] = 'subtraction';
        }
        if ($request->check_multiplication) {
            $operations[] = 'multiplication';
        }
        if ($request->check_division) {
            $operations[] = 'division';
        }

        // get numbers (min and max)
        $min = $request->number_one;
        $max = $request->number_two;

        // get numbers of exercises
        $numberExercises = $request->number_exercises;

        //generate exercises
        $exercises = [];
        for ($index = 1; $index <= $numberExercises; $index++) {
            $exercises[] = $this->generateExercise($index, $operations, $min, $max);
        };

        //place exercises in session
        /* $request->session()->put('exercises', $exercises);
        //ou  */
        session(['exercises' => $exercises]);

        return view('operations', ['exercises' => $exercises]);
    }

    public function printExercises()
    {
        //check if exercises are in session
        if(!session()->has('exercises')){
            return redirect()->route('home');
        }

        $exercises = session('exercises');

        echo '<pre>';
        echo '<h1>Exercícios de Matemática (' . env('APP_NAME') . ')</h1>';
        echo '<hr>';

        foreach($exercises as $exercise){
            echo '<h2><small>' . str_pad($exercise['exercise_number'], 2, "0", STR_PAD_LEFT) . ' >> </small> ' . $exercise['exercise'] . '</h2>';         
        }

        // solutions
        echo '<hr>';
        echo '<small>Solutions</small><br>';
        foreach($exercises as $exercise){
            echo '<small>' . str_pad($exercise['exercise_number'], 2, "0", STR_PAD_LEFT) . ' >> ' . $exercise['solution'] . '</small><br>';         
        }
    }

    public function exportExercises()
    {
        echo 'exportar exercícos para um arquivo de texto';
    }

    private function generateExercise($index, $operations, $min, $max): array
    {
        $operation = $operations[array_rand($operations)];
        $number1 = rand($min, $max);
        $number2 = rand($min, $max);

        $exercise = '';
        $solution = '';

        switch ($operation) {
            case 'sum':
                $exercise = "$number1 + $number2 =";
                $solution = $number1 + $number2;
                break;
            case 'subtraction':
                $exercise = "$number1 - $number2 =";
                $solution = $number1 - $number2;
                break;
            case 'multiplication':
                $exercise = "$number1 x $number2 =";
                $solution = $number1 * $number2;
                break;
            case 'division':
                //avoid division by zero
                if ($number2 == 0) {
                    $number2 = 1;
                }
                $exercise = "$number1 : $number2 =";
                $solution = $number1 / $number2;
                break;
        }

        //if solution is a float number, round it to 2 decimal places
        if (is_float($solution)) {
            $solution = round($solution, 2);
        }

        return [
            'operation' => $operation,
            'exercise_number' => $index,
            'exercise' => $exercise,
            'solution' => "$exercise $solution"
        ];
    }
}
