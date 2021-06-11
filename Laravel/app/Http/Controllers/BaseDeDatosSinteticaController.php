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
    	$process = new Process(['py', 'C:\wamp64\www\TFG_HUBU\Base de datos sintética\BaseDatosSintetica.py', $request->num_pacientes, $request->media_tamano_tumor, $request->desviacion_tamano_tumor, $request->media_dosis, $request->desviacion_dosis, $request->lambda_cigarros, $request->lambda_ciclos, $request->p_tablas]);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		echo $process->getOutput();
    }

}