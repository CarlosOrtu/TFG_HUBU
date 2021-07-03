<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use App\Models\Pacientes;
use Illuminate\Support\Facades\Validator;


class KaplanMeierController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verKaplanMeier()
    {
    	return view('kaplanmeier');
    }

    public function crearGraficaKaplanMeier(Request $request, $opciones = null)
    { 	
        if($opciones != null){
            $opcionesArray = explode("-", $opciones);
            $pacientesFil = app(PacientesFiltradosController::class)->obtenerPacientesFiltrados($opcionesArray);
        }else{
            $pacientesFil = Pacientes::all();
        }
        $idsFil = array();
        foreach ($pacientesFil as $paciente) {
            array_push($idsFil, $paciente->id_paciente);
        }
        $idsString = implode("-", $idsFil);
    	system('py C:\wamp64\www\TFG_HUBU\KaplanMeier\dibujarGrafica.py '.$request->separacion.' '.$idsString);
    	system('py C:\wamp64\www\TFG_HUBU\KaplanMeier\dibujarGrafica.py general '.$idsString);

    	return view('vergraficakaplan',['opciones' => $opciones, 'division' => $request->separacion]);
    }
}