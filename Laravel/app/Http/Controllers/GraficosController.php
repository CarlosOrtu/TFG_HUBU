<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use Illuminate\Support\Facades\Schema;


class GraficosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

   public function verGraficas()
   {
   		$pacientes = Pacientes::all();
   		$columnas = Schema::getColumnListing('Pacientes');
        return view('graficas',['pacientes' => $pacientes]);
   }

   public function imprimirGraficas(Request $request)
   {
   		$opcion = $request->opcion;
   		$tipos = Pacientes::select($opcion)->groupBy($opcion)->get();
   		$datosGrafica = array();
   		if($opcion == "nacimiento"){


   		}else{
	   		foreach ($tipos as $tipo) {
	   			$numTipo = Pacientes::where($opcion,$tipo->$opcion)->count();
	   			$datosGrafica[$tipo->$opcion] = $numTipo;
	   		}
	   	}
   		return view('mostrargrafica',['datosGrafica' => $datosGrafica, 'tipo' => $opcion]);
   }
}
