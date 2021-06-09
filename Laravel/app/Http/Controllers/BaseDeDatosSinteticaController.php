<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class BaseDeDatosSinteticaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verBaseSintetica()
    {
    	return view('basesintetica');
    }

    public function crearBaseSintetica(Request $request)
    { 	
    	$text = 'The text you are desperate to analyze :)';
    	$process = new Process(['py', 'C:\wamp64\www\prueba.py', "HOLA"]);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		echo $process->getOutput();
    }

}