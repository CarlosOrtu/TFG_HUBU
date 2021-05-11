<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use App\Models\Pacientes;
use App\Models\Tratamientos;
use App\Models\Intenciones;
use App\Models\Farmacos;
use App\Utilidades\Encriptacion;


class TratamientosController extends Controller
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
    *                                                                 *
    *   Radioterapia                                                  *
    *                                                                 *
    *******************************************************************/
    public function verRadioterapiaSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verradioterapia',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verradioterapia',['paciente' => $paciente]);
        }
    }

    public function verRadioterapia($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('radioterapia',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('radioterapia',['paciente' => $paciente]);
        }
    }

    public function validarRadioterapia($request)
    {
    	//Calculamos la fecha de mañana
    	$seg = time();
		$manana = strtotime("+1 day", $seg);
		$manana = date("Y-m-d", $manana);

        $mensajeError = [
            'gt' => 'La dosis debe ser un número positivo mayor que 0',
            'required' => 'El campo :attribute no puede estar vacio',
            'before' => 'Introduce una fecha valida',
            'after' => 'Fecha fin no puede ser anterior a fecha inicio',
            'date' => 'Introduce una fecha valida',
        ];
        if($request->fecha_inicio != null){
            $restricciones = [
                'dosis' => 'required|gt:0',
                'fecha_inicio' => 'required|date|before:'.$manana,
                'fecha_fin' => 'required|date|after:'.$request->fecha_inicio,
            ];
        }else{
            $restricciones = [
                'dosis' => 'required|gt:0',
                'fecha_inicio' => 'required|date|before:'.$manana,
                'fecha_fin' => 'required|date',
            ];
        }
        $validator = Validator::make($request->all(),$restricciones,$mensajeError);     

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
	    	$tratamiento = $tratamientos[$num_radioterapia];
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
    	$tratamiento = $tratamientos[$num_radioterapia];

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
    public function verCirugiaSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('vercirugia',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('vercirugia',['paciente' => $paciente]);
        }
    }

    public function verCirugia($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('cirugia',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('cirugia',['paciente' => $paciente]);
        }
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
	    	$tratamiento = $tratamientos[$num_cirugia];
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
	    $tratamiento = $tratamientos[$num_cirugia];

    	$tratamiento->delete();

    	$this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('cirugias',$id)->with('success','Radioterapia eliminada correctamente');
    }

    /******************************************************************
    *                                                                 *
    *   Quimioterapia                                                 *
    *                                                                 *
    *******************************************************************/
    public function verQuimioterapiaSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('verquimioterapia',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('verquimioterapia',['paciente' => $paciente]);
        }
    }

    public function verQuimioterapia($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('quimioterapia',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }else{
            return view('quimioterapia',['paciente' => $paciente]);
        }
    }

    public function validarQuimioterapia($request)
    {
    	//Calculamos la fecha de mañana
    	$seg = time();
		$manana = strtotime("+1 day", $seg);
		$manana = date("Y-m-d", $manana);
		$mensajeError = [
	        'gt' => 'El :attribute debe ser un número positivo mayor que 0',
	        'required' => 'El campo :attribute no puede estar vacio',
	        'before' => 'Introduce una fecha valida',
	        'after' => 'Fecha fin no puede ser anterior a fecha inicio',
	        'date' => 'Introduce una fecha valida',
	        ];
        if($request->primer_ciclo != null){
    	    $restricciones = [
    	            'num_ciclos' => 'required|gt:0',
    	            'primer_ciclo' => 'required|before:'.$manana,
    	            'ultimo_ciclo' => 'required|after:'.$request->primer_ciclo,
    	        ];
        }else{
            $restricciones = [
                    'num_ciclos' => 'required|gt:0',
                    'primer_ciclo' => 'required|before:'.$manana,
                    'ultimo_ciclo' => 'required',
                ];
        }
		$validator = Validator::make($request->all(),$restricciones,$mensajeError);		

        return $validator;
    }

    private function añadirTratamientoQuimioterapia(Request $request,$tratamiento,$id)
    {
    	$tratamiento->id_paciente = $id;
        $tratamiento->tipo = "Quimioterapia";   
        $tratamiento->subtipo = $request->intencion;
        $tratamiento->fecha_inicio = $request->primer_ciclo;
        $tratamiento->fecha_fin = $request->ultimo_ciclo;

        $tratamiento->save();

        return $tratamiento;
    }

    private function añadirIntencionQuimioterapia(Request $request,$intencion,$idTratamiento)
    {
    	$intencion->id_tratamiento = $idTratamiento;
        if($request->ensayo_clinico == "Si"){
	        $intencion->ensayo = $request->ensayo_clinico_tipo;
	        $intencion->ensayo_fase = $request->ensayo_clinico_fase;
	    }else{
	        $intencion->ensayo = null;
	        $intencion->ensayo_fase = null;	    	
	    }
        $intencion->tratamiento_acceso_expandido = $request->tratamiento_acceso;
        $intencion->tratamiento_fuera_indicacion = $request->tratamiento_fuera;
        $intencion->medicacion_extranjera = $request->medicacion_extranjera;
        $intencion->esquema = $request->esquema;
        if($request->administracion == "Otro")
			$intencion->modo_administracion = "Otro: ".$request->especificar_administracion;
        else
        	$intencion->modo_administracion = $request->administracion;
        if($request->tipo_farmaco == "Otro")
			$intencion->tipo_farmaco = "Otro: ".$request->especificar_tipo_farmaco;
        else
        	$intencion->tipo_farmaco = $request->tipo_farmaco;
        $intencion->numero_ciclos = $request->num_ciclos;

		$intencion->save();

		return $intencion;
    }

    private function añadirFarmacosQuimioterapia($farmacoModelo, $farmaco, $tipo, $idIntencion)
    {
        if($tipo != "Mal"){
        	$farmacoModelo->id_intencion = $idIntencion;
        	if($tipo == "Ninguno"){
    			$farmacoModelo->tipo = $farmaco;
    		}elseif($tipo == "Otro"){
    			$farmacoModelo->tipo = "Otro: ".$farmaco;
    		}else{
    			$farmacoModelo->tipo = "Ensayo: ".$farmaco;
    		}
    		$farmacoModelo->save();	
        }
    }

    public function crearQuimioterapia(Request $request, $id)
    {
        try{
       		$validator = $this->validarQuimioterapia($request);
    	    if($validator->fails())
    	        return back()->withErrors($validator->errors())->withInput();

        	$paciente = Pacientes::find($id);

    		$tratamiento = new Tratamientos();
    		$tratamiento = $this->añadirTratamientoQuimioterapia($request,$tratamiento,$id);

            $intencion = new Intenciones();
    		$intencion = $this->añadirIntencionQuimioterapia($request,$intencion,$tratamiento->id_tratamiento);        

    		if(isset($request->farmacos)){
    			foreach($request->farmacos as $farmaco){
    				$farmacoModelo = new Farmacos();
                    if($farmaco == "Otro" or $farmaco == "Farmaco en ensayo clínico")
                        $this->añadirFarmacosQuimioterapia($farmacoModelo,$farmaco,"Mal",$intencion->id_intencion);
                    else
    				    $this->añadirFarmacosQuimioterapia($farmacoModelo,$farmaco,"Ninguno",$intencion->id_intencion);
    			}	
    		}
    		if(isset($request->especificar_farmaco)){
    			foreach($request->especificar_farmaco as $farmaco){
    				$farmacoModelo = new Farmacos();
    				$this->añadirFarmacosQuimioterapia($farmacoModelo,$farmaco,"Otro",$intencion->id_intencion);		
    			}
    		}
    		if(isset($request->especificar_farmaco_ensayo)){
    			foreach($request->especificar_farmaco_ensayo as $farmaco){
    				$farmacoModelo = new Farmacos();
    				$this->añadirFarmacosQuimioterapia($farmacoModelo,$farmaco,"Ensayo",$intencion->id_intencion);			
    			}
    		}

    		$this->actualizarfechaModificacionPaciente($paciente);
    		return redirect()->route('quimioterapias',$id)->with('success','Quimioterapia creada correctamente');
        }catch(QueryException $e){
            return redirect()->route('quimioterapias',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function modificarQuimioterapia(Request $request, $id, $num_quimioterapia)
    {
        try{
       		$validator = $this->validarQuimioterapia($request);
    	    if($validator->fails())
    	        return back()->withErrors($validator->errors())->withInput();
    		
        	$paciente = Pacientes::find($id);

        	$tratamientos = Tratamientos::where('tipo','Quimioterapia')->where('id_paciente',$id)->get();
        	$tratamiento = $tratamientos[$num_quimioterapia];

        	$tratamiento = $this->añadirTratamientoQuimioterapia($request,$tratamiento,$id);

        	$intencion = $tratamiento->Intenciones;

    		$intencion = $this->añadirIntencionQuimioterapia($request,$intencion,$tratamiento->id_tratamiento);
    		
    		$farmacos = $intencion->Farmacos;
    		$i = 0;
    		foreach($farmacos as $farmacoModelo){
    			if($request->farmacos[$i] == "Otro"){
    				$this->añadirFarmacosQuimioterapia($farmacoModelo,$request->especificar_farmaco[$i],"Otro",$intencion->id_intencion);
    			}elseif($request->farmacos[$i] == "Farmaco en ensayo clínico"){
    				$this->añadirFarmacosQuimioterapia($farmacoModelo,$request->especificar_farmaco_ensayo[$i],"Ensayo",$intencion->id_intencion);
    			}else
    				$this->añadirFarmacosQuimioterapia($farmacoModelo,$request->farmacos[$i],"Ninguno",$intencion->id_intencion);
    			$i = $i + 1;
    		}	
    		$indiceOtro = $i;
    		$indiceEnsayo = $i;
    		if(isset($request->farmacos)){
    			for($i; $i < count($request->farmacos); $i++){
    				if($request->farmacos[$i] == "Otro"){
    					$farmacoModelo = new Farmacos();
    					$this->añadirFarmacosQuimioterapia($farmacoModelo,$request->especificar_farmaco[$indiceOtro],"Otro",$intencion->id_intencion);
    					$indiceOtro = $indiceOtro + 1;
    				}elseif($request->farmacos[$i] == "Farmaco en ensayo clínico"){
    					$farmacoModelo = new Farmacos();
    					$this->añadirFarmacosQuimioterapia($farmacoModelo,$request->especificar_farmaco_ensayo[$indiceEnsayo],"Ensayo",$intencion->id_intencion);
    					$indiceEnsayo = $indiceEnsayo + 1;
    				}else{
    					$farmacoModelo = new Farmacos();
    					$this->añadirFarmacosQuimioterapia($farmacoModelo,$request->farmacos[$i],"Ninguno",$intencion->id_intencion);
    				}
    			}
    		}

            $this->actualizarfechaModificacionPaciente($paciente);

            return redirect()->route('quimioterapias',$id)->with('success','Quimioterapia modificada correctamente');
        }catch(QueryException $e){
            return redirect()->route('quimioterapias',$id)->with('SQLerror','Introduce una fecha valida');
        }
    }

    public function eliminarQuimioterapia($id, $num_quimioterapia)
    {
    	$paciente = Pacientes::find($id);

	    $tratamientos = Tratamientos::where('tipo','Quimioterapia')->where('id_paciente',$id)->get();
	    $tratamiento = $tratamientos[$num_quimioterapia];

    	$tratamiento->delete();

    	$this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('quimioterapias',$id)->with('success','Quimioterapia eliminada correctamente');
    }

    /******************************************************************
    *                                                                 *
    *   Secuencia                                                     *
    *                                                                 *
    *******************************************************************/
    public function verSecuenciaTratamientos($id){
        $paciente = Pacientes::find($id);

        $quimioterapias = Tratamientos::where('tipo','Quimioterapia')->where('id_paciente',$id)->get();
        $radioterapias = Tratamientos::where('tipo','Radioterapia')->where('id_paciente',$id)->get();
        $cirugias = Tratamientos::where('tipo','Cirugia')->where('id_paciente',$id)->get();
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('secuenciatratamientos',['paciente' => $paciente, 'nombre' => $nhcDesencriptado, 'quimioterapias' => $quimioterapias, 'radioterapias' => $radioterapias, 'cirugias' => $cirugias]);
        }else{
            return view('secuenciatratamientos',['paciente' => $paciente, 'quimioterapias' => $quimioterapias, 'radioterapias' => $radioterapias, 'cirugias' => $cirugias]);
        }
    }
}
