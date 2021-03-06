<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\Pacientes;
use App\Models\Seguimientos;
use App\Utilidades\Encriptacion;

class SeguimientosController extends Controller
{    
	private $encriptacion;

	public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();
    }

    public function verSeguimientoSinModificar($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){      
	    	$nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
	    	return view('verseguimientos',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
	    }

	    return view('verseguimientos',['paciente' => $paciente]);
    }

    public function actualizarfechaModificacionPaciente($paciente)
    {
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save();
    }

    public function verSeguimientoNuevo($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){      
	    	$nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
	    	return view('seguimientosnuevos',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
	    }

	    return view('seguimientosnuevos',['paciente' => $paciente]);
	    
    }

    public function validarSeguimiento($request)
    {
    	//Calculamos la fecha de mañana
    	$seg = time();
		$manana = strtotime("+1 day", $seg);
		$manana = date("Y-m-d", $manana);
		if($request->estado == "Fallecido" and $request->motivo == "Otro"){
			$validator = Validator::make($request->all(), [
	            'fecha' => 'required|date|before:'.$manana,
	            'fecha_fallecimiento' => 'required|date|before:'.$manana,
	        ],
	        [
	        'required' => 'El campo :attribute no puede estar vacio',
	        'before' => 'Introduce una fecha valida',
	        'date' => 'Introduce una fecha valida',
	        ]);	

	        return $validator;
		}

        $validator = Validator::make($request->all(), [
            'fecha' => 'required|date|before:'.$manana,
        ],
        [
        'required' => 'El campo :attribute no puede estar vacio',
        'before' => 'Introduce una fecha valida',
        'date' => 'Introduce una fecha valida',
        ]);
   
        return $validator;
    }

    public function crearSeguimiento(Request $request, $idPaciente)
    {
    	try{
		    $validator = $this->validarSeguimiento($request);
	    	if($validator->fails())
	        	return back()->withErrors($validator->errors())->withInput();
	        
    		$paciente = Pacientes::find($idPaciente);

	    	$nuevoSeguimiento = new Seguimientos();
	    	$nuevoSeguimiento->id_paciente = $idPaciente;
	      	$nuevoSeguimiento->fecha = $request->fecha;
	    	$nuevoSeguimiento->estado	 = $request->estado;
	    	if($request->estado == "Fallecido"){
		    	$nuevoSeguimiento->fallecido_motivo	 = $request->motivo;
		    	$nuevoSeguimiento->fecha_fallecimiento = $request->fecha_fallecimiento;
		    }
	    	$nuevoSeguimiento->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	    	return redirect()->route('seguimientosnuevos',$idPaciente)->with('success','Seguimiento creado correctamente');
	    }catch(QueryException $e){
            return redirect()->route('seguimientosnuevos',$idPaciente)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function verSeguimiento($idPaciente, $num_seguimiento)
    {
    	$paciente = Pacientes::find($idPaciente);
    	$seguimientos = $paciente->Seguimientos;
    	$seguimiento = $seguimientos[$num_seguimiento];
        if(env('APP_ENV') == 'production'){      
	    	$nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
	    	return view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => $num_seguimiento, 'nombre' => $nhcDesencriptado]);
	    }

	    return view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => $num_seguimiento]);
    }

    public function modificarSeguimiento(Request $request, $idPaciente, $num_seguimiento)
    {
    	try{
	    	$validator = $this->validarSeguimiento($request);
	    	if($validator->fails())
	        	return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($idPaciente);

	    	$seguimiento = $paciente->Seguimientos[$num_seguimiento];
	    	$seguimiento->id_paciente = $idPaciente;
	      	$seguimiento->fecha = $request->fecha;
	    	$seguimiento->estado	 = $request->estado;
	    	if($request->estado == "Fallecido"){
	    		if($request->motivo == "Otro")
					$seguimiento->fallecido_motivo = "Otro: ".$request->motivo_especificar;
		    	else
		    		$seguimiento->fallecido_motivo	 = $request->motivo;
		    	$seguimiento->fecha_fallecimiento = $request->fecha_fallecimiento;
		    }else{
		    	$seguimiento->fallecido_motivo = null;
		    	$seguimiento->fecha_fallecimiento = null;
		    }

	    	$seguimiento->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	    	return redirect()->route('vermodificarseguimiento',['id' => $idPaciente, 'num_seguimiento' => $num_seguimiento])->with('success','Seguimiento modificado correctamente');
	    }catch(QueryException $e){
            return redirect()->route('vermodificarseguimiento',['id' => $idPaciente, 'num_seguimiento' => $num_seguimiento])->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function eliminarSeguimiento($idPaciente, $num_seguimiento)
    {
    	$paciente = Pacientes::find($idPaciente);

	    $seguimiento = $paciente->Seguimientos[$num_seguimiento];
	    $seguimiento->delete();

	    $this->actualizarfechaModificacionPaciente($paciente);

	   	return redirect()->route('seguimientosnuevos',$idPaciente)->with('success','Seguimiento eliminado correctamente');
    }

}
