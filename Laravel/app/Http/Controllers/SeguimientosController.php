<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Seguimientos;


class SeguimientosController extends Controller
{    
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function verSeguimientoNuevo($id)
    {
    	$paciente = Pacientes::find($id);
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
	            'motivo_especificar' => 'required',
	            'fecha_fallecimiento' => 'required|date|before:'.$manana,
	        ],
	        [
	        'required' => 'El campo :attribute no puede estar vacio',
	        'before' => 'Introduce una fecha valida',
	        'date' => 'Introduce una fecha valida',
	        ]);	
		}else{
	        $validator = Validator::make($request->all(), [
	            'fecha' => 'required|date|before:'.$manana,
	        ],
	        [
	        'required' => 'El campo :attribute no puede estar vacio',
	        'before' => 'Introduce una fecha valida',
	        'date' => 'Introduce una fecha valida',
	        ]);
    	}	

        return $validator;
    }

    public function crearSeguimiento(Request $request, $id)
    {
    	try{
		    $validator = $this->validarSeguimiento($request);
	    	if($validator->fails())
	        	return back()->withErrors($validator->errors())->withInput();


	    	$nuevoSeguimiento = new Seguimientos();
	    	$nuevoSeguimiento->id_paciente = $id;
	      	$nuevoSeguimiento->fecha = $request->fecha;
	    	$nuevoSeguimiento->estado	 = $request->estado;
	    	if($request->estado == "Fallecido"){
	    		if($request->motivo == "Otro")
					$nuevoSeguimiento->fallecido_motivo = "Otro: ".$request->motivo_especificar;
		    	else
		    		$nuevoSeguimiento->fallecido_motivo	 = $request->motivo;
		    	$nuevoSeguimiento->fecha_fallecimiento = $request->fecha_fallecimiento;
		    }
		    $nuevoSeguimiento->tratamiento_dirigido = $request->tratamiento_dirigido;
	    	$nuevoSeguimiento->save();

	    	return redirect()->route('seguimientosnuevos',$id)->with('success','Seguimiento creado correctamente');
	    }catch(QueryException $e){
            return redirect()->route('seguimientosnuevos',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function verSeguimiento($id, $num_seguimiento)
    {
    	$paciente = Pacientes::find($id);
    	$seguimientos = $paciente->Seguimientos;
    	$seguimiento = $seguimientos[$num_seguimiento-1];
    	return view('seguimientos',['paciente' => $paciente, 'seguimiento' => $seguimiento, 'posicion' => $num_seguimiento]);
    }

    public function modificarSeguimiento(Request $request, $id, $num_seguimiento)
    {
    	try{
	    	$validator = $this->validarSeguimiento($request);
	    	if($validator->fails())
	        	return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

	    	$seguimiento = $paciente->Seguimientos[$num_seguimiento-1];
	    	$seguimiento->id_paciente = $id;
	      	$seguimiento->fecha = $request->fecha;
	    	$seguimiento->estado	 = $request->estado;
	    	if($request->estado == "Fallecido"){
	    		if($request->motivo == "Otro")
					$seguimiento->fallecido_motivo = "Otro: ".$request->motivo_especificar;
		    	else
		    		$seguimiento->fallecido_motivo	 = $request->motivo;
		    	$seguimiento->fecha_fallecimiento = $request->fecha_fallecimiento;
		    }
		    $seguimiento->tratamiento_dirigido = $request->tratamiento_dirigido;
	    	$seguimiento->save();

	    	return redirect()->route('vermodificarseguimiento',['id' => $id, 'num_seguimiento' => $num_seguimiento])->with('success','Seguimiento modificado correctamente');
	    }catch(QueryException $e){
            return redirect()->route('vermodificarseguimiento',['id' => $id, 'num_seguimiento' => $num_seguimiento])->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function eliminarSeguimiento($id, $num_seguimiento)
    {
    	$paciente = Pacientes::find($id);

	    $seguimiento = $paciente->Seguimientos[$num_seguimiento-1];
	    $seguimiento->delete();

	   	return redirect()->route('seguimientosnuevos',$id)->with('success','Seguimiento eliminado correctamente');
    }

}
