<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use App\Models\Usuarios;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use App\Utilidades\Encriptacion;

class PacientesController extends Controller
{

    private $encriptacion;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();
    }

    //Metodo que retorna la vista pacientes
    public function verPacientes()
    {
        $todosPacientes = Pacientes::all();
        return view('pacientes',['pacientes' => $todosPacientes, 'encriptacion' => $this->encriptacion]);
    }

    //Metodo que retorna la vista crearpaciente
    public function verCrearNuevoPaciente()
    {
        return view('crearpaciente');
    }

    //Metodo que valida si los datos del nuevo usuario son validos
    public function validarNuevoPaciente($request)
    { 
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
        if(env('APP_ENV') == 'production'){
            $validator = Validator::make($request->all(), [
                'nhc' => 'required',
                'nacimiento' => 'date|before:'.$manana
            ],
            [
            'required' => 'El campo :attribute no puede estar vacio',
            'same' => 'Las dos contraseñas deben coincidir',
            'before' => 'Introduce una fecha valida',
            'date' => 'Introduce una fecha valida',
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'apellidos' => 'required',
                'nacimiento' => 'date|before:'.$manana
            ],
            [
            'required' => 'El campo :attribute no puede estar vacio',
            'same' => 'Las dos contraseñas deben coincidir',
            'before' => 'Introduce una fecha valida',
            'date' => 'Introduce una fecha valida',
            ]);
        }

        return $validator;
    }

    //Metodo que almacena el paciente recibido en la base de datos
    public function crearNuevoPaciente(Request $request)
    {
        try{
            $validator = $this->validarNuevoPaciente($request);
            if($validator->fails())
                return back()->withErrors($validator->errors())->withInput();
            //Creamos el objeto pacientes
            $nuevoPaciente = new Pacientes();
            //Encriptamos el nhc
            $nhcEncriptado = $this->encriptacion->encriptar($request->nhc);
            //Le asignamos los valores recibidos desde el metodo POST
            if(env('APP_ENV') == 'production'){
                $nuevoPaciente->nhc = $nhcEncriptado;
            }else{
                $nuevoPaciente->nombre = $request->nombre;
                $nuevoPaciente->apellidos = $request->apellidos;       
            }
            $nuevoPaciente->sexo = $request->sexo;
            $nuevoPaciente->nacimiento = $request->nacimiento;
            $nuevoPaciente->raza = $request->raza;
            if($request->profesion == "Otro")
                $nuevoPaciente->profesion = "Otro: ".$request->profesion_especificar;
            else
                $nuevoPaciente->profesion = $request->profesion;
            $nuevoPaciente->fumador = $request->fumador;  
            if($request->fumador == "Fumador" || $request->fumador == "Exfumador")
                $nuevoPaciente->num_tabaco_dia = $request->especificar_fumador;
            elseif($request->fumador == "Nunca fumador")
                $nuevoPaciente->num_tabaco_dia = 0;
            $nuevoPaciente->bebedor = $request->bebedor;
            if($request->carcinogenos == "Otro")
                $nuevoPaciente->carcinogenos = "Otro: ".$request->especificar_carcinogeno;
            else
                $nuevoPaciente->carcinogenos = $request->carcinogenos;
            $nuevoPaciente->ultima_modificacion = date("Y-m-d");
            //Añadimos el paciente a la base de datos
            $nuevoPaciente->save();

            return redirect()->route('pacientes');
        }catch(QueryException $e){
            return redirect()->route('nuevopaciente')->with('SQLerror','Introduce una fecha valida')->withInput();
        }
    }

    public function verEliminarPaciente()
    {
        $todosPacientes = Pacientes::all();
        return view('eliminarpaciente',['pacientes' => $todosPacientes, 'encriptacion' => $this->encriptacion]);      
    }


    public function eliminarPaciente($id)
    {
        $paciente = Pacientes::find($id);
        $paciente->delete();

        return redirect()->route('vereliminarpaciente');
    }
}
