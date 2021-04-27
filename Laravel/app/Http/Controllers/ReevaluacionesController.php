<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use App\Models\Reevaluaciones;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
 
class ReevaluacionesController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function actualizarfechaModificacionPaciente($paciente)
    {
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save();
    }

    public function verReevaluacionNueva($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('reevaluacionesnuevas',['paciente' => $paciente]);
    }

    public function validarReevaluacion($request)
    {
    	//Calculamos la fecha de mañana
    	$seg = time();
		$manana = strtotime("+1 day", $seg);
		$manana = date("Y-m-d", $manana);
		if($request->estado == "Progresión" and $request->localizacion == "Otro"){
			$validator = Validator::make($request->all(), [
	            'fecha' => 'required|date|before:'.$manana,
	            'localizacion_especificar' => 'required',
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

    public function crearReevaluación(Request $request, $id)
    {
    	try{
		    $validator = $this->validarReevaluacion($request);
	    	if($validator->fails())
	        	return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

	    	$nuevaReevaluacion = new Reevaluaciones();
	    	$nuevaReevaluacion->id_paciente = $id;
	      	$nuevaReevaluacion->fecha = $request->fecha;
	    	$nuevaReevaluacion->estado	 = $request->estado;
	    	if($request->estado == "Progresión"){
	    		if($request->localizacion == "Otro")
					$nuevaReevaluacion->progresion_localizacion = "Otro: ".$request->localizacion_especificar;
		    	else
		    		$nuevaReevaluacion->progresion_localizacion = $request->localizacion;
		    	$nuevaReevaluacion->tipo_tratamiento = $request->tipo_tratamiento;
		    }
	    	$nuevaReevaluacion->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	    	return redirect()->route('reevaluacionesnuevas',$id)->with('success','Reevaluación creada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('reevaluacionesnuevas',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function verReevaluacion($id, $num_reevaluacion)
    {
    	$paciente = Pacientes::find($id);
    	$reevaluaciones = $paciente->Reevaluaciones;
    	$reevaluacion = $reevaluaciones[$num_reevaluacion];
    	return view('reevaluaciones',['paciente' => $paciente, 'reevaluacion' => $reevaluacion, 'posicion' => $num_reevaluacion]);
    }

    public function modificarReevaluación(Request $request, $id, $num_reevaluacion)
    {
    	try{
	    	$validator = $this->validarReevaluacion($request);
	    	if($validator->fails())
	        	return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

	    	$reevaluacion = $paciente->Reevaluaciones[$num_reevaluacion];
	    	$reevaluacion->id_paciente = $id;
	      	$reevaluacion->fecha = $request->fecha;
	    	$reevaluacion->estado	 = $request->estado;
	    	if($request->estado == "Progresión"){
	    		if($request->localizacion == "Otro")
					$reevaluacion->progresion_localizacion = "Otro: ".$request->localizacion_especificar;
		    	else
		    		$reevaluacion->progresion_localizacion = $request->localizacion;
		    	$reevaluacion->tipo_tratamiento = $request->tipo_tratamiento;
		    }
	    	$reevaluacion->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	    	return redirect()->route('vermodificarreevaluacion',['id' => $id, 'num_reevaluacion' => $num_reevaluacion])->with('success','Reevaluación modificada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('vermodificarreevaluacion',['id' => $id, 'num_reevaluacion' => $num_reevaluacion])->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function eliminarReevaluación($id, $num_reevaluacion)
    {
    	$paciente = Pacientes::find($id);

	    $reevaluacion = $paciente->Reevaluaciones[$num_reevaluacion];
	    $reevaluacion->delete();

	    $this->actualizarfechaModificacionPaciente($paciente);

	   	return redirect()->route('reevaluacionesnuevas',$id)->with('success','Reevaluación eliminada correctamente');
    }
}
