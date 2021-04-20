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
	    	$usuario = Pacientes::find($id);

	    	$tratamientos = $usuario->Tratamientos->where('tipo','Radioterapia');
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

	        return redirect()->route('radioterapias',$id)->with('success','Radioterapia modificada correctamente');
	    }catch(QueryException $e){
            return redirect()->route('radioterapias',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function eliminarRadioterapia(Request $request, $id, $num_radioterapia)
    {
    	$usuario = Pacientes::find($id);

    	$tratamientos = $usuario->Tratamientos->where('tipo','Radioterapia');
    	$tratamiento = $tratamientos[$num_radioterapia-1];

    	$tratamiento->delete();

    	return redirect()->route('radioterapias',$id)->with('success','Radioterapia eliminada correctamente');
    }
}
/*
 public function crearAntecedentesMedicos(Request $request, $id)
    {
        $validator = $this->validarDatosAntecedentesMedicos($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();


        $antecedente = new Antecedentes_medicos();

        $antecedente->id_paciente = $id;
        if($request->tipo == "Otro")
            $antecedente->tipo_antecedente = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo_antecedente = $request->tipo;
        $antecedente->save();

        return redirect()->route('antecedentesmedicos',$id)->with('success','Antecedente médico creado correctamente');
    }

    public function modificarAntecedentesMedicos(Request $request, $id, $num_antecendente_medico)
    {
        $validator = $this->validarDatosAntecedentesMedicos($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $usuario = Pacientes::find($id);
        //Obetenemos todos los antecendentes
        $antecedentes = $usuario->Antecedentes_medicos;
        $antecedente = $antecedentes[$num_antecendente_medico-1];
        $antecedente->id_paciente = $id;
        if($request->tipo == "Otro")
            $antecedente->tipo_antecedente = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo_antecedente = $request->tipo;
        $antecedente->save();

        return redirect()->route('antecedentesmedicos',$id)->with('success','Antecedente médico modificado correctamente');
    }

    public function eliminarAntecedentesMedicos($id, $num_antecendente_medico)
    {
        $usuario = Pacientes::find($id);
        //Obetenemos todas los antecedentes
        $antecedentes = $usuario->Antecedentes_medicos;
        $antecedente = $antecedentes[$num_antecendente_medico-1];
        $antecedente->delete();

        return redirect()->route('antecedentesmedicos',$id)->with('success','Antecedente médico eliminado correctamente');
