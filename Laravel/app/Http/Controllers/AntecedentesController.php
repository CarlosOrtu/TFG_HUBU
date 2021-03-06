<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Antecedentes_medicos;
use App\Models\Antecedentes_oncologicos;
use App\Models\Antecedentes_familiares;
use App\Models\Enfermedades_familiar;
use App\Utilidades\Encriptacion;


class AntecedentesController extends Controller
{
    private $encriptacion;

    public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();
    }

    public function actualizarfechaModificacionPaciente($paciente)
    {
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save();
    }
    /******************************************************************
    *																  *
    *	Antecedentes medicos										  *
    *																  *
  	*******************************************************************/
    public function verAntecedentesMedicosSinModificar($idPaciente){
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verantecedentesmedicos',['paciente' => $paciente,'nombre' => $nhcDesencriptado]);
        }

        return view('verantecedentesmedicos',['paciente' => $paciente]);
    }

    public function verAntecedentesMedicos($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('antecedentesmedicos',['paciente' => $paciente,'nombre' => $nhcDesencriptado]);
        }

        return view('antecedentesmedicos',['paciente' => $paciente]);
    }

    public function crearAntecedentesMedicos(Request $request, $idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);

        $antecedente = new Antecedentes_medicos();

        $antecedente->id_paciente = $idPaciente;
        if($request->tipo == "Otro")
            $antecedente->tipo_antecedente = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo_antecedente = $request->tipo;
        $antecedente->save();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesmedicos',$idPaciente)->with('success','Antecedente m??dico creado correctamente');
    }

    public function modificarAntecedentesMedicos(Request $request, $idPaciente, $num_antecendente_medico)
    {
        $paciente = Pacientes::find($idPaciente);

        //Obetenemos todos los antecendentes
        $antecedentes = $paciente->Antecedentes_medicos;
        $antecedente = $antecedentes[$num_antecendente_medico];
        $antecedente->id_paciente = $idPaciente;
        if($request->tipo == "Otro")
            $antecedente->tipo_antecedente = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo_antecedente = $request->tipo;
        $antecedente->save();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesmedicos',$idPaciente)->with('success','Antecedente m??dico modificado correctamente');
    }

    public function eliminarAntecedentesMedicos($idPaciente, $num_antecendente_medico)
    {
        $paciente = Pacientes::find($idPaciente);
   
        //Obetenemos todas los antecedentes
        $antecedentes = $paciente->Antecedentes_medicos;
        $antecedente = $antecedentes[$num_antecendente_medico];
        $antecedente->delete();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesmedicos',$idPaciente)->with('success','Antecedente m??dico eliminado correctamente');
    }
    /******************************************************************
    *																  *
    *	Antecedentes oncol??gicos									  *
    *																  *
  	*******************************************************************/
    public function verAntecedentesOncologicosSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verantecedentesoncologicos',['paciente' => $paciente,'nombre' => $nhcDesencriptado]);
        }

        return view('verantecedentesoncologicos',['paciente' => $paciente]);
    }

  	public function verAntecedentesOncologicos($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('antecedentesoncologicos',['paciente' => $paciente,'nombre' => $nhcDesencriptado]);
        }

        return view('antecedentesoncologicos',['paciente' => $paciente]);
    }

    public function crearAntecedentesOncologicos(Request $request, $idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);

        $antecedente = new Antecedentes_oncologicos();

        $antecedente->id_paciente = $idPaciente;
        if($request->tipo == "Otro")
            $antecedente->tipo = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo = $request->tipo;
        $antecedente->save();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesoncologicos',$idPaciente)->with('success','Antecedente oncol??gico creado correctamente');
    }

    public function modificarAntecedentesOncologicos(Request $request, $idPaciente, $num_antecendente_oncologico)
    {
        $paciente = Pacientes::find($idPaciente);
        //Obetenemos todos los antecendentes
        $antecedentes = $paciente->Antecedentes_oncologicos;
        $antecedente = $antecedentes[$num_antecendente_oncologico];
        $antecedente->id_paciente = $idPaciente;
        if($request->tipo == "Otro")
            $antecedente->tipo = "Otro: ".$request->tipo_especificar;
        else
            $antecedente->tipo = $request->tipo;
        $antecedente->save();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesoncologicos',$idPaciente)->with('success','Antecedente oncol??gico modificado correctamente');
    }

    public function eliminarAntecedentesOncologicos($idPaciente, $num_antecendente_oncologico)
    {
        $paciente = Pacientes::find($idPaciente);
        //Obetenemos todas los antecedentes
        $antecedentes = $paciente->Antecedentes_oncologicos;
        $antecedente = $antecedentes[$num_antecendente_oncologico];
        $antecedente->delete();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesoncologicos',$idPaciente)->with('success','Antecedente oncol??gico eliminado correctamente');
    }
    /******************************************************************
    *																  *
    *	Antecedentes familiares 									  *
    *																  *
  	*******************************************************************/
    public function verAntecedentesFamiliaresSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verantecedentesfamiliares',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('verantecedentesfamiliares',['paciente' => $paciente]);
    }

  	public function verAntecedentesFamiliares($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('antecedentesfamiliares',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('antecedentesfamiliares',['paciente' => $paciente]);
    }

    public function validarDatosAntecedente($request)
    {
        $validator = Validator::make($request->all(), [
            'familiar' => 'required',
        ],
        [
            'required' => 'El campo :attribute no puede estar vacio',
        ]);

        return $validator;
    }

    public function crearAntecedentesFamiliares(Request $request, $idPaciente)
    {
        $validator = $this->validarDatosAntecedente($request);
        if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();

        $paciente = Pacientes::find($idPaciente);

        $antecedente = new Antecedentes_familiares();

        $antecedente->id_paciente = $idPaciente;
        $antecedente->familiar = $request->familiar;
        $antecedente->save();
        $contador = 0;
        if(isset($request->enfermedades)){
	        foreach($request->enfermedades as $enfermedad){
	        	$enfermedadFamiliar = new Enfermedades_familiar();
	        	$enfermedadFamiliar->id_antecedente_f =  $antecedente->id_antecedente_f;
	        	if($enfermedad == "Otro"){
	        		$enfermedadFamiliar->tipo = "Otro: ".$request->tipos_especificar[$i];
	        		$contador = $contador + 1;
	        	}else
	        		$enfermedadFamiliar->tipo = $enfermedad;
	        	$enfermedadFamiliar->save();
	        }
	    }

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesfamiliares',$idPaciente)->with('success','Antecedente familiar creado correctamente');
    }

    public function modificarAntecedentesFamiliares(Request $request, $idPaciente, $num_antecendente_familiar)
    {
        $validator = $this->validarDatosAntecedente($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();

        $paciente = Pacientes::find($idPaciente);
        //Obetenemos todos los antecendentes
        $antecedentes = $paciente->Antecedentes_familiares;
        $antecedente = $antecedentes[$num_antecendente_familiar];
        $antecedente->id_paciente = $idPaciente;
        $antecedente->familiar = $request->familiar;
        $antecedente->save();
        $i = 0;
        $j = 0;
        $enfFamiliares = $antecedente->Enfermedades_familiar;
        if(isset($request->enfermedades)){
	        foreach($request->enfermedades as $enfermedad){
	        	if($i < count($enfFamiliares)){
		        	$enfermedadFamiliar = $enfFamiliares[$i];
		        	$enfermedadFamiliar->id_antecedente_f =  $antecedente->id_antecedente_f;
			        if($enfermedad == "Otro")
			            $enfermedadFamiliar->tipo = "Otro: ".$request->tipos_especificar[$j];
			        else
						$enfermedadFamiliar->tipo = $enfermedad;
		        	$enfermedadFamiliar->save();
		        	$j = $j + 1;
		        }else{
		        	$enfermedadFamiliar = new Enfermedades_familiar();
	        		$enfermedadFamiliar->id_antecedente_f =  $antecedente->id_antecedente_f;
			        if($enfermedad == "Otro"){
			            $enfermedadFamiliar->tipo = "Otro: ".$request->tipos_especificar[$j];
			        	$j = $j + 1;
			        }else
						$enfermedadFamiliar->tipo = $enfermedad;
	        		$enfermedadFamiliar->save();
		        }
	        	$i = $i + 1;
	        }
	    }

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesfamiliares',$idPaciente)->with('success','Antecedente familiar modificado correctamente');
    }

    public function eliminarAntecedentesFamiliares($idPaciente, $num_antecendente_familiar)
    {
        $paciente = Pacientes::find($idPaciente);
        //Obetenemos todas los antecedentes
        $antecedentes = $paciente->Antecedentes_familiares;
        $antecedente = $antecedentes[$num_antecendente_familiar];
        $antecedente->delete();

        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('antecedentesfamiliares',$idPaciente)->with('success','Antecedente familiar eliminado correctamente');
    }
}
