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

   private function calcularIntervalosEdad($request)
   {
    $fechaActual = date('Y-m-d');
    $datosGrafica = array();
    $valorAnterior = null;
    for($i = $request->edadIntervalo; $i < 100;$i += $request->edadIntervalo){
      if($valorAnterior == null){
        $numTipo = Pacientes::where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->count();
        $datosGrafica["Menores de ".$i] = $numTipo;
      }else{
        $numTipo = Pacientes::where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count();
        $datosGrafica["Entre ".$valorAnterior." y ".$i] = $numTipo;
      }
      $valorAnterior = $i;
    }
    return $datosGrafica;
   }

   private function unaOpcion($opcion, $tabla){
      $datosGrafica = array();
      $tipos = ('App\\Models\\'.$tabla)::select($opcion)->groupBy($opcion)->get();
      if($opcionPaciente == "nacimiento"){
        $datosGrafica = $this->calcularIntervalosEdad($request);
      }else{
        foreach ($tipos as $tipo) {
          $numTipo = ('App\\Models\\'.$tabla)::where($opcion,$tipo->$opcion)->count();
          $datosGrafica[$tipo->$opcion] = $numTipo;
        }
      }
   }

   public function imprimirGraficas(Request $request)
   {
    if(in_array("Ninguna",$request->opciones)){
  		$numNinguno = array_count_values($request->opciones)["Ninguna"];
      $numDifNinguno = count($request->opciones) - $numNinguno;
    }else{
      $numDifNinguno = count($request->opciones);
    } 
    dd($numDifNinguno);
    switch ($i) {
        case 0:
            break;
        case 1:
            unaOpcion($request->opciones[0])
            break;
        case 2:
            echo "i es igual a 2";
            break;
    }
      /*
   		$tipos = Pacientes::select($opcionPaciente)->groupBy($opcionPaciente)->get();
   		$datosGrafica = array();
   		if($opcionPaciente == "nacimiento"){
        $datosGrafica = $this->calcularIntervalosEdad($request);
   		}elseif($opcionPaciente != "Ninguna"){
	   		foreach ($tipos as $tipo) {
	   			$numTipo = Pacientes::where($opcionPaciente,$tipo->$opcionPaciente)->count();
	   			$datosGrafica[$tipo->$opcionPaciente] = $numTipo;
	   		}
	   	}
      */
   		return view('mostrargrafica',['datosGrafica' => $datosGrafica, 'tipo' => $opcionPaciente]);
   }
}
