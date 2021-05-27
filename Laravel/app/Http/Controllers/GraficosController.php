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
use App\Models\Enfermedades;
use App\Models\Tratamientos;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;
use App\Models\Antecedentes_medicos;
use App\Models\Biomarcadores;
use App\Models\Seguimientos;
use App\Models\Farmacos;
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

    /******************************************************************
    *                                                                 *
    * Graficas un dato                                                *
    *                                                                 *
    *******************************************************************/
    //Obtenemos los datos para dibujar la gráfica cuando el campo es la edad
   private function calcularIntervalosEdad($request)
   {
    $fechaActual = date('Y-m-d');
    $datosGrafica = array();
    $valorAnterior = null;
    $intervaloEdad = $this->obtenerEdad($request);
    for($i = $intervaloEdad; $i < 100;$i += $intervaloEdad){
        if($valorAnterior == null){
          $numTipo = Pacientes::where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->count();
          if($numTipo != 0)
           $datosGrafica["Menores de ".$i] = $numTipo;
        }else{
          $numTipo = Pacientes::where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count();
          if($numTipo != 0)
           $datosGrafica["Entre ".$valorAnterior."-".$i] = $numTipo;
        }
        $valorAnterior = $i;
    }
    $numTipo = Pacientes::where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count();
    if($numTipo != 0)
     $datosGrafica["Mayores de ".$valorAnterior] = $numTipo;

    return $datosGrafica;
   }

   //Obtenemos el valor del request que sea diferente a "Ninguna"
   private function obtenerValor($opciones)
   {
    for($i = 0; $i < count($opciones); $i++){
      if($opciones[$i] != "Ninguna")
        return $i;
    }
   }

   //Obtenemos la tabla que corresponde con el dato introducido
   private function obtenerTabla($opciones)
   {
    $numTabla = $this->obtenerValor($opciones);
    switch (true) {
      case $numTabla <= 2:
        $tabla = 'Pacientes';
        break;
      case $numTabla <= 5:
        $tabla = 'Enfermedades';
        break;
      case $numTabla <= 8:
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
      case $numTabla <= 11:
        if($opciones[$numTabla] == 'tipo_antecedente_medico' || $opciones[$numTabla] == 'num_antecedente_medico')
          $tabla = 'Antecedentes_medicos';
        elseif($opciones[$numTabla] == 'tipo_antecedente_oncologico' || $opciones[$numTabla] == 'num_antecedente_oncologico')
          $tabla = 'Antecedentes_oncologicos';
        elseif($opciones[$numTabla] == 'familiar_antecedente' || $opciones[$numTabla] == 'num_familiar_antecedente')
          $tabla = 'Antecedentes_familiares';
        elseif($opciones[$numTabla] == 'tipo_antecedente_familiar' || $opciones[$numTabla] == 'num_antecedente_familiar')
          $tabla = 'Enfermedades_familiar';
        break;
      case $numTabla <= 14:
        if($opciones[$numTabla] == 'intencion_quimioterapia' || $opciones[$numTabla] == 'tipo_radioterapia' || $opciones[$numTabla] == 'dosis' || $opciones[$numTabla] == 'localizacion' || $opciones[$numTabla] == 'duracion_radioterapia' || $opciones[$numTabla] == 'tipo_cirugia' || $opciones[$numTabla] == 'tipo_tratamiento' ||  $opciones[$numTabla] == 'duracion_quimioterapia' || preg_match("/^num_/", $opciones[$numTabla]))
          $tabla = 'Tratamientos';
        elseif ($opciones[$numTabla] == 'farmacos_quimioterapia') 
          $tabla = 'Farmacos';
        else
          $tabla = 'Intenciones';
        break;
      case $numTabla <= 17:
        if($opciones[$numTabla] == 'estado_seguimiento' || $opciones[$numTabla] == 'fallecido_motivo' || $opciones[$numTabla] == 'num_seguimiento')
          $tabla = 'Seguimientos';
        else
          $tabla = 'Reevaluaciones';
    }
    return $tabla;
   }

    //Obtenemos los datos para dibujar la gráfica cuando el campo es la nominal
   private function obtenerDatos($tabla, $tipoSelec)
   {
    $tipos = ('App\\Models\\'.$tabla)::select($tipoSelec)->groupBy($tipoSelec)->get();
    foreach ($tipos as $tipo) {
      $numTipo = ('App\\Models\\'.$tabla)::whereNotNull($tipoSelec)->where($tipoSelec,$tipo->$tipoSelec)->count();
    $datosGrafica[$tipo->$tipoSelec] = $numTipo;
    }

    return $datosGrafica;
   }

    //Obtenemos los datos para dibujar la gráfica cuando el campo es un subtipo de tratamiento
   private function obtenerSubtipoTratamiento($tabla, $tipoTratamiento)
   {
    $tipos = ('App\\Models\\'.$tabla)::select('subtipo')->groupBy('subtipo')->get();
    foreach ($tipos as $tipo) {
      $numTipo = ('App\\Models\\'.$tabla)::whereNotNull('subtipo')->where('tipo',$tipoTratamiento)->where('subtipo',$tipo->subtipo)->count();
    $datosGrafica[$tipo->subtipo] = $numTipo;
    }

    return $datosGrafica;
   }

    //Obtenemos los datos para dibujar la gráfica cuando el campo es una duración
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

    //Obtenemos los datos para dibujar la gráfica cuando el campo es la numerico
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

   //Se obtienes los valores para dibujar la grafica cuando 
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

   //Segun el tipo de dato se llama a una funcuion o a otra
   private function unaOpcion($request)
   {
    $datosGrafica = array();
    $opciones = $request->opciones;
    $tabla = $this->obtenerTabla($opciones);
    $opcion = $opciones[$this->obtenerValor($opciones)];
    //Pacientes | Enfermedad | Seguimientos | Reevaluaciones
    if($tabla == 'Pacientes' or $tabla == 'Enfermedades' or $tabla == 'Seguimientos' or  $tabla == 'Reevaluaciones'){
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


    /******************************************************************
    *                                                                 *
    * Graficas dos datos                                              *
    *                                                                 *
    *******************************************************************/
    //Devolvemos el campo a seleccionar segun la request y la tabla
   private function campoASeleccionar($tabla,$opcion)
   {
    //Pacientes | Enfermedad | Seguimientos | Reevaluaciones
    if($tabla == 'Pacientes' or $tabla == 'Enfermedades' or $tabla == 'Seguimientos' or  $tabla == 'Reevaluaciones')
      if($opcion == 'estado_seguimiento')
        return 'estado';
      else
       return $opcion;
    //Biomarcadores
    elseif($tabla == 'Biomarcadores'){
      if($opcion == 'tipo_biomarcador')
        return 'tipo_biomarcador';
      elseif($opcion == 'num_biomarcador')
          return 'num_biomarcador';
      else  
        return 'tipo';
    //Antecedentes familiares
    }elseif($tabla == 'Antecedentes_familiares'){
      if($opcion == 'num_familiar_antecedente')
        return 'num_familiar_antecedente';  
      else  
        return 'familiar';
    //Antecedentes medicos
    }elseif($tabla == 'Antecedentes_medicos'){
      if($opcion == 'num_antecedente_medico')
        return 'num_antecedente_medico';
      else  
      return 'tipo_antecedente';
    }
    //Intenciones | Tratamientos
    elseif($tabla == 'Intenciones' || $tabla == 'Tratamientos'){
      if($opcion == 'tipo_tratamiento')
        return 'tipo';
      elseif($opcion == 'duracion_radioterapia')
        return 'duracion_radioterapia';
      elseif($opcion == 'duracion_quimioterapia')
        return 'duracion_quimioterapia';
      elseif($opcion == 'num_tratamientos')
        return 'num_tratamientos';
      elseif($opcion == 'num_quimioterapia')
          return 'num_quimioterapia';
      elseif($opcion == 'num_radioterapia')
          return 'num_radioterapia';
      elseif($opcion == 'num_cirugia')
          return 'num_cirugia';
      else  
        return $opcion;
    }elseif($opcion == 'farmacos_quimioterapia')
      return 'farmacos_quimioterapia';
    elseif(preg_match("/^num/", $opcion))
      return $opcion;
    else
      return 'tipo';
    }

    private function joinTablasPaciente($tabla2)
    {
      if($tabla2 == 'Enfermedades')
        $joinTablas = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente');
      elseif($tabla2 == 'Otros_tumores' || $tabla2 == 'Tecnicas_realizadas' || $tabla2 == 'Pruebas_realizadas' || $tabla2 == 'Biomarcadores' || $tabla2 == 'Sintomas' || $tabla2 == 'Metastasis')
        $joinTablas = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente')->join($tabla2, 'Enfermedades.id_enfermedad', '=', $tabla2.'.id_enfermedad');
      elseif($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Pacientes')->join('tratamientos', 'Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->join('intenciones', 'tratamientos.id_tratamiento', '=', 'intenciones.id_tratamiento');
      elseif($tabla2 == 'Farmacos')
        $joinTablas = DB::table('Pacientes')->join('tratamientos', 'Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->join('intenciones', 'tratamientos.id_tratamiento', '=', 'intenciones.id_tratamiento')->join('farmacos','intenciones.id_intencion','=','farmacos.id_intencion');
      elseif($tabla2 == 'Enfermedades_familiar')
        $joinTablas = DB::table('Pacientes')->join('Antecedentes_familiares', 'Pacientes.id_paciente', '=', 'Antecedentes_familiares.id_paciente')->join('Enfermedades_familiar','Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f');
      else
        $joinTablas = DB::table('Pacientes')->join($tabla2, 'Pacientes.id_paciente', '=', $tabla2.'.id_paciente');

      return $joinTablas;
    }

    private function joinTablasEnfermedad($tabla1, $tabla2)
    {
      if($tabla2 == 'Enfermedades' || $tabla2 == 'Otros_tumores' || $tabla2 == 'Tecnicas_realizadas' || $tabla2 == 'Pruebas_realizadas' || $tabla2 == 'Biomarcadores' || $tabla2 == 'Sintomas' || $tabla2 == 'Metastasis')
        $joinTablas = DB::table($tabla1)->join($tabla2, $tabla1.'.id_enfermedad', '=', $tabla2.'.id_enfermedad');
      elseif($tabla2 == 'Seguimientos' || $tabla2 == 'Antecedentes_medicos' || $tabla2 == 'Tratamientos' || $tabla2 == 'Antecedentes_oncologicos' || $tabla2 == 'Antecedentes_familiares' || $tabla2 == 'Reevaluaciones'){
        if($tabla1 == 'Enfermedades')
          $joinTablas = DB::table('Enfermedades')->join($tabla2, 'Enfermedades.id_paciente','=',$tabla2.'.id_paciente');
        else
          $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join($tabla2, 'Enfermedades.id_paciente','=',$tabla2.'.id_paciente');
      }elseif($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento');
      elseif($tabla2 == 'Farmacos'){
        if($tabla1 == 'Enfermedades')
          $joinTablas = DB::table('Enfermedades')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion');
        else
          $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion');
      }else
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Antecedentes_familiares','Enfermedades.id_paciente','=','Antecedentes_familiares.id_paciente')->join('Enfermedades_familiar', 'Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f');

      return $joinTablas;
    }

    private function joinTablasEnfermedades_familiar($tabla2)
    {
      if($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Enfermedades_familiar')->join('Antecedentes_familiares', 'Enfermedades_familiar.id_antecedente_f', '=', 'Antecedentes_familiares.id_antecedente_f')->join('Tratamientos','Antecedentes_familiares.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento');   
      else
        $joinTablas = DB::table('Enfermedades_familiar')->join('Antecedentes_familiares', 'Enfermedades_familiar.id_antecedente_f', '=', 'Antecedentes_familiares.id_antecedente_f')->join($tabla2,'Antecedentes_familiares.id_paciente','=',$tabla2.'.id_paciente'); 

      return $joinTablas;
    }

    private function joinTablasIntenciones($tabla2)
    {
      if($tabla2 == 'Tratamientos')
        $joinTablas =DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento');
      else
        $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join($tabla2,'Tratamientos.id_paciente','=',$tabla2.'.id_paciente');

      return $joinTablas;
    }

    private function joinTablasRestantes($tabla1, $tabla2)
    {
      if($tabla2 == 'Intenciones'){
        if($tabla1 == 'Tratamientos')
          $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento');
        else
          $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join($tabla1, 'Tratamientos.id_paciente', '=', $tabla1.'.id_paciente');
      }elseif($tabla2 == 'Farmacos')
        $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion')->join($tabla1, 'Tratamientos.id_paciente', '=', $tabla1.'.id_paciente');
      else
       $joinTablas = DB::table($tabla1)->join($tabla2, $tabla1.'.id_paciente', '=', $tabla2.'.id_paciente');

     return $joinTablas;
    }

   //Realizamos los join entre tablas según sean necesarios
   private function hacerJoinTablas($tabla1,$tabla2)
   {
    if($tabla1 == $tabla2)
      $joinTablas = DB::table($tabla1);
    elseif($tabla1 == 'Pacientes')
      $joinTablas = $this->joinTablasPaciente($tabla2);
    elseif($tabla1 == 'Enfermedades' || $tabla1 == 'Otros_tumores' || $tabla1 == 'Tecnicas_realizadas' || $tabla1 == 'Pruebas_realizadas' || $tabla1 == 'Biomarcadores' || $tabla1 == 'Sintomas' || $tabla1 == 'Metastasis'){
      $joinTablas = $this->joinTablasEnfermedad($tabla1, $tabla2);
   }elseif($tabla1 == 'Enfermedades_familiar'){
      $joinTablas = $this->joinTablasEnfermedades_familiar($tabla2);
   }elseif($tabla1 == 'Intenciones'){
      $joinTablas = $this->joinTablasIntenciones($tabla2);
   }elseif($tabla1 == 'Farmacos'){
    $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion')->join($tabla2,'Tratamientos.id_paciente','=',$tabla2.'.id_paciente');
   }else{
    $joinTablas = $this->joinTablasRestantes($tabla1, $tabla2);
   }

    return $joinTablas; 
   }

   //Calculamos los tipos segun la tabla y la division
   private function calcularTipos($tabla, $division)
   {
    if($division == 'intencion_quimioterapia')
      $tipos = Tratamientos::select('subtipo')->groupBy('subtipo')->where('tipo','Quimioterapia')->get();
    elseif($division == 'tipo_radioterapia')
      $tipos = Tratamientos::select('subtipo')->groupBy('subtipo')->where('tipo','Radioterapia')->get();
    elseif($division == 'tipo_cirugia')
      $tipos = Tratamientos::select('subtipo')->groupBy('subtipo')->where('tipo','Cirugia')->get();
    elseif($division == 'tipo_biomarcador')
      $tipos = Biomarcadores::select('nombre')->groupBy('nombre')->get();
    elseif($division == 'farmacos_quimioterapia')
      $tipos = Farmacos::select('tipo')->groupBy('tipo')->get();
    else
      $tipos = ('App\\Models\\'.$tabla)::select($division)->groupBy($division)->get();

    return $tipos;
   }

   //Calculamos las nuevas divisiones 
   private function calcularNuevaDivision($division)
   {
    if($division == 'intencion_quimioterapia')
      $divisionNueva = 'subtipo';
    elseif($division == 'tipo_radioterapia')
      $divisionNueva = 'subtipo';
    elseif($division == 'tipo_cirugia')
      $divisionNueva = 'subtipo';
    elseif($division == 'tipo_biomarcador')
      $divisionNueva = 'nombre';
    elseif($division == 'farmacos_quimioterapia')
      $divisionNueva = 'tipo';
    else
      $divisionNueva = $division;

    return $divisionNueva;
   }

   //Obtenemos los datos para dibujar la grafica cuando los dos campos son nominales
   private function obtenerDatosDosOpciones($tabla1,$tabla2,$tipoSelec1,$tipoSelec2)
   {
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $division1 = $this->campoASeleccionar($tabla1,$tipoSelec1);
    $division2 = $this->campoASeleccionar($tabla2,$tipoSelec2);
    $tipos1 = $this->calcularTipos($tabla1, $division1);
    $tipos2 = $this->calcularTipos($tabla2, $division2);
    $division1 = $this->calcularNuevaDivision($division1);
    $division2 = $this->calcularNuevaDivision($division2);
    foreach ($tipos1 as $tipo1) {
      foreach($tipos2 as $tipo2){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
        if($tabla1 == 'Farmacos')
          $numTipo = $joinTablas->whereNotNull('Farmacos.tipo')->whereNotNull($tabla2.'.'.$division2)->where('Farmacos.tipo',$tipo1->$division1)->where($tabla2.'.'.$division2,$tipo2->$division2)->count();
        elseif($tabla2 == 'Farmacos')
          $numTipo = $joinTablas->whereNotNull($tabla1.'.'.$division1)->whereNotNull('Farmacos.tipo')->where($tabla1.'.'.$division1,$tipo1->$division1)->where('Farmacos.tipo',$tipo2->$division2)->count();
        else
          $numTipo = $joinTablas->whereNotNull($tabla1.'.'.$division1)->whereNotNull($tabla2.'.'.$division2)->where($tabla1.'.'.$division1,$tipo1->$division1)->where($tabla2.'.'.$division2,$tipo2->$division2)->count();
        if($numTipo > 0 )
          $datosGrafica[$tipo1->$division1.' y '.$tipo2->$division2] = $numTipo;
      }
    }

    return $datosGrafica;
   }

   //Obtenemos los valores de la request que no sean "Ninguna"
   private function obtenerValores($opciones)
   {
    $valores = array();
    for($i = 0; $i < count($opciones); $i++){
      if($opciones[$i] != "Ninguna"){
        array_push($valores, $opciones[$i]);
      }
    }

    return $valores;
   }

   //Obtenemos los valores de la request que no sean "Ninguna"
   private function obtenerEdad($request)
   {
    foreach($request->edadIntervalo as $edad){
      if($edad != null){
        return $edad;
      }
    }
   }

   //Obtenemos los datos para dibujar la grafica cuando un campo es la edad del paciente y otro un campo nominal
   private function calcularIntervalosEdadDosTipos($tabla1, $tabla2, $request, $opcion)
   {
    $fechaActual = date('Y-m-d');
    $datosGrafica = array();
    $divisiones = $this->calcularTipos($tabla2, $opcion);
    $opcion = $this->calcularNuevaDivision($opcion);
    $intervaloEdad = $this->obtenerEdad($request);
    foreach ($divisiones as $division) {
      $valorAnterior = null;
      for($i = $intervaloEdad; $i < 100; $i += $intervaloEdad){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
          if($valorAnterior == null){
            $numTipo = $joinTablas->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->whereNotNull($tabla2.'.'.$opcion)->where($tabla2.'.'.$opcion,$division->$opcion)->count();
            if($numTipo != 0){
              $datosGrafica["Menores de ".$i.' y '.$division->$opcion] = $numTipo;
            }
          }else{
            $numTipo = $joinTablas->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->whereNotNull($tabla2.'.'.$opcion)->where($tabla2.'.'.$opcion,$division->$opcion)->count();
            if($numTipo != 0){
              $datosGrafica["Entre ".$valorAnterior."-".$i.' y '.$division->$opcion] = $numTipo;
            }
          }
          $valorAnterior = $i;
      }
      $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
      $numTipo = $joinTablas->where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->whereNotNull($tabla2.'.'.$opcion)->where($tabla2.'.'.$opcion,$division->$opcion)->count();
      if($numTipo != 0){
        $datosGrafica["Mayores de ".$valorAnterior.' y '.$division->$opcion] = $numTipo;
      }
    }

    return $datosGrafica;
  }

   //Obtenemos los datos para dibujar la grafica cuando un campo es la edad del paciente y otro un campo numerico
   private function calcularIntervalosEdadDosTiposNum($tabla1, $tabla2, $request)
   {
    $fechaActual = date('Y-m-d');
    $datosGrafica = array();
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $divisiones = Pacientes::select('id_paciente')->groupBy('id_paciente')->get();
    $numDatos = array();
    $intervaloEdad = $this->obtenerEdad($request);
    foreach ($divisiones as $division) {
      $valorAnterior = null;
      for($i = $intervaloEdad; $i < 100; $i += $intervaloEdad){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
          if($valorAnterior == null){
            if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->count() != 0){
              $numTipo = $joinTablas->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where('Pacientes.id_paciente',$division->id_paciente)->count();
              array_push($numDatos,"Menores de ".$i.' y '.$numTipo);
            }
          }else{
            if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count() != 0){
              $numTipo = $joinTablas->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->where('Pacientes.id_paciente',$division->id_paciente)->count();
              array_push($numDatos,"Entre ".$valorAnterior."-".$i.' y '.$numTipo);
            }
          }
          $valorAnterior = $i;
      }
      $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
      if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count() != 0){
        $numTipo = $joinTablas->where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->where('Pacientes.id_paciente',$division->id_paciente)->count();
        array_push($numDatos,"Mayores de ".$valorAnterior.' y '.$numTipo);
      }
    }
    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   //Obtenemos los datos para dibujar la grafica cuando un campo es la edad del paciente y otro un campo es un tratamiento
   private function calcularIntervalosEdadDosTiposNumTrat($tabla1, $tabla2, $request, $opcion)
   {
    $fechaActual = date('Y-m-d');
    $datosGrafica = array();
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $divisiones = Pacientes::select('id_paciente')->groupBy('id_paciente')->get();
    $numDatos = array();
    $intervaloEdad = $this->obtenerEdad($request);
    foreach ($divisiones as $division) {
      $valorAnterior = null;
      for($i = $intervaloEdad; $i < 100; $i += $intervaloEdad){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
          if($valorAnterior == null){
            if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->count() != 0){
              $numTipo = $joinTablas->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where('Pacientes.id_paciente',$division->id_paciente)->where('Tratamientos.tipo',$opcion)->count();
              array_push($numDatos,"Menores de ".$i.' y '.$numTipo);
            }
          }else{
            if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count() != 0){
              $numTipo = $joinTablas->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->where('Pacientes.id_paciente',$division->id_paciente)->where('Tratamientos.tipo',$opcion)->count();
              array_push($numDatos,"Entre ".$valorAnterior."-".$i.' y '.$numTipo);
            }
          }
          $valorAnterior = $i;
      }
      $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
      if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count() != 0){
        $numTipo = $joinTablas->where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->where('Pacientes.id_paciente',$division->id_paciente)->where('Tratamientos.tipo',$opcion)->count();
        array_push($numDatos,"Mayores de ".$valorAnterior.' y '.$numTipo);
      }
    }
    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   private function obtenerDatosDuracion($division)
   {
    $fechaIni = strtotime($division->fecha_inicio);
    $fechaFin = strtotime($division->fecha_fin);
    $difSegundos = $fechaFin - $fechaIni;
    $difDias = $difSegundos/86400;

    return $difDias;
   }

   //Obtenemos los datos para dibujar la grafica cuando un campo es la edad del paciente y otro la duración de un tratamiento
   private function calcularIntervalosEdadDosTiposDur($tabla1, $tabla2, $tipoTratamiento, $request)
   {
    $fechaActual = date('Y-m-d');
    $datosGrafica = array();
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $divisiones = Tratamientos::where('tipo',$tipoTratamiento)->get();
    $numDatos = array();
    $intervaloEdad = $this->obtenerEdad($request);
    foreach ($divisiones as $division) {
      $valorAnterior = null;
      for($i = $intervaloEdad; $i < 100; $i += $intervaloEdad){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
          if($valorAnterior == null){
            if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->count() != 0){
              $difDias = $this->obtenerDatosDuracion($division);
              array_push($numDatos,"Menores de ".$i.' y '.$difDias);
            }
          }else{
            if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'>=',date("Y-m-d",strtotime($fechaActual."- ".$i." year")))->where("nacimiento",'<',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count() != 0){
              $difDias = $this->obtenerDatosDuracion($division);
              array_push($numDatos,"Entre ".$valorAnterior."-".$i.' y '.$difDias);
            }
          }
          $valorAnterior = $i;
      }
      $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
      if(Pacientes::where('id_paciente',$division->id_paciente)->where("nacimiento",'<=',date("Y-m-d",strtotime($fechaActual."- ".$valorAnterior." year")))->count() != 0){
        $difDias = $this->obtenerDatosDuracion($division);
        array_push($numDatos,"Mayores de ".$valorAnterior.' y '.$difDias);
      }
    }
    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   //Obtenemos los datos para dibujar la grafica cuando un campo es nominal y otro numerico
   private function obtenerNumeroDosTipo($tabla1, $tabla2, $opcion, $tipoSelec1, $tipoSelec2, $id, $reves = false)
   {
    if($reves == true)
      $joinTablas = $this->hacerJoinTablas($tabla2,$tabla1);
    else
      $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $division1 = $this->campoASeleccionar($tabla1,$tipoSelec1);
    $division2 = $this->campoASeleccionar($tabla2,$tipoSelec2);
    if($id == 'id_enfermedad'){
      $tipos1 = $joinTablas->select($tabla1.'.id_enfermedad')->groupBy($tabla1.'.id_enfermedad')->get();
      $division1 = $tabla1.'.id_enfermedad';
    }elseif ($tabla1 == 'Enfermedades_familiar') {
      $tipos1 = $joinTablas->select('Antecedentes_familiares.id_paciente')->groupBy('Antecedentes_familiares.id_paciente')->get();
      $division1 = 'Antecedentes_familiares.id_paciente';
    }else{
      $tipos1 = $joinTablas->select($tabla1.'.id_paciente')->groupBy($tabla1.'.id_paciente')->get();
      $division1 = $tabla1.'.id_paciente';
    }
    $division2 = $this->calcularNuevaDivision($division2);
    if($opcion == 'todos' or $opcion == 'seguimiento' or $opcion == 'reevaluacion')
      $tipos2 = ('App\\Models\\'.$tabla2)::select($division2)->groupBy($division2)->get();
    elseif($tabla2 != 'Tratamientos')
      $tipos2 = ('App\\Models\\'.$tabla2)::select($division2)->groupBy($division2)->get();
    else
      $tipos2 = ('App\\Models\\'.$tabla2)::select($division2)->groupBy($division2)->where('tipo',$opcion)->get();
    $numDatos = array();
    foreach ($tipos1 as $tipo1) {
      foreach($tipos2 as $tipo2){
        if($reves == true)
          $joinTablas = $this->hacerJoinTablas($tabla2,$tabla1);
        else
          $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
        if($opcion == 'todos' or $opcion == 'seguimiento' or $opcion == 'reevaluacion'){
          if($tabla1 == 'Enfermedades_familiar')
            $numTipo = $joinTablas->whereNotNull($tabla2.'.'.$division2)->where($division1,$tipo1->$id)->where($tabla2.'.'.$division2,$tipo2->$division2)->count();
          elseif($opcion == 'todos' or $opcion == 'seguimiento' or $opcion == 'reevaluacion')
            $numTipo = $joinTablas->whereNotNull($tabla2.'.'.$division2)->where($division1,$tipo1->$id)->where($tabla2.'.'.$division2,$tipo2->$division2)->count();
          else
            $numTipo = $joinTablas->whereNotNull($tabla2.'.'.$division2)->where($division1,$tipo1->$id)->where($tabla2.'.'.$division2,$tipo2->$division2)->where('tipo',$opcion)->count();        }
        else
          $numTipo = $joinTablas->whereNotNull($tabla2.'.'.$division2)->where($division1,$tipo1->$id)->where($tabla2.'.'.$division2,$tipo2->$division2)->where('Tratamientos.tipo',$opcion)->count();
        if($tipo2->$division2 == null)
          array_push($numDatos,$numTipo.' y ninguna'); 
        else
          array_push($numDatos,$numTipo.' y '.$tipo2->$division2);
      }
    }

    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   //Obtenemos los datos para dibujar la grafica cuando los dos campos son numericos
   private function obtenerNumeroDosTipoNum($tabla1, $tabla2, $opcion ,$idPrincipal)
   {
    $ids = Pacientes::select('id_paciente')->groupBy('id_paciente')->get();
    $id1 = ('App\\Models\\'.$tabla1)::obtenerId();
    $id2 = ('App\\Models\\'.$tabla2)::obtenerId();
    $numDatos = array();
    foreach($ids as $id){
      if($opcion != 'Cirugia' and $opcion != 'Radioterapia' and $opcion != 'Quimioterapia' ){
        if($tabla1 == 'Sintomas' or $tabla1 == 'Metastasis' or $tabla1 == 'Biomarcadores' or $tabla1 == 'Pruebas_realizadas' or $tabla1 == 'Tecnicas_realizadas' or $tabla1 == 'Otros_tumores'){
          if(count(Enfermedades::where('id_paciente',$id->id_paciente)->get()) > 0 ){
            $idEnfermedad = Enfermedades::where('id_paciente',$id->id_paciente)->first()->id_enfermedad;
            $numTipo1 = ('App\\Models\\'.$tabla1)::where($tabla1.'.id_enfermedad',$idEnfermedad)->select($tabla1.'.'.$id1)->distinct()->get()->count();
          }else
            $numTipo1 = 0;
        }else
          if($tabla1 == 'Enfermedades_familiar'){
            $idsAntecedentes = Antecedentes_familiares::where('id_paciente',$id->id_paciente)->get();
            $numTipo1 = 0; 
            foreach($idsAntecedentes as $idAntecedente){
              $numActual = ('App\\Models\\'.$tabla1)::where($tabla1.'.id_antecedente_f',$idAntecedente->id_antecedente_f)->distinct()->get()->count();
              $numTipo1 = $numTipo1 + $numActual;
            }
          }else
            $numTipo1 = ('App\\Models\\'.$tabla1)::where($tabla1.'.id_paciente',$id->id_paciente)->select($tabla1.'.'.$id1)->distinct()->get()->count();
        if($tabla2 == 'Sintomas' or $tabla2 == 'Metastasis' or $tabla2 == 'Biomarcadores' or $tabla2 == 'Pruebas_realizadas' or $tabla2 == 'Tecnicas_realizadas' or $tabla2 == 'Otros_tumores'){
          if(count(Enfermedades::where('id_paciente',$id->id_paciente)->get()) > 0 ){
            $idEnfermedad = Enfermedades::where('id_paciente',$id->id_paciente)->first()->id_enfermedad;
            $numTipo2 =('App\\Models\\'.$tabla2)::where($tabla2.'.id_enfermedad',$idEnfermedad)->select($tabla2.'.'.$id2)->distinct()->get()->count();
          }else
            $numTipo2 = 0;
        }else
          if($tabla2 == 'Enfermedades_familiar'){
            $idsAntecedentes = Antecedentes_familiares::where('id_paciente',$id->id_paciente)->get();
            $numTipo2 = 0; 
            foreach($idsAntecedentes as $idAntecedente){
              $numActual = ('App\\Models\\'.$tabla2)::where($tabla2.'.id_antecedente_f',$idAntecedente->id_antecedente_f)->distinct()->get()->count();
              $numTipo2 = $numTipo2 + $numActual;
            }
          }else
            $numTipo2 = ('App\\Models\\'.$tabla2)::where($tabla2.'.id_paciente',$id->id_paciente)->select($tabla2.'.'.$id2)->distinct()->get()->count();
      }else{
        if($tabla1 == 'Sintomas' or $tabla1 == 'Metastasis' or $tabla1 == 'Biomarcadores' or $tabla1 == 'Pruebas_realizadas' or $tabla1 == 'Tecnicas_realizadas' or $tabla1 == 'Otros_tumores'){
          if(count(Enfermedades::where('id_paciente',$id->id_paciente)->get()) > 0 ){
            $idEnfermedad = Enfermedades::where('id_paciente',$id->id_paciente)->first()->id_enfermedad;
            $numTipo1 =('App\\Models\\'.$tabla1)::where($tabla1.'.id_enfermedad',$idEnfermedad)->select($tabla1.'.'.$id1)->distinct()->get()->count();
          }else
            $numTipo1 = 0;
        }
        else
          if($tabla1 == 'Enfermedades_familiar'){
            $idsAntecedentes = Antecedentes_familiares::where('id_paciente',$id->id_paciente)->get();
            $numTipo1 = 0; 
            foreach($idsAntecedentes as $idAntecedente){
              $numActual = ('App\\Models\\'.$tabla1)::where($tabla1.'.id_antecedente_f',$idAntecedente->id_antecedente_f)->distinct()->get()->count();
              $numTipo1 = $numTipo1 + $numActual;
            }
          }
          else  
            $numTipo1 =('App\\Models\\'.$tabla1)::where($tabla1.'.id_paciente',$id->id_paciente)->select($tabla1.'.'.$id1)->distinct()->get()->count();
        $numTipo2 = ('App\\Models\\'.$tabla2)::where($tabla2.'.id_paciente',$id->id_paciente)->where('Tratamientos.tipo',$opcion)->select($tabla2.'.'.$id2)->distinct()->get()->count();
      }
      array_push($numDatos,$numTipo1.' y '.$numTipo2);
    }

    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   //Obtenemos los datos para dibujar la grafica cuando un campo es númerico de tratamiento y otro de duración
   private function obtenerNumeroDosTipoDurTratamiento($tabla1, $tabla2, $tipoTratamientoDur, $tipoTratamiento, $tipoSelec1, $id)
   {
    $division2 = $this->campoASeleccionar($tabla1,$tipoSelec1);
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $tipos1 = Tratamientos::where('tipo',$tipoTratamientoDur)->get();
    $tipos2 = $joinTablas->select($tabla1.'.'.$id)->where('tipo', $tipoTratamiento)->groupBy($tabla1.'.'.$id)->get();
    $numDatos = array();
    foreach ($tipos1 as $tipo1) {
      foreach($tipos2 as $tipo2){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
        $numTipo2 = $joinTablas->where('Tratamientos.tipo',$tipoTratamientoDur)->where('Tratamientos.id_paciente',$tipo1->id_paciente)->where($tabla1.'.'.$id,$tipo2->$id)->count();
        $fechaIni = strtotime($tipo1->fecha_inicio);
        $fechaFin = strtotime($tipo1->fecha_fin);
        $difSegundos = $fechaFin - $fechaIni;
        $difDias = $difSegundos/86400;
        array_push($numDatos,$numTipo2.' y '.$difDias);
      }
    }

    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   //Obtenemos los datos para dibujar la grafica cuando un campo es númerico y otro de duración
   private function obtenerNumeroDosTipoDur($tabla1, $tabla2, $tipoTratamiento, $tipoSelec1, $id)
   {
    $division2 = $this->campoASeleccionar($tabla1,$tipoSelec1);
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $tipos1 = Tratamientos::where('tipo',$tipoTratamiento)->get();
    if($tabla1 == 'Enfermedades_familiar')
      $tipos2 = $joinTablas->select('Antecedentes_familiares.id_paciente')->groupBy('Antecedentes_familiares.id_paciente')->get();
    else
      $tipos2 = $joinTablas->select($tabla1.'.'.$id)->groupBy($tabla1.'.'.$id)->get();
    $numDatos = array();
    foreach ($tipos1 as $tipo1) {
      foreach($tipos2 as $tipo2){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
        if($tabla1 == 'Enfermedades_familiar')
          $numTipo2 = $joinTablas->where('Tratamientos.tipo',$tipoTratamiento)->where('Tratamientos.id_paciente',$tipo1->id_paciente)->where('Antecedentes_familiares.id_paciente',$tipo2->id_paciente)->count();
        else
          $numTipo2 = $joinTablas->where('Tratamientos.tipo',$tipoTratamiento)->where('Tratamientos.id_paciente',$tipo1->id_paciente)->where($tabla1.'.'.$id,$tipo2->$id)->count();
        $fechaIni = strtotime($tipo1->fecha_inicio);
        $fechaFin = strtotime($tipo1->fecha_fin);
        $difSegundos = $fechaFin - $fechaIni;
        $difDias = $difSegundos/86400;
        array_push($numDatos,$numTipo2.' y '.$difDias);
      }
    }

    $datosGrafica = array_count_values($numDatos);

    return $datosGrafica;
   }

   private function obtenerDuracionYNominal($tabla1, $tabla2, $tipoTratamiento, $tipoSelec1, $tipoSelec2, $id)
   {
    $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
    $tipos1 = Tratamientos::where('tipo',$tipoTratamiento)->get();
    $division2 = $this->campoASeleccionar($tabla1,$tipoSelec1);
    $division2 = $this->calcularNuevaDivision($division2);
    $tipos2 = ('App\\Models\\'.$tabla1)::select($division2)->groupBy($division2)->get();
    $numDatos = array();
    foreach ($tipos1 as $tipo1) {
      foreach($tipos2 as $tipo2){
        $joinTablas = $this->hacerJoinTablas($tabla1,$tabla2);
        $fechaIni = strtotime($tipo1->fecha_inicio);
        $fechaFin = strtotime($tipo1->fecha_fin);
        $difSegundos = $fechaFin - $fechaIni;
        $difDias = $difSegundos/86400;
        $posibles = $joinTablas->where('Tratamientos.id_tratamiento',$tipo1->id_tratamiento)->where($tabla1.'.'.$division2,$tipo2->$division2)->get();
        $numTipo = 0;
        foreach($posibles as $posible){
          $fechaIni = strtotime($posible->fecha_inicio);
          $fechaFin = strtotime($posible->fecha_fin);
          $difSegundos = $fechaFin - $fechaIni;
          $difDias2 = $difSegundos/86400;
          if($difDias == $difDias2)
            $numTipo += 1;
        }
        if($tipo2->$division2 == null)
          $tipo = 'Ninguno';
        else
          $tipo = $tipo2->$division2;
        if($numTipo>0){
          if(in_array($tipo.' y '.$difDias,array_keys($numDatos)))
            $numDatos[$tipo.' y '.$difDias] = $numDatos[$tipo.' y '.$difDias] + 1;
          else
            $numDatos[$tipo.' y '.$difDias] = $numTipo;
        }
      }
    }
    return $numDatos;
   }

   private function datosGraficaNacimiento($opcion2, $tabla1, $tabla2, $request)
   {
    if($opcion2 == 'num_quimioterapia')
      $datosGrafica = $this->calcularIntervalosEdadDosTiposNumTrat($tabla1, $tabla2, $request, 'Quimioterapia');
    elseif($opcion2 == 'num_radioterapia')
      $datosGrafica = $this->calcularIntervalosEdadDosTiposNumTrat($tabla1, $tabla2, $request, 'Radioterapia');
    elseif($opcion2 == 'num_cirugia')
      $datosGrafica =$this->calcularIntervalosEdadDosTiposNumTrat($tabla1, $tabla2, $request, 'Cirugia');
    elseif(preg_match("/^num_/", $opcion2))
      $datosGrafica = $this->calcularIntervalosEdadDosTiposNum($tabla1, $tabla2, $request);
    elseif($opcion2 == 'duracion_quimioterapia')
      $datosGrafica = $this->calcularIntervalosEdadDosTiposDur($tabla1, $tabla2, 'Quimioterapia', $request);
    elseif($opcion2 == 'duracion_radioterapia')
      $datosGrafica = $this->calcularIntervalosEdadDosTiposDur($tabla1, $tabla2, 'Radioterapia',$request);
    else
      $datosGrafica = $this->calcularIntervalosEdadDosTipos($tabla1, $tabla2, $request, $opcion2,'id_paciente');

    return $datosGrafica;
   }

   private function datosGraficaAntecedentes($tabla1, $tabla2 ,$opcion1, $opcion2)
   {
    if($tabla2 == 'Tratamientos'){
      if($opcion2 == 'intencion_quimioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Quimioterapia',$opcion1, $opcion2,'id_paciente');
      elseif($opcion2 == 'tipo_radioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Radioterapia',$opcion1, $opcion2,'id_paciente');
      elseif($opcion2 == 'tipo_cirugia')
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Cirugia',$opcion1, $opcion2,'id_paciente');
      elseif($opcion2 == 'num_tratamientos')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'todos','id_paciente');
      elseif($opcion2 == 'num_cirugia')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'Cirugia','id_paciente');
      elseif($opcion2 == 'num_radioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'Radioterapia','id_paciente');
      elseif($opcion2 == 'num_quimioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'Quimioterapia','id_paciente');
      elseif($opcion2 == 'duracion_quimioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoDur($tabla1, $tabla2,'Quimioterapia', $opcion1,'id_paciente');
      elseif($opcion2 == 'duracion_radioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoDur($tabla1, $tabla2,'Radioterapia', $opcion1,'id_paciente');
      else
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_paciente'); 
    }elseif(preg_match("/^num_/", $opcion2))
      $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'todos','id_paciente');
    else
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_paciente');

    return $datosGrafica;
   }


   private function datosGraficaTratamientos($tabla1, $tabla2 , $tipo, $opcion1, $opcion2)
   {
    if(preg_match("/^num_/", $opcion2)){
      $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla2, $tabla1, $tipo ,'id_paciente'); 
      $datosGrafica = $this->arrayClaveInvertida($datosGrafica);
    }
    elseif($opcion2 == 'duracion_quimioterapia')
      $datosGrafica = $this->obtenerNumeroDosTipoDurTratamiento($tabla1, $tabla2, 'Quimioterapia', $tipo, $opcion1, 'id_paciente'); 
    elseif($opcion2 == 'duracion_radioterapia')
      $datosGrafica = $this->obtenerNumeroDosTipoDurTratamiento($tabla1, $tabla2, 'Radioterapia', $tipo, $opcion1, 'id_paciente'); 
    else
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, $tipo, $opcion1, $opcion2, 'id_paciente');
    
    return $datosGrafica;
   }

   private function datosGraficaEnfermedad($tabla1, $tabla2 ,$opcion1, $opcion2)
   {
    if($tabla2 == 'Tratamientos'){
      if($opcion2 == 'intencion_quimioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Quimioterapia',$opcion1, $opcion2,'id_enfermedad');
      elseif($opcion2 == 'tipo_radioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Radioterapia',$opcion1, $opcion2,'id_enfermedad');
      elseif($opcion2 == 'tipo_cirugia')
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Cirugia',$opcion1, $opcion2,'id_enfermedad');
      elseif($opcion2 == 'num_tratamientos')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'todos','id_enfermedad');
      elseif($opcion2 == 'duracion_quimioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoDur($tabla1, $tabla2, 'Quimioterapia', $opcion1, 'id_enfermedad'); 
      elseif($opcion2 == 'duracion_radioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoDur($tabla1, $tabla2, 'Radioterapia', $opcion1, 'id_enfermedad' ); 
      elseif($opcion2 == 'num_cirugia')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'Cirugia','id_enfermedad');
      elseif($opcion2 == 'num_radioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'Radioterapia','id_enfermedad');
      elseif($opcion2 == 'num_quimioterapia')
        $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'Quimioterapia','id_enfermedad');
      else
        $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_enfermedad'); 
    }elseif(preg_match("/^num_/", $opcion2))
      $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'todos','id_enfermedad');
    else
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_enfermedad'); 
    return $datosGrafica;
   }

   private function datosGraficaReevaYSegui($tabla1, $tabla2 ,$opcion1, $opcion2){
    if($opcion2 == 'num_reevaluacion' or $opcion2 == 'num_seguimiento')
      $datosGrafica = $this->obtenerNumeroDosTipoNum($tabla1, $tabla2, 'todos','id_paciente');
    else
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_paciente'); 

    return $datosGrafica;
   }


   private function arrayClaveInvertida($array)
   {
    $datosFinales = array();

    foreach(array_keys($array) as $clave){
      $dato1 = explode(" y ", $clave)[0];
      $dato2 = explode(" y ", $clave)[1];
      $datosFinales[$dato2.' y '.$dato1] = $array[$clave];
    }

    return $datosFinales;
   }

   private function datosGraficaDuracion($tabla1, $tabla2, $tipo, $opcion1, $opcion2)
   {
    if($opcion1 == 'num_quimioterapia')
      $datosGrafica = $this->obtenerNumeroDosTipoDurTratamiento($tabla1, $tabla2, $tipo, 'Quimioterapia', $opcion1, 'id_paciente');  
    elseif($opcion1 == 'num_radioterapia')
      $datosGrafica = $this->obtenerNumeroDosTipoDurTratamiento($tabla1, $tabla2, $tipo, 'Radioterapia', $opcion1, 'id_paciente');  
    elseif($opcion1 == 'num_cirugia')
      $datosGrafica = $this->obtenerNumeroDosTipoDurTratamiento($tabla1, $tabla2, $tipo, 'Cirugia', $opcion1, 'id_paciente'); 
    elseif(preg_match("/^num_/", $opcion1))
      $datosGrafica = $this->obtenerNumeroDosTipoDur($tabla1, $tabla2, $tipo, $opcion1, 'id_paciente');
    elseif($opcion1 == 'duracion_radioterapia' or $opcion1 == 'duracion_quimioterapia')
      throw new \Exception('errorDosDuraciones');
    else
      $datosGrafica = $this->obtenerDuracionYNominal($tabla1, $tabla2, $tipo, $opcion1, $opcion2,'id_paciente');

    return $this->arrayClaveInvertida($datosGrafica);
   }

   public function datosGraficaNominal($tabla1, $tabla2,$opcion1, $opcion2)
   {
    if($tabla2 == 'Enfermedades' or $tabla2 == 'Metastasis' or $tabla2 == 'Sintomas' or $tabla2 == 'Biomarcadores' or $tabla2 == 'Pruebas_realizadas' or $tabla2 == 'Tecnicas_realizadas' or $tabla2 == 'Otros_tumores')
      $id = 'id_enfermedad';
    else
      $id = 'id_paciente';
    if($opcion1 == 'num_quimioterapia')
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Quimioterapia',$opcion1, $opcion2, 'id_paciente' ,true);
    elseif($opcion1 == 'num_radioterapia')
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Radioterapia',$opcion1, $opcion2,'id_paciente',true);
    elseif($opcion1 == 'num_cirugia')
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'Cirugia',$opcion1, $opcion2, 'id_paciente',true);
    elseif($opcion1 == 'num_sintoma' or $opcion1 == 'num_metastasis' or $opcion1 == 'num_biomarcador' or $opcion1 == 'num_prueba' or $opcion1 == 'num_tecnica' or $opcion1 == 'num_tumor')
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_enfermedad',true);
    elseif(preg_match("/^num_/", $opcion1))
      $datosGrafica = $this->obtenerNumeroDosTipo($tabla1, $tabla2, 'todos',$opcion1, $opcion2,'id_paciente',true);
    elseif($opcion1 == 'duracion_quimioterapia')
      return $this->obtenerDuracionYNominal($tabla2, $tabla1, 'Quimioterapia', $opcion2, $opcion1,'id_paciente');
    elseif($opcion1 == 'duracion_radioterapia')
      return $this->obtenerDuracionYNominal($tabla2, $tabla1, 'Radioterapia', $opcion2, $opcion1,'id_paciente');
    else
      return $this->obtenerDatosDosOpciones($tabla2,$tabla1,$opcion2,$opcion1);

    return $this->arrayClaveInvertida($datosGrafica);
  }

   private function dosOpciones($request)
   {
    $opciones = $request->opciones;
    $tabla1 = $this->obtenerTabla($opciones);
    $seleccion1 = $opciones[$this->obtenerValor($opciones)];
    $opcion1 = $this->campoASeleccionar($tabla1, $seleccion1);
    $opciones[$this->obtenerValor($opciones)] = "Ninguna";
    $tabla2 = $this->obtenerTabla($opciones);
    $seleccion2 = $opciones[$this->obtenerValor($opciones)];
    $opcion2 = $this->campoASeleccionar($tabla2, $seleccion2);
    $datosGrafica = array();
    if($seleccion1 == $seleccion2)
      throw new \Exception('mismoDatoSeleccionado');
    //Cuando el primer campo es nacimiento
    if($opcion1 == "nacimiento")
      return $this->datosGraficaNacimiento($opcion2, $tabla1, $tabla2, $request);
    //Cuando el primer campo es un antecedente numerico
    elseif($opcion1 == 'num_antecedente_oncologico' or $opcion1 == 'num_antecedente_medico' or $opcion1 == 'num_familiar_antecedente' or $opcion1 == 'num_antecedente_familiar')
      return $this->datosGraficaAntecedentes($tabla1, $tabla2 ,$opcion1, $opcion2);
    elseif($opcion1 == 'num_quimioterapia')
      return $this->datosGraficaTratamientos($tabla1, $tabla2 ,'Quimioterapia', $opcion1, $opcion2,);
    elseif($opcion1 == 'num_radioterapia')
      return $this->datosGraficaTratamientos($tabla1, $tabla2 ,'Radioterapia', $opcion1 ,$opcion2);
    elseif($opcion1 == 'num_cirugia')
      return $this->datosGraficaTratamientos($tabla1, $tabla2 ,'Cirugia', $opcion1, $opcion2);
    elseif($opcion1 == 'num_tratamientos')
      return $this->datosGraficaTratamientos($tabla1, $tabla2 ,'todos', $opcion1, $opcion2);
    elseif($opcion1 == 'num_reevaluacion' or $opcion1 == 'num_seguimiento')
      return $this->datosGraficaReevaYSegui($tabla1, $tabla2 ,$opcion1, $opcion2);
    elseif(preg_match("/^num_/", $opcion1))
      return $this->datosGraficaEnfermedad($tabla1, $tabla2 ,$opcion1, $opcion2);
    elseif ($opcion1 == 'duracion_quimioterapia')
      return $this->datosGraficaDuracion($tabla2, $tabla1, 'Quimioterapia', $opcion2 ,$opcion1);
    elseif ($opcion1 == 'duracion_radioterapia') 
      return $this->datosGraficaDuracion($tabla2, $tabla1, 'Radioterapia', $opcion2 ,$opcion1);
    else{
      return $this->datosGraficaNominal($tabla2, $tabla1,$opcion2, $opcion1);
    }

    return $datosGrafica;
   }

    /******************************************************************
    *                                                                 *
    * Graficas dos datos                                              *
    *                                                                 *
    *******************************************************************/


    //PRUEBAS
    private function joinTablasPaciente($tabla2)
    {
      if($tabla2 == 'Enfermedades')
        $joinTablas = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente');
      elseif($tabla2 == 'Otros_tumores' || $tabla2 == 'Tecnicas_realizadas' || $tabla2 == 'Pruebas_realizadas' || $tabla2 == 'Biomarcadores' || $tabla2 == 'Sintomas' || $tabla2 == 'Metastasis')
        $joinTablas = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente')->join($tabla2, 'Enfermedades.id_enfermedad', '=', $tabla2.'.id_enfermedad');
      elseif($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Pacientes')->join('tratamientos', 'Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->join('intenciones', 'tratamientos.id_tratamiento', '=', 'intenciones.id_tratamiento');
      elseif($tabla2 == 'Farmacos')
        $joinTablas = DB::table('Pacientes')->join('tratamientos', 'Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->join('intenciones', 'tratamientos.id_tratamiento', '=', 'intenciones.id_tratamiento')->join('farmacos','intenciones.id_intencion','=','farmacos.id_intencion');
      elseif($tabla2 == 'Enfermedades_familiar')
        $joinTablas = DB::table('Pacientes')->join('Antecedentes_familiares', 'Pacientes.id_paciente', '=', 'Antecedentes_familiares.id_paciente')->join('Enfermedades_familiar','Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f');
      else
        $joinTablas = DB::table('Pacientes')->join($tabla2, 'Pacientes.id_paciente', '=', $tabla2.'.id_paciente');

      return $joinTablas;
    }

    private function joinTablasEnfermedad($tabla1, $tabla2)
    {
      if($tabla2 == 'Enfermedades' || $tabla2 == 'Otros_tumores' || $tabla2 == 'Tecnicas_realizadas' || $tabla2 == 'Pruebas_realizadas' || $tabla2 == 'Biomarcadores' || $tabla2 == 'Sintomas' || $tabla2 == 'Metastasis')
        $joinTablas = DB::table($tabla1)->join($tabla2, $tabla1.'.id_enfermedad', '=', $tabla2.'.id_enfermedad');
      elseif($tabla2 == 'Seguimientos' || $tabla2 == 'Antecedentes_medicos' || $tabla2 == 'Tratamientos' || $tabla2 == 'Antecedentes_oncologicos' || $tabla2 == 'Antecedentes_familiares' || $tabla2 == 'Reevaluaciones'){
        if($tabla1 == 'Enfermedades')
          $joinTablas = DB::table('Enfermedades')->join($tabla2, 'Enfermedades.id_paciente','=',$tabla2.'.id_paciente');
        else
          $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join($tabla2, 'Enfermedades.id_paciente','=',$tabla2.'.id_paciente');
      }elseif($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento');
      elseif($tabla2 == 'Farmacos'){
        if($tabla1 == 'Enfermedades')
          $joinTablas = DB::table('Enfermedades')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion');
        else
          $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Tratamientos','Enfermedades.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento','=','Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion');
      }else
        $joinTablas = DB::table('Enfermedades')->join($tabla1, 'Enfermedades.id_enfermedad', '=', $tabla1.'.id_enfermedad')->join('Antecedentes_familiares','Enfermedades.id_paciente','=','Antecedentes_familiares.id_paciente')->join('Enfermedades_familiar', 'Antecedentes_familiares.id_antecedente_f','=','Enfermedades_familiar.id_antecedente_f');

      return $joinTablas;
    }

    private function joinTablasEnfermedades_familiar($tabla2)
    {
      if($tabla2 == 'Intenciones')
        $joinTablas = DB::table('Enfermedades_familiar')->join('Antecedentes_familiares', 'Enfermedades_familiar.id_antecedente_f', '=', 'Antecedentes_familiares.id_antecedente_f')->join('Tratamientos','Antecedentes_familiares.id_paciente','=','Tratamientos.id_paciente')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento');   
      else
        $joinTablas = DB::table('Enfermedades_familiar')->join('Antecedentes_familiares', 'Enfermedades_familiar.id_antecedente_f', '=', 'Antecedentes_familiares.id_antecedente_f')->join($tabla2,'Antecedentes_familiares.id_paciente','=',$tabla2.'.id_paciente'); 

      return $joinTablas;
    }

    private function joinTablasIntenciones($tabla2)
    {
      if($tabla2 == 'Tratamientos')
        $joinTablas =DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento');
      else
        $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join($tabla2,'Tratamientos.id_paciente','=',$tabla2.'.id_paciente');

      return $joinTablas;
    }

    private function joinTablasRestantes($tabla1, $tabla2)
    {
      if($tabla2 == 'Intenciones'){
        if($tabla1 == 'Tratamientos')
          $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento');
        else
          $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join($tabla1, 'Tratamientos.id_paciente', '=', $tabla1.'.id_paciente');
      }elseif($tabla2 == 'Farmacos')
        $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion')->join($tabla1, 'Tratamientos.id_paciente', '=', $tabla1.'.id_paciente');
      else
       $joinTablas = DB::table($tabla1)->join($tabla2, $tabla1.'.id_paciente', '=', $tabla2.'.id_paciente');

     return $joinTablas;
    }

   //Realizamos los join entre tablas según sean necesarios
   private function hacerJoinTablasTres($tabla1,$tabla2,$primerJoin)
   {
    if($tabla1 == $tabla2)
      $joinTablas = $primerJoin;
    elseif($tabla1 == 'Pacientes')
      $joinTablas = $this->joinTablasPaciente($tabla2);
    elseif($tabla1 == 'Enfermedades' || $tabla1 == 'Otros_tumores' || $tabla1 == 'Tecnicas_realizadas' || $tabla1 == 'Pruebas_realizadas' || $tabla1 == 'Biomarcadores' || $tabla1 == 'Sintomas' || $tabla1 == 'Metastasis'){
      $joinTablas = $this->joinTablasEnfermedad($tabla1, $tabla2);
   }elseif($tabla1 == 'Enfermedades_familiar'){
      $joinTablas = $this->joinTablasEnfermedades_familiar($tabla2);
   }elseif($tabla1 == 'Intenciones'){
      $joinTablas = $this->joinTablasIntenciones($tabla2);
   }elseif($tabla1 == 'Farmacos'){
    $joinTablas = DB::table('Tratamientos')->join('Intenciones', 'Tratamientos.id_tratamiento', '=', 'Intenciones.id_tratamiento')->join('Farmacos','Intenciones.id_intencion','=','Farmacos.id_intencion')->join($tabla2,'Tratamientos.id_paciente','=',$tabla2.'.id_paciente');
   }else{
    $joinTablas = $this->joinTablasRestantes($tabla1, $tabla2);
   }

    return $joinTablas; 
   }










   private function tresOpciones($request)
   {
    $opciones = $request->opciones;
    $tabla1 = $this->obtenerTabla($opciones);
    $seleccion1 = $opciones[$this->obtenerValor($opciones)];
    $opcion1 = $this->campoASeleccionar($tabla1, $seleccion1);
    $opciones[$this->obtenerValor($opciones)] = "Ninguna";
    $tabla2 = $this->obtenerTabla($opciones);
    $seleccion2 = $opciones[$this->obtenerValor($opciones)];
    $opcion2 = $this->campoASeleccionar($tabla2, $seleccion2);
    $opciones[$this->obtenerValor($opciones)] = "Ninguna";
    $tabla3 = $this->obtenerTabla($opciones);
    $seleccion3 = $opciones[$this->obtenerValor($opciones)];
    $opcion3 = $this->campoASeleccionar($tabla3, $seleccion3);   
    $join1 = $this->hacerJoinTablas($tabla1,$tabla2);
    $join2 = $this->hacerJoinTablas($tabla2,$tabla3);

    dump($joinFinal);
    dd();
  }

    /******************************************************************
    *                                                                 *
    * Imprimir gráficas                                               *
    *                                                                 *
    *******************************************************************/
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
            $datosGrafica = $this->tresOpciones($request);
            break;
          case 4:
            return redirect()->route('vergraficas')->with('errorMax','No puedes seleccionar más de 3 divisiones');
            break;
          default:
            return redirect()->route('vergraficas')->with('errorMax','No puedes seleccionar más de 3 divisiones');
            break;
      }
      $tipos = $this->obtenerValores($request->opciones);
      return view('mostrargrafica',['datosGrafica' => $datosGrafica, 'tipos' => $tipos, 'tipoGrafica' =>  $request->tipo_grafica]);
      /*
    }catch (\Exception $e){
      if($e->getMessage() == 'errorDosDuraciones')
        return redirect()->route('vergraficas')->with('errorNoExisteCampo','No se pueden seleccionar las dos duraciones');
      elseif($e->getMessage() == 'mismoDatoSeleccionado')
        return redirect()->route('vergraficas')->with('errorNoExisteCampo','No se pueden seleccionar el mismo campo');        
      $opcion = $request->opciones[$this->obtenerValor($request->opciones)];
      return redirect()->route('vergraficas')->with('errorNoExisteCampo','No hay ningun dato guardado sobre los datos introducidos');
    }
    */
   }
}
