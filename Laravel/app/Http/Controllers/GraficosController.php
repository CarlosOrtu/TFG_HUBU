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
use App\Models\Tratamientos;
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
    $numTipo = Pacientes::where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count();
	$datosGrafica["Mayores de ".$valorAnterior] = $numTipo;

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
   			elseif($opciones[$numTabla] == 'familiar_antecedente')
   				$tabla = 'Antecedentes_familiares';
   			elseif($opciones[$numTabla] == 'tipo_antecedente_familiar')
   				$tabla = 'Enfermedades_familiar';
   			break;
   		case 4:
   			if($opciones[$numTabla] == 'intencion_quimioterapia' || $opciones[$numTabla] == 'tipo_radioterapia' || $opciones[$numTabla] == 'dosis' || $opciones[$numTabla] == 'localizacion' || $opciones[$numTabla] == 'duracion_radioterapia' || $opciones[$numTabla] == 'tipo_cirugia' || $opciones[$numTabla] == 'tipo_tratamiento' ||  $opciones[$numTabla] == 'duracion_quimioterapia')
   				$tabla = 'Tratamientos';
   			elseif ($opciones[$numTabla] == 'farmacos_quimioterapia') 
   				$tabla = 'Farmacos';
   			else
   				$tabla = 'Intenciones';
   			break;
   	}
   	return $tabla;
   }

   private function obtenerDatos($tabla, $tipoSelec)
   {
	$tipos = ('App\\Models\\'.$tabla)::select($tipoSelec)->groupBy($tipoSelec)->get();
    foreach ($tipos as $tipo) {
    	$numTipo = ('App\\Models\\'.$tabla)::whereNotNull($tipoSelec)->where($tipoSelec,$tipo->$tipoSelec)->count();
		$datosGrafica[$tipo->$tipoSelec] = $numTipo;
    }

    return $datosGrafica;
   }

   private function obtenerSubtipoTratamiento($tabla, $tipoTratamiento)
   {
	$tipos = ('App\\Models\\'.$tabla)::select('subtipo')->groupBy('subtipo')->get();
    foreach ($tipos as $tipo) {
    	$numTipo = ('App\\Models\\'.$tabla)::whereNotNull('subtipo')->where('tipo',$tipoTratamiento)->where('subtipo',$tipo->subtipo)->count();
		$datosGrafica[$tipo->subtipo] = $numTipo;
    }

    return $datosGrafica;
   }

   private function obtenerDuracion($tipoTratamiento)
   {
	$tratamientos = Tratamientos::where('tipo',$tipoTratamiento)->get();
	$diferenciasFechas = array();
	foreach ($tratamientos as $tratamiento) {
		$fechaIni = strtotime($tratamiento->fecha_inicio);
		$fechaFin = strtotime($tratamiento->fecha_fin);
		$difSegundos = $fechaFin - $fechaIni;
		$difDias = $difSegundos/86400;
		array_push($diferenciasFechas, $difDias);
    }
    $datosGrafica = array_count_values($diferenciasFechas);

    return $datosGrafica;
   }

   private function unaOpcion($request)
   {
    $datosGrafica = array();
    $opciones = $request->opciones;
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
	}elseif($tabla == 'Antecedentes_familiares')
		$datosGrafica = $this->obtenerDatos($tabla, 'familiar');
	elseif($tabla == 'Antecedentes_medicos')
		$datosGrafica = $this->obtenerDatos($tabla, 'tipo_antecedente');
	elseif($tabla == 'Intenciones' || $tabla == 'Tratamientos'){
		if($opcion == 'tipo_tratamiento')
			$datosGrafica = $this->obtenerDatos($tabla, 'tipo');
		elseif($opcion == 'intencion_quimioterapia')
			$datosGrafica = $this->obtenerSubtipoTratamiento($tabla, 'Quimioterapia');
		elseif($opcion == 'tipo_radioterapia')
			$datosGrafica = $this->obtenerSubtipoTratamiento($tabla, 'Radioterapia');
		elseif($opcion == 'tipo_cirugia')
			$datosGrafica = $this->obtenerSubtipoTratamiento($tabla, 'Cirugia');
		elseif($opcion == 'duracion_radioterapia')
			$datosGrafica = $this->obtenerDuracion('Radioterapia');
		elseif($opcion == 'duracion_quimioterapia')
			$datosGrafica = $this->obtenerDuracion('Quimioterapia');
		else	
			$datosGrafica = $this->obtenerDatos($tabla, $opcion);
	}else
		$datosGrafica = $this->obtenerDatos($tabla, 'tipo');
	

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
            $datosGrafica = $this->unaOpcion($request);
            break;
        case 2:
            echo "i es igual a 2";
            break;
    }
    	$tipo = $this->obtenerValor($request->opciones);

   		return view('mostrargrafica',['datosGrafica' => $datosGrafica, 'tipo' => $request->opciones[$tipo] ]);
   }
}
