<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pacientes;
use Illuminate\Support\Facades\Validator;


class PacientesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Metodo que retorna la vista pacientes
    public function verPacientes()
    {
        $todosPacientes = Pacientes::all();
        return view('pacientes',['pacientes' => $todosPacientes]);
    }

    //Metodo que retorna la vista crearpaciente
    public function verCrearNuevoPaciente()
    {
        return view('crearpaciente');
    }

    //Metodo que valida si los datos del nuevo usuario son validos
    public function validarNuevoPaciente($request)
    { 
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'nacimiento' => 'date|before:'.date('Y-m-d')
        ],
        [
        'required' => 'El campo :attribute no puede estar vacio',
        'same' => 'Las dos contraseñas deben coincidir',
        'before' => 'Introduce una fecha valida',
        'date' => 'Introduce una fecha valida',
        ]);

        return $validator;
    }

    //Metodo que almacena el paciente recibido en la base de datos
    public function crearNuevoPaciente(Request $request)
    {
        $validator = $this->validarNuevoPaciente($request);
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        //Creamos el objeto pacientes
        $nuevoPaciente = new Pacientes();
        //Le asignamos los valores recibidos desde el metodo POST
        $nuevoPaciente->nombre = $request->nombre;
        $nuevoPaciente->apellidos = $request->apellidos;
        $nuevoPaciente->sexo = $request->sexo;
        $nuevoPaciente->nacimiento = $request->nacimiento;
        $nuevoPaciente->raza = $request->raza;
        $nuevoPaciente->profesion = $request->profesion;
        if($request->fumador != "desconocido")
            $nuevoPaciente->fumador = $request->fumador;  
        if($request->bebedor != "desconocido")
            $nuevoPaciente->bebedor = $request->bebedor;
        if($request->carcinogenos != "desconocido")
            $nuevoPaciente->carcinogenos = $request->carcinogenos;
        //Añadimos el paciente a la base de datos
        $nuevoPaciente->save();

        return redirect()->route('pacientes');
    }


}
