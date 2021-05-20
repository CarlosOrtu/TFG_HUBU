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
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
   		if($opciones[$i] != "Ninguna"){
   			$numValor = $i;
        break;
      }
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
   			if($opciones[$numTabla] == 'tipo_sintoma' || $opciones[$numTabla] == 'num_sintoma')
   				$tabla = 'Sintomas';
   			elseif($opciones[$numTabla] == 'tipo_metastasis' || $opciones[$numTabla] == 'num_metastasis')
   				$tabla = 'Metastasis';
   			elseif($opciones[$numTabla] == 'tipo_biomarcador' || $opciones[$numTabla] == 'subtipo_biomarcador' || $opciones[$numTabla] == 'num_biomarcador')
   				$tabla = 'Biomarcadores';
   			elseif($opciones[$numTabla] == 'tipo_prueba' || $opciones[$numTabla] == 'num_prueba')
   				$tabla = 'Pruebas_realizadas';
   			elseif($opciones[$numTabla] == 'tipo_tecnica' || $opciones[$numTabla] == 'num_tecnica')
   				$tabla = 'Tecnicas_realizadas';
   			elseif($opciones[$numTabla] == 'tipo_tumor' || $opciones[$numTabla] == 'num_tumor')
   				$tabla = 'Otros_tumores';	
   			break;	
   		case 3:
   			if($opciones[$numTabla] == 'tipo_antecedente_medico' || $opciones[$numTabla] == 'num_antecedente_medico')
   				$tabla = 'Antecedentes_medicos';
   			elseif($opciones[$numTabla] == 'tipo_antecedente_oncologico' || $opciones[$numTabla] == 'num_antecedente_oncologico')
   				$tabla = 'Antecedentes_oncologicos';
   			elseif($opciones[$numTabla] == 'familiar_antecedente' || $opciones[$numTabla] == 'num_familiar_antecedente')
   				$tabla = 'Antecedentes_familiares';
   			elseif($opciones[$numTabla] == 'tipo_antecedente_familiar' || $opciones[$numTabla] == 'num_antecedente_familiar')
   				$tabla = 'Enfermedades_familiar';
   			break;
   		case 4:
   			if($opciones[$numTabla] == 'intencion_quimioterapia' || $opciones[$numTabla] == 'tipo_radioterapia' || $opciones[$numTabla] == 'dosis' || $opciones[$numTabla] == 'localizacion' || $opciones[$numTabla] == 'duracion_radioterapia' || $opciones[$numTabla] == 'tipo_cirugia' || $opciones[$numTabla] == 'tipo_tratamiento' ||  $opciones[$numTabla] == 'duracion_quimioterapia' || preg_match("/^num/", $opciones[$numTabla]))
   				$tabla = 'Tratamientos';
   			elseif ($opciones[$numTabla] == 'farmacos_quimioterapia') 
   				$tabla = 'Farmacos';
   			else
   				$tabla = 'Intenciones';
   			break;
      case 5:
        if($opciones[$numTabla] == 'estado_seguimiento' || $opciones[$numTabla] == 'fallecido_motivo' || $opciones[$numTabla] == 'num_seguimiento')
          $tabla = 'Seguimientos';
        else
          $tabla = 'Reevaluaciones';
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

   private function obtenerNumero($tabla,$tipo,$id)
   {
    $filas = ('App\\Models\\'.$tabla)::select($id)->groupBy($id)->get();
    $numDatos = array();
    foreach ($filas as $fila) {
      if($tipo == 'todos' or $tipo == 'seguimiento' or $tipo == 'reevaluacion')
        $numDato = ('App\\Models\\'.$tabla)::where($id,$fila->$id)->count();
      else
        $numDato = ('App\\Models\\'.$tabla)::where($id,$fila->$id)->where('tipo',$tipo)->count();
      array_push($numDatos, $numDato);
    }
    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   private function obtenerEnfermedadesAnte()
   {
    $filas = Antecedentes_familiares::select('id_paciente')->groupBy('id_paciente')->get();
    $numEnfermedades = array();
    foreach ($filas as $fila) {
      $antecedentesFamPaciente = Antecedentes_familiares::where('id_paciente',$fila->id_paciente)->get();
      $numEnfermedadTotal = 0;
      foreach($antecedentesFamPaciente as $antecedente){
        $numEnfermedad = Enfermedades_familiar::where('id_antecedente_f',$antecedente->id_antecedente_f)->count();
        $numEnfermedadTotal = $numEnfermedad + $numEnfermedadTotal;
      }
      array_push($numEnfermedades, $numEnfermedadTotal);
    }
    $datosGrafica = array_count_values($numEnfermedades);

    return $datosGrafica;
   }

   private function unaOpcion($request)
   {
    $datosGrafica = array();
    $opciones = $request->opciones;
    $tabla = $this->obtenerTabla($opciones);
    $opcion = $opciones[$this->obtenerValor($opciones)];
    //Pacientes | Enfermedad | Seguimientos | Reevaluaciones
    if($tabla == 'Pacientes' or $tabla == 'Enfermedad' or $tabla == 'Seguimientos' or  $tabla == 'Reevaluaciones'){
	    if($opcion == "nacimiento")
			 $datosGrafica = $this->calcularIntervalosEdad($request);
	    elseif($opcion == "estado_seguimiento")
        $datosGrafica = $this->obtenerDatos($tabla, 'estado');
      elseif($opcion == "num_reevaluacion")
        $datosGrafica = $this->obtenerNumero('Reevaluaciones','reevaluacion','id_paciente');
      elseif($opcion == "num_seguimiento")
        $datosGrafica = $this->obtenerNumero('Seguimientos','seguimiento','id_paciente');
      else
	    	$datosGrafica = $this->obtenerDatos($tabla, $opcion);
    //Biomarcadores
  	}elseif($tabla == 'Biomarcadores'){
  		if($opcion == 'tipo_biomarcador')
  			$datosGrafica = $this->obtenerDatos($tabla, 'nombre');
  		elseif($opcion == 'num_biomarcador')
        $datosGrafica = $this->obtenerNumero($tabla, 'todos','id_enfermedad');
      else  
  			$datosGrafica = $this->obtenerDatos($tabla, 'tipo');
    //Antecedentes familiares
  	}elseif($tabla == 'Antecedentes_familiares'){
      if($opcion == 'num_familiar_antecedente')
        $datosGrafica = $this->obtenerNumero($tabla,'todos','id_paciente');
      else  
  		  $datosGrafica = $this->obtenerDatos($tabla, 'familiar');
    //Antecedentes medicos
    }elseif($tabla == 'Antecedentes_medicos'){
      if($opcion == 'num_antecedente_medico')
        $datosGrafica = $this->obtenerNumero($tabla,'todos','id_paciente');
      else  
  		  $datosGrafica = $this->obtenerDatos($tabla, 'tipo_antecedente');
    }
    //Intenciones | Tratamientos
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
      elseif($opcion == 'num_tratamientos')
        $datosGrafica = $this->obtenerNumero('Tratamientos','todos','id_paciente');
      elseif($opcion == 'num_quimioterapia')
        $datosGrafica = $this->obtenerNumero('Tratamientos','Quimioterapia','id_paciente');
      elseif($opcion == 'num_radioterapia')
        $datosGrafica = $this->obtenerNumero('Tratamientos','Radioterapia','id_paciente');
      elseif($opcion == 'num_cirugia')
        $datosGrafica = $this->obtenerNumero('Tratamientos','Cirugia','id_paciente');
  		else	
  			$datosGrafica = $this->obtenerDatos($tabla, $opcion);
  	}elseif($opcion == 'estado_seguimiento')
      $datosGrafica = $this->obtenerDatos($tabla, 'estado');
    elseif($opcion == 'num_antecedente_oncologico')
      $datosGrafica = $this->obtenerNumero($tabla,'todos','id_paciente');
    elseif($opcion == 'num_antecedente_familiar')
      $datosGrafica = $this->obtenerEnfermedadesAnte();
    elseif(preg_match("/^num/", $opcion))
      $datosGrafica = $this->obtenerNumero($tabla,'todos','id_enfermedad');
    else
  		$datosGrafica = $this->obtenerDatos($tabla, 'tipo');
	
    return $datosGrafica;
   }

   private function hacerJoinTablas($tabla1,$tabla2)
   {
    if($tabla1 == 'Pacientes'){
      if($tabla2 == 'Enfermedad')
        $joinTablas = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente');
      elseif($tabla2 == 'Otros_tumores' || $tabla2 == 'Tecnicas_realizadas' || $tabla2 == 'Pruebas_realizadas' || $tabla2 == 'Biomarcadores' || $tabla2 == 'Sintomas' || $tabla2 == 'Metastasis')
        $joinTablas = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente')->join($tabla2, 'Enfermedades.id_enfermedad', '=', $tabla2.'.id_enfermedad');
      elseif($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Pacientes')->join('tratamientos', 'Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->join('intenciones', 'tratamientos.id_tratamiento', '=', 'intenciones.id_tratamiento');
      elseif($tabla2 == 'Farmacos')
        $joinTablas = DB::table('Pacientes')->join('tratamientos', 'Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->join('intenciones', 'Enfermedades.id_enfermedad', '=', 'intenciones.id_enfermedad')->join('farmacos','intenciones.id_intencion','=','farmacos.id_intencion');
      elseif($tabla2 == 'Enfermedades_familiar')
        $joinTablas = DB::table('Pacientes')->join('Antecedentes_familiares', 'Pacientes.id_paciente', '=', 'Antecedentes_familiares.id_paciente')->join('Enfermedades_familiar','Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f');
      else
        $joinTablas = DB::table('Pacientes')->join($tabla2, 'Pacientes.id_paciente', '=', $tabla2.'.id_paciente');
    }elseif($tabla1 == 'Enfermedad' || $tabla1 == 'Otros_tumores' || $tabla1 == 'Tecnicas_realizadas' || $tabla1 == 'Pruebas_realizadas' || $tabla1 == 'Biomarcadores' || $tabla1 == 'Sintomas' || $tabla1 == 'Metastasis'){
      if($tabla2 == 'Enfermedad' || $tabla2 == 'Otros_tumores' || $tabla2 == 'Tecnicas_realizadas' || $tabla2 == 'Pruebas_realizadas' || $tabla2 == 'Biomarcadores' || $tabla2 == 'Sintomas' || $tabla2 == 'Metastasis')
        $joinTablas = DB::table($tabla1)->join($tabla2, $tabla1.'.id_enfermedad', '=', $tabla2.'.id_enfermedad');
      elseif($tabla2 == 'Seguimientos' || $tabla2 == 'Antecedentes_medicos' || $tabla2 == 'Tratamientos' || $tabla2 == 'Antecedentes_oncologicos' || $tabla2 == 'Antecedentes_familiares' || $tabla2 == 'Reevaluaciones')
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join($tabla2, 'Enfermedades.id_paciente','=',$tabla2.'.id_paciente');
      elseif($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Enfermedades.id_paciente','=','Intenciones.id_paciente');
      elseif($tabla2 == 'Farmacos')
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Enfermedades.id_paciente','=','Intenciones.id_paciente')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion');
      else
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Antecedentes_familiares','Enfermedades.id_paciente','=','Antecedentes_familiares.id_paciente')->join('Enfermedades_familiar', 'Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f');
	 }elseif($tabla1 == 'Enfermedades_familiar'){
	 	$joinTablas = DB::table('Enfermedades_familiar')->join('Antecedentes_familiares', 'Enfermedades_familiar.id_antecedente_f', '=', 'Antecedentes_familiares.id_antecedente_f')->join($tabla2,'Antecedentes_familiares.id_paciente','=',$tabla2.'.id_paciente');
	 }elseif($tabla1 == 'Intenciones'){
	 	$joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join($tabla2,'Tratamientos.id_paciente','=',$tabla2.'.id_paciente');
	 }elseif($tabla1 == 'Farmacos'){
	 	$joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion')->join($tabla2,'Tratamientos.id_paciente','=',$tabla2.'.id_paciente');
	 }else{
	 	$joinTablas = DB::table($tabla1)->join($tabla2, $tabla1.'.id_paciente', '=', $tabla2.'.id_paciente');
	 }
	dd($joinTablas);
    return $joinTablas; 
   }

   private function obtenerDatosDosOpciones($tabla1,$tabla2,$tipoSelec1,$tipoSelec2)
   {
   	$joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $tipos1 = ('App\\Models\\'.$tabla1)::select($tipoSelec1)->groupBy($tipoSelec1)->get();
    $tipos2 = ('App\\Models\\'.$tabla2)::select($tipoSelec2)->groupBy($tipoSelec2)->get();
    foreach ($tipos1 as $tipo1) {
      foreach($tipos2 as $tipo2){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
        $numTipo = $joinTablas->whereNotNull($tipoSelec1)->whereNotNull($tipoSelec2)->where($tipoSelec1,$tipo1->$tipoSelec1)->where($tipoSelec2,$tipo2->$tipoSelec2)->count();
        $datosGrafica[$tipo1->$tipoSelec1.' y '.$tipo2->$tipoSelec2] = $numTipo;
      }
    }

    return $datosGrafica;
   }

   private function dosOpciones($request)
   {
    $datosGrafica = array();
    $opciones = $request->opciones;
    $tabla1 = $this->obtenerTabla($opciones);
    $opcion1 = $opciones[$this->obtenerValor($opciones)];
    $opciones[$this->obtenerValor($opciones)] = "Ninguna";
    $tabla2 = $this->obtenerTabla($opciones);
    $opcion2 = $opciones[$this->obtenerValor($opciones)];
    $datosGrafica = $this->obtenerDatosDosOpciones($tabla1,$tabla2,$opcion1,$opcion2);

    return $datosGrafica;
   }

   public function imprimirGraficas(Request $request)
   {
    //try{
      if(in_array("Ninguna",$request->opciones)){
  		    $numNinguno = array_count_values($request->opciones)["Ninguna"];
          $numDifNinguno = count($request->opciones) - $numNinguno;
      }else{
          $numDifNinguno = count($request->opciones);
      } 
      switch ($numDifNinguno) {
          case 0:
              return redirect()->route('vergraficas')->with('errorVacio','Selecciona alguna división para la grafica');
          case 1:
              $datosGrafica = $this->unaOpcion($request);
              break;
          case 2:
              $datosGrafica = $this->dosOpciones($request);
              break;
          case 3:
              break;
          case 4:
            return redirect()->route('vergraficas')->with('errorMax','No puedes seleccionar más de 3 divisiones');
            break;
          default:
            return redirect()->route('vergraficas')->with('errorMax','No puedes seleccionar más de 3 divisiones');
            break;
      }
    	$tipo = $this->obtenerValor($request->opciones);

   		return view('mostrargrafica',['datosGrafica' => $datosGrafica, 'tipo' => $request->opciones[$tipo] ]);
      /*
    }catch (\Exception $e){
      $opcion = $request->opciones[$this->obtenerValor($request->opciones)];
      return redirect()->route('vergraficas')->with('errorNoExisteCampo','No hay ningun dato guardado sobre '.$opcion);
    }
    */
   }
}
