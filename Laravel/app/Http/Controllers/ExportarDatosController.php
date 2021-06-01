<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AllExport;


class ExportarDatosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verExportarDatos()
    {
    	return view('exportardatos');
    }

    public function exportarDatos(Request $request)
    { 	
    	return Excel::download(new AllExport, $request->nombre_excel.'.xlsx');
    }

}