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


class EnfermedadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /******************************************************************
    *																  *
    *	Datos enfermedad											  *
    *																  *
  	*******************************************************************/
    public function verDatosEnfermedad($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('datosenfermedad',['paciente' => $paciente]);
    }

    public function validarDatosModificarEnfermedad($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        if($request->histologia_subtipo != "Otro"){
            $validator = Validator::make($request->all(), [
                'fecha_primera_consulta' => 'required|date|before:'$manana,
                'fecha_diagnostico' => 'required|date|before:'$manana,
                'T_tamano' => 'required|gt:0',
            ],
            [
            	'required' => 'El campo :attribute no puede estar vacio',
            	'before' => 'Introduce una fecha valida',
            	'gt' => 'El valor ha de ser mayor que 0',
                'date' => 'Introduce una fecha valida',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'fecha_primera_consulta' => 'required|date|before:'$manana,
                'fecha_diagnostico' => 'required|date|before:'$manana,
                'T_tamano' => 'required|gt:0',
                'histologia_subtipo_especificar' => 'required'
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
                'before' => 'Introduce una fecha valida',
                'gt' => 'El valor ha de ser mayor que 0',
                'date' => 'Introduce una fecha valida',
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
        		$enfermedad->histologia_subtipo = $request->histologia_subtipo_especificar;
        	else
        		$enfermedad->histologia_subtipo = $request->histologia_subtipo;
        	$enfermedad->histologia_grado = $request->fecha_primera_consulta;

        	$enfermedad->save();

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

    public function verDatosSintomas($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('datossintomas',['paciente' => $paciente]);
    }

    public function validarDatosSintomas($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        if($request->tipo != "Otro" and $request->tipo != "Dolor otra localización"){
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'required|before:'$manana,
            ],
            [
            	'required' => 'El campo :attribute no puede estar vacio',
            	'before' => 'Introduce una fecha valida',
            ]);
        }else if($request->tipo == "Dolor otra localización"){
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'required|before:'$manana,
                'tipo_especificar_localizacion' => 'required|date',
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
                'before' => 'Introduce una fecha valida',
                'date' => 'Introduce una fecha valida',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'fecha_inicio' => 'required|date|before:'$manana,
                'tipo_especificar' => 'required',
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
                'before' => 'Introduce una fecha valida',
                'date' => 'Introduce una fecha valida',
            ]);
        }

        return $validator;
    }

    public function crearDatosSintomas(Request $request, $id)
    {
        try{
            $validator = $this->validarDatosSintomas($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            $sintoma = new Sintomas();

            $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

            $sintoma->id_enfermedad = $idEnfermedad;
            if($request->tipo == "Dolor otra localización")
                $sintoma->tipo = "Localización: ".$request->tipo_especificar_localizacion;
            elseif($request->tipo == "Otro")
                $sintoma->tipo = "Otro: ".$request->tipo_especificar;
            else
                $sintoma->tipo = $request->tipo;
            $sintoma->fecha_inicio = $request->fecha_inicio;    
            $sintoma->save();

            return redirect()->route('datossintomas',$id)->with('success','Sintoma creado correctamente');
        }catch(QueryException $e){
            return redirect()->route('datossintomas',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function modificarDatosSintomas(Request $request, $id, $num_sintoma)
    {
        try{
           	$validator = $this->validarDatosSintomas($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
            //Obetenemos todos los sintomas
            $sintomas = Enfermedad::find($idEnfermedad)->Sintomas;
        	$sintoma = $sintomas[$num_sintoma-1];
        	$sintoma->id_enfermedad = $idEnfermedad;
        	if($request->tipo == "Dolor otra localización")
        		$sintoma->tipo = "Localización: ".$request->tipo_especificar_localizacion;
        	elseif($request->tipo == "Otro")
        		$sintoma->tipo = "Otro: ".$request->tipo_especificar;
        	else
        		$sintoma->tipo = $request->tipo;
        	$sintoma->fecha_inicio = $request->fecha_inicio;	
        	$sintoma->save();

        	return redirect()->route('datossintomas',$id)->with('success','Sintoma modificado correctamente');
        }catch(QueryException $e){
            return redirect()->route('datossintomas',$id)->with('SQLerror','Introduce una fecha valida 2');
        }
    }

    public function eliminarSintoma($id, $num_sintoma)
    {
    	$idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los sintomas
        $sintomas = Enfermedad::find($idEnfermedad)->Sintomas;
        $sintoma = $sintomas[$num_sintoma-1];
  		$sintoma->delete();

  		return redirect()->route('datossintomas',$id)->with('success','Sintoma eliminado correctamente');
    }

    /******************************************************************
    *																  *
    *	Metastasis											          *
    *																  *
  	*******************************************************************/
   	public function verMetastasis($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('metastasis',['paciente' => $paciente]);
    }

    public function validarDatosMetastasis($request)
    {
        if($request->localizacion == "Otro"){
            $validator = Validator::make($request->all(), [
                'localizacion_especificar' => 'required',
            ],
            [
                'required' => 'El campo :attribute no puede estar vacio',
            ]);
        }else{
            $validator = Validator::make($request->all(),[]);
        }

        return $validator;
    }

    public function crearMetastasis(Request $request, $id)
    {
        $validator = $this->validarDatosMetastasis($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $metastasis = new Metastasis();

        $metastasis->id_enfermedad = $idEnfermedad;
        if($request->localizacion == "Otro")
            $metastasis->localizacion = "Otro: ".$request->localizacion_especificar;
        else
            $metastasis->localizacion = $request->localizacion;
        $metastasis->save();

        return redirect()->route('metastasis',$id)->with('success','Metastasis creada correctamente');
    }

    public function modificarMetastasis(Request $request, $id, $num_metastasis)
    {
        $validator = $this->validarDatosMetastasis($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las metastasis
        $metastasis = Enfermedad::find($idEnfermedad)->Metastasis;
    	$metastasis = $metastasis[$num_metastasis-1];
    	$metastasis->id_enfermedad = $idEnfermedad;
    	if($request->localizacion == "Otro")
    		$metastasis->localizacion = "Otro: ".$request->localizacion_especificar;
    	else
    		$metastasis->localizacion = $request->localizacion;
    	$metastasis->save();

    	return redirect()->route('metastasis',$id)->with('success','Metastasis modificada correctamente');
    }

    public function eliminarMetastasis($id, $num_metastasis)
    {
    	$idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las metastasis
        $metastasis = Enfermedad::find($idEnfermedad)->Metastasis;
        $metastasis = $metastasis[$num_metastasis-1];
  		$metastasis->delete();

  		return redirect()->route('metastasis',$id)->with('success','Metastasis eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Pruebas                                                       *
    *                                                                 *
    *******************************************************************/
    public function verPruebas($id)
    {
        $paciente = Pacientes::find($id);
        return view('pruebas',['paciente' => $paciente]);
    }

    public function validarDatosPruebas($request)
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

    public function crearPruebas(Request $request, $id)
    {
        $validator = $this->validarDatosPruebas($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $prueba = new Pruebas_realizadas();

        $prueba->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $prueba->tipo = "Otro: ".$request->tipo_especificar;
        else
            $prueba->tipo = $request->tipo;
        $prueba->save();

        return redirect()->route('pruebas',$id)->with('success','Prueba creada correctamente');
    }

    public function modificarPruebas(Request $request, $id, $num_prueba)
    {
        $validator = $this->validarDatosPruebas($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las pruebas
        $pruebas = Enfermedad::find($idEnfermedad)->Pruebas_realizadas;
        $prueba = $pruebas[$num_prueba-1];
        $prueba->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $prueba->tipo = "Otro: ".$request->tipo_especificar;
        else
            $prueba->tipo = $request->tipo;
        $prueba->save();

        return redirect()->route('pruebas',$id)->with('success','Prueba modificada correctamente');
    }

    public function eliminarPruebas($id, $num_prueba)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las pruebas
        $pruebas = Enfermedad::find($idEnfermedad)->Pruebas_realizadas;
        $prueba = $pruebas[$num_prueba-1];
        $prueba->delete();

        return redirect()->route('pruebas',$id)->with('success','Prueba eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Técnicas                                                       *
    *                                                                 *
    *******************************************************************/
    public function verTecnicas($id)
    {
        $paciente = Pacientes::find($id);
        return view('tecnicas',['paciente' => $paciente]);
    }

    public function validarDatosTecnicas($request)
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

    public function crearTecnicas(Request $request, $id)
    {
        $validator = $this->validarDatosTecnicas($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $tecnica = new Tecnicas_realizadas();

        $tecnica->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tecnica->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tecnica->tipo = $request->tipo;
        $tecnica->save();

        return redirect()->route('tecnicas',$id)->with('success','Tecnica creada correctamente');
    }

    public function modificarTecnicas(Request $request, $id, $num_tecnica)
    {
        $validator = $this->validarDatosTecnicas($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las técnicas
        $tecnicas = Enfermedad::find($idEnfermedad)->Tecnicas_realizadas;
        $tecnica = $tecnicas[$num_tecnica-1];
        $tecnica->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tecnica->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tecnica->tipo = $request->tipo;
        $tecnica->save();

        return redirect()->route('tecnicas',$id)->with('success','Tecnica modificada correctamente');
    }

    public function eliminarTecnicas($id, $num_tecnica)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todas las técnicas
        $tecnicas = Enfermedad::find($idEnfermedad)->Tecnicas_realizadas;
        $tecnica = $tecnicas[$num_tecnica-1];
        $tecnica->delete();

        return redirect()->route('tecnicas',$id)->with('success','Tecnica eliminada correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Otros tumores                                                 *
    *                                                                 *
    *******************************************************************/
    public function verOtrosTumores($id)
    {
        $paciente = Pacientes::find($id);
        return view('otrostumores',['paciente' => $paciente]);
    }

    public function validarDatosOtrosTumores($request)
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

    public function crearOtrosTumores(Request $request, $id)
    {
        $validator = $this->validarDatosOtrosTumores($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;

        $tumor = new Otros_tumores();

        $tumor->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tumor->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tumor->tipo = $request->tipo;
        $tumor->save();

        return redirect()->route('otrostumores',$id)->with('success','Tumor creado correctamente');
    }

    public function modificarOtrosTumores(Request $request, $id, $num_otrostumores)
    {
        $validator = $this->validarDatosOtrosTumores($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los tumores
        $tumores = Enfermedad::find($idEnfermedad)->Otros_tumores;
        $tumor = $tumores[$num_otrostumores-1];
        $tumor->id_enfermedad = $idEnfermedad;
        if($request->tipo == "Otro")
            $tumor->tipo = "Otro: ".$request->tipo_especificar;
        else
            $tumor->tipo = $request->tipo;
        $tumor->save();

        return redirect()->route('otrostumores',$id)->with('success','Tumor modificado correctamente');
    }

    public function eliminarOtrosTumores($id, $num_otrostumores)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los tumores
        $tumores = Enfermedad::find($idEnfermedad)->Otros_tumores;
        $tumor = $tumores[$num_otrostumores-1];
        $tumor->delete();

        return redirect()->route('otrostumores',$id)->with('success','Tumor eliminado correctamente');
    }
    /******************************************************************
    *                                                                 *
    *   Biomarcadores                                                 *
    *                                                                 *
    *******************************************************************/
    public function verBiomarcadores($id)
    {
        $paciente = Pacientes::find($id);
        $biomarcadores = Enfermedad::where('id_paciente',$id)->first()->Biomarcadores;
        return view('biomarcadores',['paciente' => $paciente, 'biomarcadores' => $biomarcadores]);
    }


    //HACER
    public function validarDatosBiomarcadores($request)
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
        return redirect()->route('biomarcadores',$id)->with('success','Biomarcadores creados correctamente');
    }

    public function eliminarBiomarcadores($id,$num_biomarcador)
    {
        $idEnfermedad = Enfermedad::where('id_paciente',$id)->first()->id_enfermedad;
        //Obetenemos todos los biomarcadores
        $biomarcadores = Enfermedad::find($idEnfermedad)->Biomarcadores;
        $biomarcador = $biomarcadores[$num_biomarcador];
        $biomarcador->delete();

        return redirect()->route('biomarcadores',$id)->with('success','Biomarcador eliminado correctamente');
    }
}