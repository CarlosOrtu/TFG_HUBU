<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Utilidades\Encriptacion;


class DatosPacienteController extends Controller
{
    private $encriptacion;

    public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();
    }

    public function verPacienteSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
           return view('verdatospaciente',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('verdatospaciente',['paciente' => $paciente]);
    }

    public function verPaciente($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
    	   return view('datospaciente',['paciente' => $paciente, 'nhc' => $nhcDesencriptado]);
        }

        return view('datospaciente',['paciente' => $paciente]);
    }    

    public function validarDatosModificarPaciente($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        if(env('APP_ENV') == 'production'){
            $validator = Validator::make($request->all(), [
                    'nhc' => 'required',
                    'nacimiento' => 'required|date|before:'.$manana
                ],
                [
                    'required' => 'El campo :attribute no puede estar vacio',
                    'before' => 'Introduce una fecha valida',
                    'date' => 'Introduce una fecha valida',
                ]);

            return $validator;
        }
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'nacimiento' => 'required|date|before:'.$manana
        ],
        [
        	'required' => 'El campo :attribute no puede estar vacio',
        	'before' => 'Introduce una fecha valida',
            'date' => 'Introduce una fecha valida',
        ]);

        return $validator;
    }

    public function cambiarDatosPaciente(Request $request, $id)
    {
        try{
        	$validator = $this->validarDatosModificarPaciente($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            //Obtenemos el paciente actual
            $paciente = Pacientes::find($id);
            //Encriptamos el nhc
            $nhcEncriptado = $this->encriptacion->encriptar($request->nhc);
            //Modificamos sus datos
            if(env('APP_ENV') == 'production'){
                $paciente->NHC = $nhcEncriptado;
            }else{
                $paciente->nombre = $request->nombre;
                $paciente->apellidos = $request->apellidos;       
            }
            $paciente->sexo = $request->sexo;
            $paciente->nacimiento = $request->nacimiento;
            $paciente->raza = $request->raza;
            if($request->profesion == "Otro")
                $paciente->profesion = "Otro: ".$request->profesion_especificar;
            else
                $paciente->profesion = $request->profesion;
            $paciente->fumador = $request->fumador;  
            if($request->fumador == "Fumador" || $request->fumador == "Exfumador")
                $paciente->num_tabaco_dia = $request->especificar_fumador;
            if($request->fumador == "Nunca fumador")
                $paciente->num_tabaco_dia = 0;
            else
                $paciente->num_tabaco_dia = null;
            $paciente->bebedor = $request->bebedor;
            if($request->carcinogenos == "Otro")
                $paciente->carcinogenos = "Otro: ".$request->especificar_carcinogeno;
            else
                $paciente->carcinogenos = $request->carcinogenos;
            $paciente->ultima_modificacion = date("Y-m-d");
            //Guardamos los cambios en la base de datos a la base de datos
            $paciente->save();

            return redirect()->route('datospaciente',$id)->with('success','Paciente modificado correctamente');
        }catch(QueryException $e){
            return redirect()->route('datospaciente',$id)->with('SQLerror','Introduce una fecha valida');
        }
        
    }
}
