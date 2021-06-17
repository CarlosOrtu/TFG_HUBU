<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\Pacientes;
use App\Models\Enfermedades;
use App\Models\Sintomas;
use App\Models\Metastasis;
use App\Models\Pruebas_realizadas;
use App\Models\Tecnicas_realizadas;
use App\Models\Otros_tumores;
use App\Models\Biomarcadores;
use App\Utilidades\Encriptacion;


class EnfermedadController extends Controller
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
    *	Datos enfermedad											  *
    *																  *
  	*******************************************************************/
    public function verDatosEnfermedadSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verdatosenfermedad',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('verdatosenfermedad',['paciente' => $paciente]);
    }

    public function verDatosEnfermedad($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('datosenfermedad',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('datosenfermedad',['paciente' => $paciente]);
    }

    public function validarDatosModificarEnfermedad($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        if($request->fecha_primera_consulta != null){
            $validator = Validator::make($request->all(), [
                'fecha_primera_consulta' => 'required|before:'.$manana,
                'fecha_diagnostico' => 'required|before:'.$manana.'|after:'.$request->fecha_primera_consulta,
                'T_tamano' => 'required|gt:0',
            ],
            [
            	'required' => 'El campo :attribute no puede estar vacio',
            	'before' => 'Introduce una fecha valida',
            	'gt' => 'El valor ha de ser mayor que 0',
                'date' => 'Introduce una fecha valida',
                'after' => 'La fecha del diagnostico no puede ser anterior a la fecha primera fecha_primera_consulta'
            ]);

            return $validator;
        }
        $validator = Validator::make($request->all(), [
            'fecha_primera_consulta' => 'required|before:'.$manana,
            'fecha_diagnostico' => 'required|before:'.$manana,
            'T_tamano' => 'required|gt:0',
        ],
        [
            'required' => 'El campo :attribute no puede estar vacio',
            'before' => 'Introduce una fecha valida',
            'gt' => 'El valor ha de ser mayor que 0',
            'date' => 'Introduce una fecha valida',
            'after' => 'La fecha del diagnostico no puede ser anterior a la fecha primera fecha_primera_consulta'
        ]);

        return $validator;
    }

    public function guardarDatosEnfermedad(Request $request, $idPaciente)
    {
        try{
        	$validator = $this->validarDatosModificarEnfermedad($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();

            $enfermedad = Enfermedades::where('id_paciente',$idPaciente)->first();
        	if(empty($enfermedad))
        		$enfermedad = new Enfermedades();
        	$enfermedad->id_paciente = $idPaciente;
        	$enfermedad->fecha_primera_consulta = $request->fecha_primera_consulta;
        	$enfermedad->fecha_diagnostico = $request->fecha_diagnostico;
        	$enfermedad->ECOG = $request->ECOG;
        	$enfermedad->T = $request->T;
        	$enfermedad->T_tamano = $request->T_tamano;
        	$enfermedad->N = $request->N;
        	$enfermedad->N_afectacion = $request->N_afectacion;
        	$enfermedad->M = $request->M;
        	$enfermedad->num_afec_metas = $request->num_afec_metas;
        	$enfermedad->TNM = $request->TNM;
        	$enfermedad->tipo_muestra = $request->tipo_muestra;
        	$enfermedad->histologia_tipo = $request->histologia_tipo;
        	if($request->histologia_subtipo == "Otro")
        		$enfermedad->histologia_subtipo = "Otro: ".$request->histologia_subtipo_especificar;
        	else
        		$enfermedad->histologia_subtipo = $request->histologia_subtipo;
        	$enfermedad->histologia_grado = $request->histologia_grado;
            $enfermedad->tratamiento_dirigido = $request->tratamiento_dirigido;

        	$enfermedad->save();

            $paciente = Pacientes::find($idPaciente);
            $this->actualizarfechaModificacionPaciente($paciente);


        	return redirect()->route('datosenfermedad',$idPaciente)->with('success','Datos enfermedad guardados correctamente');
        }catch(QueryException $e){
            return redirect()->route('datosenfermedad',$idPaciente)->with('SQLerror','Introduce una fecha valida');
        }
    }

    /******************************************************************
    *																  *
    *	Datos sintoma											      *
    *																  *
  	*******************************************************************/
    public function verDatosSintomasSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verdatossintomas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('verdatossintomas',['paciente' => $paciente]);     
    }

    public function verDatosSintomas($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('datossintomas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('datossintomas',['paciente' => $paciente]);    
    }

    public function validarDatosSintomas($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        if(isset($request->fecha_inicio)){
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'required|date|before:'.$manana,
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
                'before' => 'Introduce una fecha valida',
                'date' => 'Introduce una fecha valida',
            ]);

            return $validator;
        }

        $validator = Validator::make($request->all(),[]);

        return $validator;
    }

    public function validarDatosFecha($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        $validator = Validator::make($request->all(), [
            'fecha_inicio' => 'required|date|before:'.$manana,
        ],
        [
            'required' => 'El campo :attribute no puede estar vacio',
            'before' => 'Introduce una fecha valida',
            'date' => 'Introduce una fecha valida',
        ]);

        return $validator;

    }

    public function crearDatosSintomas(Request $request, $idPaciente)
    {
        try{
            $validator = $this->validarDatosSintomas($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            
            $sintoma = new Sintomas();

            $enfermedad = Pacientes::find($idPaciente)->Enfermedades;
            $idEnfermedad = $enfermedad->id_enfermedad;

            if(isset($request->fecha_inicio))
                $fecha_sintomas = $request->fecha_inicio;
            elseif(count($enfermedad->Sintomas) > 0)
                $fecha_sintomas = $enfermedad->Sintomas[0]->fecha_inicio;
            else
                $fecha_sintomas = null;

            $sintoma->id_enfermedad = $idEnfermedad;
            if($request->tipo == "Dolor otra localización")
                $sintoma->tipo = "Localización: ".$request->tipo_especificar_localizacion;
            if($request->tipo == "Otro")
                $sintoma->tipo = "Otro: ".$request->tipo_especificar;
            else
                $sintoma->tipo = $request->tipo;
            $sintoma->fecha_inicio = $fecha_sintomas;    
            $sintoma->save();

            $paciente = Pacientes::find($idPaciente);
            $this->actualizarfechaModificacionPaciente($paciente);

            return redirect()->route('datossintomas',$idPaciente)->with('success','Sintoma creado correctamente');
        }catch(QueryException $e){
            return redirect()->route('datossintomas',$idPaciente)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function modificarDatosSintomas(Request $request, $idPaciente, $num_sintoma)
    {
        $enfermedad = Pacientes::find($idPaciente)->Enfermedades;
        $idEnfermedad =$enfermedad->id_enfermedad;
        
        $fecha_sintomas = $enfermedad->Sintomas[0]->fecha_inicio;

        //Obetenemos todos los sintomas
        $sintomas = Enfermedades::find($idEnfermedad)->Sintomas;
    	$sintoma = $sintomas[$num_sintoma];
    	$sintoma->id_enfermedad = $idEnfermedad;
    	if($request->tipo == "Dolor otra localización")
    		$sintoma->tipo = "Localización: ".$request->tipo_especificar_localizacion;
    	if($request->tipo == "Otro")
    		$sintoma->tipo = "Otro: ".$request->tipo_especificar;
    	else
    		$sintoma->tipo = $request->tipo;
    	$sintoma->fecha_inicio = $fecha_sintomas;	
    	$sintoma->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('datossintomas',$idPaciente)->with('success','Sintoma modificado correctamente');
    }

    public function eliminarSintoma($idPaciente, $num_sintoma)
    {
    	$idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todos los sintomas
        $sintomas = Enfermedades::find($idEnfermedad)->Sintomas;
        $sintoma = $sintomas[$num_sintoma];
  		$sintoma->delete();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

  		return redirect()->route('datossintomas',$idPaciente)->with('success','Sintoma eliminado correctamente');
    }

    public function modificarFechaSintomas(Request $request, $idPaciente)
    {
        try{
            $validator = $this->validarDatosFecha($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
            //Obetenemos todos los sintomas
            $sintomas = Enfermedades::find($idEnfermedad)->Sintomas;

            foreach($sintomas as $sintoma){
                $sintoma->fecha_inicio = $request->fecha_inicio;
                $sintoma->save();
            }
            return redirect()->route('datossintomas',$idPaciente)->with('success','Fecha modificada correctamente');
        }catch(QueryException $e){
            return redirect()->route('datossintomas',$idPaciente)->with('SQLerror','Introduce una fecha valida');
        }
    }

    /******************************************************************
    *																  *
    *	Metastasis											          *
    *																  *
  	*******************************************************************/
    public function verMetastasisSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('vermetastasis',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('vermetastasis',['paciente' => $paciente]);
    }

   	public function verMetastasis($idPaciente)
    {
    	$paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('metastasis',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('metastasis',['paciente' => $paciente]);
    }


    public function crearMetastasis(Request $request, $idPaciente)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;

        $metastasis = new Metastasis();

        $metastasis->id_enfermedad = $idEnfermedad;
        if($request->localizacion == "Otro")
            $metastasis->tipo = "Otro: ".$request->localizacion_especificar;
        else
            $metastasis->tipo = $request->localizacion;
        $metastasis->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('metastasis',$idPaciente)->with('success','Metastasis creada correctamente');
    }

    public function modificarMetastasis(Request $request, $idPaciente, $num_metastasis)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todas las metastasis
        $metastasis = Enfermedades::find($idEnfermedad)->Metastasis;
    	$metastasis = $metastasis[$num_metastasis];
    	$metastasis->id_enfermedad = $idEnfermedad;
    	if($request->localizacion == "Otro")
    		$metastasis->tipo = "Otro: ".$request->localizacion_especificar;
    	else
    		$metastasis->tipo = $request->localizacion;
    	$metastasis->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('metastasis',$idPaciente)->with('success','Metastasis modificada correctamente');
    }

    public function eliminarMetastasis($idPaciente, $num_metastasis)
    {
    	$idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todas las metastasis
        $metastasis = Enfermedades::find($idEnfermedad)->Metastasis;
        $metastasis = $metastasis[$num_metastasis];
  		$metastasis->delete();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);
  		return redirect()->route('metastasis',$idPaciente)->with('success','Metastasis eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Pruebas                                                       *
    *                                                                 *
    *******************************************************************/
    public function verPruebasSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verpruebas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('verpruebas',['paciente' => $paciente]);
    }
    public function verPruebas($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('pruebas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('pruebas',['paciente' => $paciente]);
    }


    public function crearPruebas(Request $request, $idPaciente)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;

        $prueba = new Pruebas_realizadas();

        $prueba->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $prueba->tipo = "Otro: ".$request->tipo_especificar;
        else
            $prueba->tipo = $request->tipo;
        $prueba->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('pruebas',$idPaciente)->with('success','Prueba creada correctamente');
    }

    public function modificarPruebas(Request $request, $idPaciente, $num_prueba)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todas las pruebas
        $pruebas = Enfermedades::find($idEnfermedad)->Pruebas_realizadas;
        $prueba = $pruebas[$num_prueba];
        $prueba->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $prueba->tipo = "Otro: ".$request->tipo_especificar;
        else
            $prueba->tipo = $request->tipo;
        $prueba->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('pruebas',$idPaciente)->with('success','Prueba modificada correctamente');
    }

    public function eliminarPruebas($idPaciente, $num_prueba)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todas las pruebas
        $pruebas = Enfermedades::find($idEnfermedad)->Pruebas_realizadas;
        $prueba = $pruebas[$num_prueba];
        $prueba->delete();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('pruebas',$idPaciente)->with('success','Prueba eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Técnicas                                                       *
    *                                                                 *
    *******************************************************************/
    public function verTecnicasSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('vertecnicas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('vertecnicas',['paciente' => $paciente]);
    }

    public function verTecnicas($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('tecnicas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('tecnicas',['paciente' => $paciente]);
    }


    public function crearTecnicas(Request $request, $idPaciente)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;

        $tecnica = new Tecnicas_realizadas();

        $tecnica->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tecnica->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tecnica->tipo = $request->tipo;
        $tecnica->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('tecnicas',$idPaciente)->with('success','Tecnica creada correctamente');
    }

    public function modificarTecnicas(Request $request, $idPaciente, $num_tecnica)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todas las técnicas
        $tecnicas = Enfermedades::find($idEnfermedad)->Tecnicas_realizadas;
        $tecnica = $tecnicas[$num_tecnica];
        $tecnica->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tecnica->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tecnica->tipo = $request->tipo;
        $tecnica->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('tecnicas',$idPaciente)->with('success','Tecnica modificada correctamente');
    }

    public function eliminarTecnicas($idPaciente, $num_tecnica)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todas las técnicas
        $tecnicas = Enfermedades::find($idEnfermedad)->Tecnicas_realizadas;
        $tecnica = $tecnicas[$num_tecnica];
        $tecnica->delete();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('tecnicas',$idPaciente)->with('success','Tecnica eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Otros tumores                                                 *
    *                                                                 *
    *******************************************************************/
    public function verOtrosTumoresSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verotrostumores',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('verotrostumores',['paciente' => $paciente]);
    }

    public function verOtrosTumores($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('otrostumores',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('otrostumores',['paciente' => $paciente]);
    }

    public function crearOtrosTumores(Request $request, $idPaciente)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;

        $tumor = new Otros_tumores();

        $tumor->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tumor->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tumor->tipo = $request->tipo;
        $tumor->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('otrostumores',$idPaciente)->with('success','Tumor creado correctamente');
    }

    public function modificarOtrosTumores(Request $request, $idPaciente, $num_otrostumores)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todos los tumores
        $tumores = Enfermedades::find($idEnfermedad)->Otros_tumores;
        $tumor = $tumores[$num_otrostumores];
        $tumor->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tumor->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tumor->tipo = $request->tipo;
        $tumor->save();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('otrostumores',$idPaciente)->with('success','Tumor modificado correctamente');
    }

    public function eliminarOtrosTumores($idPaciente, $num_otrostumores)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todos los tumores
        $tumores = Enfermedades::find($idEnfermedad)->Otros_tumores;
        $tumor = $tumores[$num_otrostumores];
        $tumor->delete();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('otrostumores',$idPaciente)->with('success','Tumor eliminado correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Biomarcadores                                                 *
    *                                                                 *
    *******************************************************************/
    public function verBiomarcadoresSinModificar($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        $enfermedad = Enfermedades::where('id_paciente',$idPaciente)->first();
        if($enfermedad == null)
            $biomarcadores = [];
        else
            $biomarcadores = $enfermedad->Biomarcadores;
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verbiomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores, 'nombre' => $nhcDesencriptado]);
        }

        return view('verbiomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores]);
    }

    public function verBiomarcadores($idPaciente)
    {
        $paciente = Pacientes::find($idPaciente);
        $enfermedad = Enfermedades::where('id_paciente',$idPaciente)->first();
        if($enfermedad == null)
            $biomarcadores = [];
        else
            $biomarcadores = $enfermedad->Biomarcadores;
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('biomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores, 'nombre' => $nhcDesencriptado]);
        }

        return view('biomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores]);
    }

    public function guardarBiomarcadores(Request $request, $idPaciente)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;

        $arrayBiomarcadores = ["NGS", "PDL1", "EGFR", "ALK","ROS1", "KRAS","BRAF", "HER2","NTRK", "FGFR1","RET", "MET", "Pl3K","TMB","Otros"];
        $numeroInputs= [2,2,1,2,1,2,2,1,2,1,1,1,1,1,1];
        $i = 0;
        foreach($arrayBiomarcadores as $biomarcador){
            if($request->$biomarcador == "on"){
                $nuevoBiomarcador = new Biomarcadores();
                $nuevoBiomarcador->id_enfermedad = $idEnfermedad;
                $nuevoBiomarcador->nombre = $biomarcador;
                if($request->{$biomarcador.'_tipo'} == "Otra")
                    $nuevoBiomarcador->tipo = 'Otro: '.$request->{$biomarcador.'_tipo_especificar'};
                else
                    $nuevoBiomarcador->tipo = $request->{$biomarcador.'_tipo'};
                if($biomarcador == "ALK"){
                    if($request->{$biomarcador.'_tipo'} == "Traslocado"){
                        if($request->{$biomarcador.'_subtipo'} == "Otra")
                            $nuevoBiomarcador->subtipo = 'Otro: '.$request->{$biomarcador.'_subtipo_especificar'};
                        else
                            $nuevoBiomarcador->subtipo = $request->{$biomarcador.'_subtipo'};
                    }
                }elseif($biomarcador == "KRAS" or $biomarcador == "BRAF"){
                  if($request->{$biomarcador.'_tipo'} == "Mutado"){
                        if($request->{$biomarcador.'_subtipo'} == "Otra")
                            $nuevoBiomarcador->subtipo = 'Otro: '.$request->{$biomarcador.'_subtipo_especificar'};
                        else
                            $nuevoBiomarcador->subtipo = $request->{$biomarcador.'_subtipo'};
                    }
                }else{
                    if($numeroInputs[$i] == 2){
                        if($request->{$biomarcador.'_subtipo'} == "Otra")
                            $nuevoBiomarcador->subtipo = 'Otro: '.$request->{$biomarcador.'_subtipo_especificar'};
                        else
                            $nuevoBiomarcador->subtipo = $request->{$biomarcador.'_subtipo'};
                    }
                }
                $nuevoBiomarcador->save();
            }
            $i = $i + 1;
        }

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('biomarcadores',$idPaciente)->with('success','Biomarcadores creados correctamente');
    }

    public function eliminarBiomarcadores($idPaciente,$num_biomarcador)
    {
        $idEnfermedad = Enfermedades::where('id_paciente',$idPaciente)->first()->id_enfermedad;
        //Obetenemos todos los biomarcadores
        $biomarcadores = Enfermedades::find($idEnfermedad)->Biomarcadores;
        $biomarcador = $biomarcadores[$num_biomarcador];
        $biomarcador->delete();

        $paciente = Pacientes::find($idPaciente);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('biomarcadores',$idPaciente)->with('success','Biomarcador eliminado correctamente');
    }
}