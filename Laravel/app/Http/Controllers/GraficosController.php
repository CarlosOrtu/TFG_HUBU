<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Enfermedades;


class GraficosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

   public function verGraficas()
   {
   		$pacientes = Pacientes::all();
        return view('graficas',['pacientes' => $pacientes]);
   }
}
