<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Metastasis;
use App\Models\Pruebas_realizadas;
use App\Models\Tecnicas_realizadas;
use App\Models\Sintomas;
use App\Models\Otros_tumores;
use App\Models\Enfermedad;
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

   private function obtenerValor($opciones)
   {
   	for($i = 0; $i < count($opciones); $i++){
   		if($opciones[$i] != "Ninguna")
   			$numValor = $i;
   	}

   	return $numValor;
   }

   private function obtenerTabla($opciones)
   {
   	$numTabla = $this->obtenerValor($opciones);
   	switch ($numTabla) {
   		case 0:
   			$tabla = 'Pacientes';
   			break;
   		case 1:
   			$tabla = 'Enfermedad';
   			break;
   		case 2:
   			if($opciones[$numTabla] == 'tipo_sintoma')
   				$tabla = 'Sintomas';
   			elseif($opciones[$numTabla] == 'tipo_metastasis')
   				$tabla = 'Metastasis';
   			elseif($opciones[$numTabla] == 'tipo_biomarcador' || $opciones[$numTabla] == 'subtipo_biomarcador')
   				$tabla = 'Biomarcadores';
   			elseif($opciones[$numTabla] == 'tipo_prueba')
   				$tabla = 'Pruebas_realizadas';
   			elseif($opciones[$numTabla] == 'tipo_tecnica')
   				$tabla = 'Tecnicas_realizadas';
   			elseif($opciones[$numTabla] == 'tipo_tumor')
   				$tabla = 'Otros_tumores';	
   			break;	
   		case 3:
   			if($opciones[$numTabla] == 'tipo_antecedente_medico')
   				$tabla = 'Antecedentes_medicos';
   			elseif($opciones[$numTabla] == 'tipo_antecedente_oncologico')
   				$tabla = 'Antecedentes_oncologicos';
   			elseif($opciones[$numTabla] == 'tipo_antecedente_familiar')
   				$tabla = 'Antecedentes_familiares';
   			break;
   	}
   	return $tabla;
   }

      private function obtenerDatos($tabla, $tipoSelec)
   {
	$tipos = ('App\\Models\\'.$tabla)::select($tipoSelec)->groupBy($tipoSelec)->get();
    foreach ($tipos as $tipo) {
    	$numTipo = ('App\\Models\\'.$tabla)::where($tipoSelec,$tipo->$tipoSelec)->count();
		$datosGrafica[$tipo->$tipoSelec] = $numTipo;
    }

    return $datosGrafica;
   }

   private function unaOpcion($opciones)
   {
    $datosGrafica = array();
    $tabla = $this->obtenerTabla($opciones);
    $opcion = $opciones[$this->obtenerValor($opciones)];
    if($tabla == 'Pacientes' or $tabla == 'Enfermedad'){
	    if($opcion == "nacimiento"){
			$datosGrafica = $this->calcularIntervalosEdad($request);
	    }else{
	    	$datosGrafica = $this->obtenerDatos($tabla, $opcion);
	    }
	}elseif($tabla == 'Biomarcadores'){
		if($opcion == 'tipo_biomarcador'){
			$datosGrafica = $this->obtenerDatos($tabla, 'nombre');
		}else{
			$datosGrafica = $this->obtenerDatos($tabla, 'tipo');
		}
	}elseif($tabla == 'Antecedentes_familiares'){
		$datosGrafica = $this->obtenerDatos($tabla, 'familiar');
	}elseif($tabla == 'Antecedentes_medicos'){
		$datosGrafica = $this->obtenerDatos($tabla, 'tipo_antecedente');
	}else{
		$datosGrafica = $this->obtenerDatos($tabla, 'tipo');
	}

      return $datosGrafica;
   }

   public function imprimirGraficas(Request $request)
   {
    if(in_array("Ninguna",$request->opciones)){
		$numNinguno = array_count_values($request->opciones)["Ninguna"];
      	$numDifNinguno = count($request->opciones) - $numNinguno;
    }else{
      	$numDifNinguno = count($request->opciones);
    } 
    switch ($numDifNinguno) {
        case 0:
        	$datosGrafica = null;
            break;
        case 1:
            $datosGrafica = $this->unaOpcion($request->opciones);
            break;
        case 2:
            echo "i es igual a 2";
            break;
    }
    	$tipo = $this->obtenerValor($request->opciones);

   		return view('mostrargrafica',['datosGrafica' => $datosGrafica, 'tipo' => $request->opciones[$tipo] ]);
   }
}
