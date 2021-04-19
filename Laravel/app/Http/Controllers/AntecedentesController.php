<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Antecedentes_medicos;
use App\Models\Antecedentes_oncologicos;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;

class AntecedentesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /******************************************************************
    *																  *
    *	Antecedentes medicos										  *
    *																  *
  	*******************************************************************/
    public function verAntecedentesMedicos($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('antecedentesmedicos',['paciente' => $paciente]);
    }

    public function validarDatosAntecedentesMedicos($request)
    {
        if($request->tipo == "Otro"){
            $validator = Validator::make($request->all(), [
                'tipo_especificar' => 'required',
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
            ]);
        }else{
            $validator = Validator::make($request->all(),[]);
        }

        return $validator;
    }

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
    }
    /******************************************************************
    *																  *
    *	Antecedentes oncológicos									  *
    *																  *
  	*******************************************************************/
  	public function verAntecedentesOncologicos($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('antecedentesoncologicos',['paciente' => $paciente]);
    }

    public function validarDatosAntecedentesOncologicos($request)
    {
        if($request->tipo == "Otro"){
            $validator = Validator::make($request->all(), [
                'tipo_especificar' => 'required',
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
            ]);
        }else{
            $validator = Validator::make($request->all(),[]);
        }

        return $validator;
    }

    public function crearAntecedentesOncologicos(Request $request, $id)
    {
        $validator = $this->validarDatosAntecedentesOncologicos($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();

        $antecedente = new Antecedentes_oncologicos();

        $antecedente->id_paciente = $id;
        if($request->tipo == "Otro")
            $antecedente->tipo = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo = $request->tipo;
        $antecedente->save();

        return redirect()->route('antecedentesoncologicos',$id)->with('success','Antecedente oncológico creado correctamente');
    }

    public function modificarAntecedentesOncologicos(Request $request, $id, $num_antecendente_oncologico)
    {
        $validator = $this->validarDatosAntecedentesOncologicos($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $usuario = Pacientes::find($id);
        //Obetenemos todos los antecendentes
        $antecedentes = $usuario->Antecedentes_oncologicos;
        $antecedente = $antecedentes[$num_antecendente_oncologico-1];
        $antecedente->id_paciente = $id;
        if($request->tipo == "Otro")
            $antecedente->tipo = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo = $request->tipo;
        $antecedente->save();

        return redirect()->route('antecedentesoncologicos',$id)->with('success','Antecedente oncológico modificado correctamente');
    }

    public function eliminarAntecedentesOncologicos($id, $num_antecendente_oncologico)
    {
        $usuario = Pacientes::find($id);
        //Obetenemos todas los antecedentes
        $antecedentes = $usuario->Antecedentes_oncologicos;
        $antecedente = $antecedentes[$num_antecendente_oncologico-1];
        $antecedente->delete();

        return redirect()->route('antecedentesoncologicos',$id)->with('success','Antecedente oncológico eliminado correctamente');
    }
    /******************************************************************
    *																  *
    *	Antecedentes familiares 									  *
    *																  *
  	*******************************************************************/
  	public function verAntecedentesFamiliares($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('antecedentesfamiliares',['paciente' => $paciente]);
    }

    public function crearAntecedentesFamiliares(Request $request, $id)
    {
    	/*
        $validator = $this->validarDatosAntecedentesOncologicos($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
		*/
        $antecedente = new Antecedentes_familiares();

        $antecedente->id_paciente = $id;
        $antecedente->familiar = $request->familiar;
        $antecedente->save();
        foreach($request->enfermedad as $enfermedad){
        	$enfermedadFamiliar = new Enfermedades_familiar();
        	$enfermedadFamiliar->id_antecedente_f =  $antecedente->id_antecedente_f;
        	$enfermedadFamiliar->tipo = $enfermedad;
        	$enfermedadFamiliar->save();
        }
        return redirect()->route('antecedentesfamiliares',$id)->with('success','Antecedente oncológico creado correctamente');
    }

    public function modificarAntecedentesFamiliares(Request $request, $id, $num_antecendente_familiar)
    {
    	/*
        $validator = $this->validarDatosAntecedentesOncologicos($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        */
        $usuario = Pacientes::find($id);
        //Obetenemos todos los antecendentes
        $antecedentes = $usuario->Antecedentes_familiares;
        $antecedente = $antecedentes[$num_antecendente_familiar-1];
        $antecedente->id_paciente = $id;
        $antecedente->familiar = $request->familiar;
        $antecedente->save();
        $i = 0;
        $enfermedadesFamiliares = $antecedente->Enfermedades_familiar;
        foreach($request->enfermedad as $enfermedad){
        	if($i < count($enfermedadesFamiliares)){
	        	$enfermedadFamiliar = $enfermedadesFamiliares[$i];
	        	$enfermedadFamiliar->id_antecedente_f =  $antecedente->id_antecedente_f;
		        if($enfermedad == "Otro")
		            $enfermedadFamiliar->tipo = "Otro: ".$request->tipo_especificar[$i];
		        else
					$enfermedadFamiliar->tipo = $enfermedad;
	        	$enfermedadFamiliar->save();
	        }else{
	        	$enfermedadFamiliar = new Enfermedades_familiar();
        		$enfermedadFamiliar->id_antecedente_f =  $antecedente->id_antecedente_f;
		        if($enfermedad == "Otro")
		            $enfermedadFamiliar->tipo = "Otro: ".$request->tipo_especificar[$i];
		        else
					$enfermedadFamiliar->tipo = $enfermedad;
        		$enfermedadFamiliar->save();
	        }
        	$i = $i + 1;
        }

        return redirect()->route('antecedentesfamiliares',$id)->with('success','Antecedente familiar modificado correctamente');
    }

    public function eliminarAntecedentesFamiliares($id, $num_antecendente_familiar)
    {
        $usuario = Pacientes::find($id);
        //Obetenemos todas los antecedentes
        $antecedentes = $usuario->Antecedentes_familiares;
        $antecedente = $antecedentes[$num_antecendente_familiar-1];
        $antecedente->delete();

        return redirect()->route('antecedentesfamiliares',$id)->with('success','Antecedente oncológico eliminado correctamente');
    }
}
