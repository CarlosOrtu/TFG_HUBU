<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Facades\Validator;


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

    public function validarDatosBaseSintetica($request)
    {
        $validator = Validator::make($request->all(), [
                'num_pacientes' => 'required|gte:0',
                'media_tamano_tumor' => 'required|gte:0',
                'desviacion_tamano_tumor' => 'required|gte:0',
                'media_dosis' => 'required|gte:0',
                'desviacion_dosis' => 'required|gte:0',
                'lambda_cigarros' => 'required|gte:0',
                'lambda_ciclos' => 'required|gte:0',
                'p_tablas' => 'required|gt:0|lte:1',
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
                'gt' => 'Tiene que ser un número mayor que 0',
                'lt' => 'Tiene que ser un número menor que 1',
            ]);

        return $validator;

    }

    public function crearBaseSintetica(Request $request)
    { 	
        $validator = $this->validarDatosBaseSintetica($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
    	$process = new Process(['py', 'C:\wamp64\www\TFG_HUBU\Base de datos sintética\BaseDatosSintetica.py', $request->num_pacientes, $request->media_tamano_tumor, $request->desviacion_tamano_tumor, $request->media_dosis, $request->desviacion_dosis, $request->lambda_cigarros, $request->lambda_ciclos, $request->p_tablas]);
		$process->run();

		// executes after the command finishes
		if (!$process->isSuccessful()) {
		    throw new ProcessFailedException($process);
		}

		echo $process->getOutput();
    }

}