<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function home()
    {
        echo 'apresentar a página inicial';
    }
    
    public function generateExercises(Request $request)
    {
        echo 'gerar exerícios';
    }

    public function printExercises()
    {
        echo 'imprimir exercícios no navegador';
    }

    public function exportExercises()
    {
        echo 'exportar exercícos para um arquivo de texto';
    }
}
