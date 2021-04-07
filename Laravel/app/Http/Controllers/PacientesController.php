<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;


class PacientesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function verPaciente()
    {
        $todosPacientes = Pacientes::all();
        return view('pacientes',['pacientes' => $todosPacientes]);
    }

    public function verAnadirNuevoPaciente()
    {
        $todosPacientes = Pacientes::all();
        return view('pacientes',['pacientes' => $todosPacientes]);
    }

    public function anadirNuevoPaciente()
    {
        $todosPacientes = Pacientes::all();
        return view('pacientes',['pacientes' => $todosPacientes]);
    }


}
