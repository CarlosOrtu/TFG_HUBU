<?php
 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\Pacientes;
use App\Models\Enfermedad;
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
    public function verDatosEnfermedadSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verdatosenfermedad',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verdatosenfermedad',['paciente' => $paciente]);
        }
    }

    public function verDatosEnfermedad($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('datosenfermedad',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('datosenfermedad',['paciente' => $paciente]);
        }
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
        }else{
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
        }
        return $validator;
    }

    public function guardarDatosEnfermedad(Request $request, $id)
    {
        try{
        	$validator = $this->validarDatosModificarEnfermedad($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();

            $enfermedad = Enfermedad::where('id_paciente',$id)->first();
        	if(empty($enfermedad))
        		$enfermedad = new Enfermedad();
        	$enfermedad->id_paciente = $id;
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

            $paciente = Pacientes::find($id);
            $this->actualizarfechaModificacionPaciente($paciente);


        	return redirect()->route('datosenfermedad',$id)->with('success','Datos enfermedad guardados correctamente');
        }catch(QueryException $e){
            return redirect()->route('datosenfermedad',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    /******************************************************************
    *																  *
    *	Datos sintoma											      *
    *																  *
  	*******************************************************************/
    public function verDatosSintomasSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verdatossintomas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verdatossintomas',['paciente' => $paciente]);    
        }    
    }

    public function verDatosSintomas($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('datossintomas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('datossintomas',['paciente' => $paciente]);    
        }
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
        }else{
            $validator = Validator::make($request->all(),[]);
        }

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

    public function crearDatosSintomas(Request $request, $id)
    {
        try{
            $validator = $this->validarDatosSintomas($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            
            $sintoma = new Sintomas();

            $enfermedad = Pacientes::find($id)->Enfermedad;
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
            elseif($request->tipo == "Otro")
                $sintoma->tipo = "Otro: ".$request->tipo_especificar;
            else
                $sintoma->tipo = $request->tipo;
            $sintoma->fecha_inicio = $fecha_sintomas;    
            $sintoma->save();

            $paciente = Pacientes::find($id);
            $this->actualizarfechaModificacionPaciente($paciente);

            return redirect()->route('datossintomas',$id)->with('success','Sintoma creado correctamente');
        }catch(QueryException $e){
            return redirect()->route('datossintomas',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function modificarDatosSintomas(Request $request, $id, $num_sintoma)
    {
        $enfermedad = Pacientes::find($id)->Enfermedad;
        $idEnfermedad =$enfermedad->id_enfermedad;
        
        $fecha_sintomas = $enfermedad->Sintomas[0]->fecha_inicio;

        //Obetenemos todos los sintomas
        $sintomas = Enfermedad::find($idEnfermedad)->Sintomas;
    	$sintoma = $sintomas[$num_sintoma];
    	$sintoma->id_enfermedad = $idEnfermedad;
    	if($request->tipo == "Dolor otra localización")
    		$sintoma->tipo = "Localización: ".$request->tipo_especificar_localizacion;
    	elseif($request->tipo == "Otro")
    		$sintoma->tipo = "Otro: ".$request->tipo_especificar;
    	else
    		$sintoma->tipo = $request->tipo;
    	$sintoma->fecha_inicio = $fecha_sintomas;	
    	$sintoma->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('datossintomas',$id)->with('success','Sintoma modificado correctamente');
    }

    public function eliminarSintoma($id, $num_sintoma)
    {
    	$idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los sintomas
        $sintomas = Enfermedad::find($idEnfermedad)->Sintomas;
        $sintoma = $sintomas[$num_sintoma];
  		$sintoma->delete();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

  		return redirect()->route('datossintomas',$id)->with('success','Sintoma eliminado correctamente');
    }

    public function modificarFechaSintomas(Request $request, $id)
    {
        try{
            $validator = $this->validarDatosFecha($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
            //Obetenemos todos los sintomas
            $sintomas = Enfermedad::find($idEnfermedad)->Sintomas;

            foreach($sintomas as $sintoma){
                $sintoma->fecha_inicio = $request->fecha_inicio;
                $sintoma->save();
            }
            return redirect()->route('datossintomas',$id)->with('success','Fecha modificada correctamente');
        }catch(QueryException $e){
            return redirect()->route('datossintomas',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    /******************************************************************
    *																  *
    *	Metastasis											          *
    *																  *
  	*******************************************************************/
    public function verMetastasisSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('vermetastasis',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('vermetastasis',['paciente' => $paciente]);
        }
    }

   	public function verMetastasis($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('metastasis',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('metastasis',['paciente' => $paciente]);
        }
    }


    public function crearMetastasis(Request $request, $id)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $metastasis = new Metastasis();

        $metastasis->id_enfermedad = $idEnfermedad;
        if($request->localizacion == "Otro")
            $metastasis->tipo = "Otro: ".$request->localizacion_especificar;
        else
            $metastasis->tipo = $request->localizacion;
        $metastasis->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('metastasis',$id)->with('success','Metastasis creada correctamente');
    }

    public function modificarMetastasis(Request $request, $id, $num_metastasis)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las metastasis
        $metastasis = Enfermedad::find($idEnfermedad)->Metastasis;
    	$metastasis = $metastasis[$num_metastasis];
    	$metastasis->id_enfermedad = $idEnfermedad;
    	if($request->localizacion == "Otro")
    		$metastasis->tipo = "Otro: ".$request->localizacion_especificar;
    	else
    		$metastasis->tipo = $request->localizacion;
    	$metastasis->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('metastasis',$id)->with('success','Metastasis modificada correctamente');
    }

    public function eliminarMetastasis($id, $num_metastasis)
    {
    	$idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las metastasis
        $metastasis = Enfermedad::find($idEnfermedad)->Metastasis;
        $metastasis = $metastasis[$num_metastasis];
  		$metastasis->delete();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);
  		return redirect()->route('metastasis',$id)->with('success','Metastasis eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Pruebas                                                       *
    *                                                                 *
    *******************************************************************/
    public function verPruebasSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verpruebas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verpruebas',['paciente' => $paciente]);
        } 
    }
    public function verPruebas($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('pruebas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('pruebas',['paciente' => $paciente]);
        }
    }


    public function crearPruebas(Request $request, $id)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $prueba = new Pruebas_realizadas();

        $prueba->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $prueba->tipo = "Otro: ".$request->tipo_especificar;
        else
            $prueba->tipo = $request->tipo;
        $prueba->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('pruebas',$id)->with('success','Prueba creada correctamente');
    }

    public function modificarPruebas(Request $request, $id, $num_prueba)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las pruebas
        $pruebas = Enfermedad::find($idEnfermedad)->Pruebas_realizadas;
        $prueba = $pruebas[$num_prueba];
        $prueba->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $prueba->tipo = "Otro: ".$request->tipo_especificar;
        else
            $prueba->tipo = $request->tipo;
        $prueba->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('pruebas',$id)->with('success','Prueba modificada correctamente');
    }

    public function eliminarPruebas($id, $num_prueba)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las pruebas
        $pruebas = Enfermedad::find($idEnfermedad)->Pruebas_realizadas;
        $prueba = $pruebas[$num_prueba];
        $prueba->delete();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('pruebas',$id)->with('success','Prueba eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Técnicas                                                       *
    *                                                                 *
    *******************************************************************/
    public function verTecnicasSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('vertecnicas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('vertecnicas',['paciente' => $paciente]);
        }
    }

    public function verTecnicas($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('tecnicas',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('tecnicas',['paciente' => $paciente]);
        }
    }


    public function crearTecnicas(Request $request, $id)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $tecnica = new Tecnicas_realizadas();

        $tecnica->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tecnica->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tecnica->tipo = $request->tipo;
        $tecnica->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('tecnicas',$id)->with('success','Tecnica creada correctamente');
    }

    public function modificarTecnicas(Request $request, $id, $num_tecnica)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las técnicas
        $tecnicas = Enfermedad::find($idEnfermedad)->Tecnicas_realizadas;
        $tecnica = $tecnicas[$num_tecnica];
        $tecnica->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tecnica->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tecnica->tipo = $request->tipo;
        $tecnica->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('tecnicas',$id)->with('success','Tecnica modificada correctamente');
    }

    public function eliminarTecnicas($id, $num_tecnica)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las técnicas
        $tecnicas = Enfermedad::find($idEnfermedad)->Tecnicas_realizadas;
        $tecnica = $tecnicas[$num_tecnica];
        $tecnica->delete();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('tecnicas',$id)->with('success','Tecnica eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Otros tumores                                                 *
    *                                                                 *
    *******************************************************************/
    public function verOtrosTumoresSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verotrostumores',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verotrostumores',['paciente' => $paciente]);
        }
    }

    public function verOtrosTumores($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('otrostumores',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('otrostumores',['paciente' => $paciente]);
        }
    }

    public function crearOtrosTumores(Request $request, $id)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $tumor = new Otros_tumores();

        $tumor->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tumor->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tumor->tipo = $request->tipo;
        $tumor->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('otrostumores',$id)->with('success','Tumor creado correctamente');
    }

    public function modificarOtrosTumores(Request $request, $id, $num_otrostumores)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los tumores
        $tumores = Enfermedad::find($idEnfermedad)->Otros_tumores;
        $tumor = $tumores[$num_otrostumores];
        $tumor->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tumor->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tumor->tipo = $request->tipo;
        $tumor->save();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('otrostumores',$id)->with('success','Tumor modificado correctamente');
    }

    public function eliminarOtrosTumores($id, $num_otrostumores)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los tumores
        $tumores = Enfermedad::find($idEnfermedad)->Otros_tumores;
        $tumor = $tumores[$num_otrostumores];
        $tumor->delete();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('otrostumores',$id)->with('success','Tumor eliminado correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Biomarcadores                                                 *
    *                                                                 *
    *******************************************************************/
    public function verBiomarcadoresSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        $enfermedad = Enfermedad::where('id_paciente',$id)->first();
        if($enfermedad == null)
            $biomarcadores = [];
        else
            $biomarcadores = $enfermedad->Biomarcadores;
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verbiomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verbiomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores]);
        }
    }

    public function verBiomarcadores($id)
    {
        $paciente = Pacientes::find($id);
        $enfermedad = Enfermedad::where('id_paciente',$id)->first();
        if($enfermedad == null)
            $biomarcadores = [];
        else
            $biomarcadores = $enfermedad->Biomarcadores;
        if(env('APP_ENV') == 'production'){         
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('biomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('biomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores]);
        }
    }

    public function guardarBiomarcadores(Request $request, $id)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

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
                }else if($biomarcador == "KRAS" or $biomarcador == "BRAF"){
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

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('biomarcadores',$id)->with('success','Biomarcadores creados correctamente');
    }

    public function eliminarBiomarcadores($id,$num_biomarcador)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los biomarcadores
        $biomarcadores = Enfermedad::find($idEnfermedad)->Biomarcadores;
        $biomarcador = $biomarcadores[$num_biomarcador];
        $biomarcador->delete();

        $paciente = Pacientes::find($id);
        $this->actualizarfechaModificacionPaciente($paciente);

        return redirect()->route('biomarcadores',$id)->with('success','Biomarcador eliminado correctamente');
    }
}