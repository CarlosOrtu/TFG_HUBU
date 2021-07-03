<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Biomarcadores;
use App\Models\Tratamientos;
use Illuminate\Support\Facades\DB;
use App\Utilidades\Encriptacion;

class PacientesFiltradosController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();
    }

    public function verFiltrado(){
        return view('filtrarpacientes');
    }

    private function obtenerListaOpciones($request){
        $contador = 0;
        $variables = array();
        foreach (array_keys($request->all()) as $opcion) {
            if($contador != 0){
                array_push($variables, $opcion);
            }
            $contador += 1;
        }
        return $variables;
    }

    private function realizarJoin(){
        $pacientes = DB::table('Pacientes')->join('enfermedades', 'Pacientes.id_paciente', '=', 'enfermedades.id_paciente')->leftJoin('tratamientos','Pacientes.id_paciente', '=', 'tratamientos.id_paciente')->leftJoin('biomarcadores','enfermedades.id_enfermedad','=','biomarcadores.id_enfermedad');
        return $pacientes->select('pacientes.id_paciente','biomarcadores.nombre','tratamientos.tipo');
    }

    public function obtenerPacientesFiltrados($listaOpciones){
        $opciones = $listaOpciones;
        $pacientes = array();
        foreach($opciones as $opcion){
            $joinTablas = $this->realizarJoin();
            if($opcion == "Radioterapia" or $opcion == "Quimioterapia" or $opcion == "Cirugia"){
                $idPacientesCoinc = $joinTablas->select('pacientes.id_paciente')->distinct()->where('tratamientos.tipo',$opcion)->get();
                foreach ($idPacientesCoinc as $idPaciente) {
                    array_push($pacientes, $idPaciente->id_paciente);
                }
            }else{
                $idPacientesCoinc = $joinTablas->select('pacientes.id_paciente')->distinct()->where('biomarcadores.nombre',$opcion)->get();
                foreach ($idPacientesCoinc as $idPaciente) {
                    array_push($pacientes, $idPaciente->id_paciente);
                }
            }
        }
        $contador = 0;
        $pacientesFiltro = array();
        $vecesPacientes = array_count_values($pacientes);
        foreach ($vecesPacientes as $numVeces) {
            if($numVeces == count($opciones)){
                array_push($pacientesFiltro, Pacientes::find(array_keys($vecesPacientes)[$contador]));
            }
            $contador += 1;
        }

        return $pacientesFiltro;
    }

    public function verPacientesFiltrados(Request $request){
        if(count($request->all()) <= 1){
            return back()->with('filtroVacio','Selecciona alguna opción de filtrado');
        }
        $opciones = $this->obtenerListaOpciones($request);
        $pacientesFiltro = $this->obtenerPacientesFiltrados($opciones);
        $opciones = implode("-", $opciones);
        if(count($pacientesFiltro) == 0){
            return back()->with('errorFiltro','No existen pacientes con ese filtro');
        }
        return view('pacientes',['pacientes' => $pacientesFiltro, 'encriptacion' => $this->encriptacion, 'opciones' => $opciones]);    
    }

    public function verPacientesFiltradosPercentiles(Request $request){
        if(count($request->all()) <= 1){
            return back()->with('filtroVacio','Selecciona alguna opción de filtrado');
        }
        $opciones = $this->obtenerListaOpciones($request);
        $opciones = implode("-", $opciones);
        return view('percentiles',['opciones' => $opciones]);    
    }

    public function verPacientesFiltradosGraficas(Request $request){
        if(count($request->all()) <= 1){
            return back()->with('filtroVacio','Selecciona alguna opción de filtrado');
        }
        $opciones = $this->obtenerListaOpciones($request);
        $opciones = implode("-", $opciones);
        return view('graficas',['opciones' => $opciones]);    
    }

    public function verPacientesFiltradosKaplan(Request $request){
        if(count($request->all()) <= 1){
            return back()->with('filtroVacio','Selecciona alguna opción de filtrado');
        }
        $opciones = $this->obtenerListaOpciones($request);
        $opciones = implode("-", $opciones);
        return view('kaplanmeier',['opciones' => $opciones]);    
    }

}
