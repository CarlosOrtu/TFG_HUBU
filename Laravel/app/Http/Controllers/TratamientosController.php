<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\Pacientes;
use App\Models\Tratamientos;

class TratamientosController extends Controller
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
    /******************************************************************
    *                                                                 *
    *   Radioterapia                                                  *
    *                                                                 *
    *******************************************************************/
    public function verRadioterapia($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('radioterapia',['paciente' => $paciente]);
    }

    public function validarRadioterapia($request)
    {
    	//Calculamos la fecha de mañana
    	$seg = time();
		$manana = strtotime("+1 day", $seg);
		$manana = date("Y-m-d", $manana);
    	if($request->localizacion == "Otro"){
	        $validator = Validator::make($request->all(), [
	            'dosis' => 'required|gt:0',
	            'localizacion_especificar' => 'required',
	            'fecha_inicio' => 'required|date|before:'.$manana,
	            'fecha_fin' => 'required|date|after:'.$request->fecha_inicio,
	        ],
	        [
	        'gt' => 'La dosis debe ser un número positivo mayor que 0',
	        'required' => 'El campo :attribute no puede estar vacio',
	        'before' => 'Introduce una fecha valida',
	        'after' => 'Fecha fin no puede ser anterior a fecha inicio',
	        'date' => 'Introduce una fecha valida',
	        ]);
	    }else{
	    	$validator = Validator::make($request->all(), [
	            'dosis' => 'required|gt:0',
	            'fecha_inicio' => 'date|before:'.$manana,
	            'fecha_fin' => 'date|after:'.$request->fecha_inicio,
	        ],
	        [
	        'gt' => 'La dosis debe ser un número positivo mayor que 0',
	        'required' => 'El campo :attribute no puede estar vacio',
	        'before' => 'Introduce una fecha valida',
	        'after' => 'Fecha fin no puede ser anterior a fecha inicio',
	        'date' => 'Introduce una fecha valida',
	        ]);
	    }

        return $validator;
    }

    public function crearRadioterapia(Request $request, $id)
    {
    	try{
	        $validator = $this->validarRadioterapia($request);
	        if($validator->fails())
	            return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

			$tratamiento = new Tratamientos();

	        $tratamiento->id_paciente = $id;
	        $tratamiento->tipo = "Radioterapia";
	        if($request->localizacion == "Otro")
	            $tratamiento->localizacion = "Otro: ".$request->localizacion_especificar;
	        else
	            $tratamiento->localizacion = $request->localizacion;
	        $tratamiento->dosis = $request->dosis;
	        $tratamiento->subtipo = $request->intencion;
	        $tratamiento->fecha_inicio = $request->fecha_inicio;
	        $tratamiento->fecha_fin = $request->fecha_fin;
	        $tratamiento->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	        return redirect()->route('radioterapias',$id)->with('success','Radioterapia creada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('radioterapias',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function modificarRadioterapia(Request $request, $id, $num_radioterapia)
    {
    	try{
	    	$validator = $this->validarRadioterapia($request);
	        if($validator->fails())
	            return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

	    	$tratamientos = Tratamientos::where('tipo','Radioterapia')->where('id_paciente',$id)->get();
	    	$tratamiento = $tratamientos[$num_radioterapia-1];
	    	$tratamiento->id_paciente = $id;
	        $tratamiento->tipo = "Radioterapia";
	        if($request->localizacion == "Otro")
	            $tratamiento->localizacion = "Otro: ".$request->localizacion_especificar;
	        else
	            $tratamiento->localizacion = $request->localizacion;
	        $tratamiento->dosis = $request->dosis;
	        $tratamiento->subtipo = $request->intencion;
	        $tratamiento->fecha_inicio = $request->fecha_inicio;
	        $tratamiento->fecha_fin = $request->fecha_fin;
	        $tratamiento->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	        return redirect()->route('radioterapias',$id)->with('success','Radioterapia modificada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('radioterapias',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function eliminarRadioterapia(Request $request, $id, $num_radioterapia)
    {
	    $tratamientos = Tratamientos::where('tipo','Radioterapia')->where('id_paciente',$id)->get();
    	$tratamiento = $tratamientos[$num_radioterapia-1];

    	$paciente = Pacientes::find($id);


    	$tratamiento->delete();

    	$this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('radioterapias',$id)->with('success','Radioterapia eliminada correctamente');
    }

    /******************************************************************
    *                                                                 *
    *   Cirugía                                                       *
    *                                                                 *
    *******************************************************************/
    public function verCirugia($id)
    {
        $paciente = Pacientes::find($id);
        return view('cirugia',['paciente' => $paciente]);
    }
    public function validarCirugia($request)
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
    public function crearCirugia(Request $request, $id)
    {
    	try{
	        $validator = $this->validarCirugia($request);
	        if($validator->fails())
	            return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

			$tratamiento = new Tratamientos();

	        $tratamiento->id_paciente = $id;
	        $tratamiento->tipo = "Cirugia";
			$tratamiento->subtipo = $request->tipo;
	        $tratamiento->fecha_inicio = $request->fecha;


	        $tratamiento->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	        return redirect()->route('cirugias',$id)->with('success','Cirugía creada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('cirugias',$id)->with('SQLerror','Introduce una fecha valida');
        }    	
    }

    public function modificarCirugia(Request $request, $id, $num_cirugia)
    {
    	try{
	    	$validator = $this->validarCirugia($request);
	        if($validator->fails())
	            return back()->withErrors($validator->errors())->withInput();

	    	$paciente = Pacientes::find($id);

	    	$tratamientos = Tratamientos::where('tipo','Cirugia')->where('id_paciente',$id)->get();
	    	$tratamiento = $tratamientos[$num_cirugia-1];
	    	$tratamiento->id_paciente = $id;
	        $tratamiento->tipo = "Cirugia";
			$tratamiento->subtipo = $request->tipo;
	        $tratamiento->fecha_inicio = $request->fecha;

	        $tratamiento->save();

	        $this->actualizarfechaModificacionPaciente($paciente);

	        return redirect()->route('cirugias',$id)->with('success','Cirugía modificada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('cirugias',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

	public function eliminarCirugia(Request $request, $id, $num_cirugia)
    {
       	$paciente = Pacientes::find($id);

	    $tratamientos = Tratamientos::where('tipo','Cirugia')->where('id_paciente',$id)->get();
	    $tratamiento = $tratamientos[$num_cirugia-1];

    	$tratamiento->delete();

    	$this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('cirugias',$id)->with('success','Radioterapia eliminada correctamente');
    }

    /******************************************************************
    *                                                                 *
    *   Quimioterapia                                                 *
    *                                                                 *
    *******************************************************************/
    public function verQuimioterapia($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('quimioterapia',['paciente' => $paciente]);
    }
}
