<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
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

    public function crearGraficaKaplanMeier(Request $request)
    { 	
    	system('py C:\wamp64\www\TFG_HUBU\KaplanMeier\dibujarGrafica.py '.$request->separacion);
    	system('py C:\wamp64\www\TFG_HUBU\KaplanMeier\dibujarGrafica.py general');

    	return view('vergraficakaplan',['division' => $request->separacion]);
    }
}