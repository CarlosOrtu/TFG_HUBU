<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use App\Models\Reevaluaciones;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Utilidades\Encriptacion;
 
class ReevaluacionesController extends Controller
{
    private $encriptacion;

	public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();

    }
    public function verReevaluacionSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verreevaluaciones',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verreevaluaciones',['paciente' => $paciente]);
        }
    }

    public function actualizarfechaModificacionPaciente($paciente)
    {
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save();
    }

    public function verReevaluacionNueva($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('reevaluacionesnuevas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('reevaluacionesnuevas',['paciente' => $paciente]);
        }
    }

    public function validarReevaluacion($request)
    {
    	//Calculamos la fecha de mañana
    	$seg = time();
		$manana = strtotime("+1 day", $seg);
		$manana = date("Y-m-d", $manana);
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
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('reevaluaciones',['paciente' => $paciente, 'reevaluacion' => $reevaluacion, 'posicion' => $num_reevaluacion, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('reevaluaciones',['paciente' => $paciente, 'reevaluacion' => $reevaluacion, 'posicion' => $num_reevaluacion]);
        }
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
		    }else{
                $reevaluacion->progresion_localizacion = null;
                $reevaluacion->tipo_tratamiento = null;
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
