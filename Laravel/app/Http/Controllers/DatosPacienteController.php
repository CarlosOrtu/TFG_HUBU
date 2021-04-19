<?php

namespace App\Http\Controllers;

use App\Models\Pacientes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class DatosPacienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function verPaciente($id)
    {
    	$paciente = Pacientes::find($id);
    	return view('datospaciente',['paciente' => $paciente]);
    }    

    public function validarDatosModificarPaciente($request)
    {
        $seg = time();
        $manana = strtotime("+1 day", $seg);
        $manana = date("Y-m-d", $manana);
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
            //Modificamos sus datos
            $paciente->nombre = $request->nombre;
            $paciente->apellidos = $request->apellidos;
            $paciente->sexo = $request->sexo;
            $paciente->nacimiento = $request->nacimiento;
            $paciente->raza = $request->raza;
            $paciente->profesion = $request->profesion;
            if($request->fumador != "Desconocido"){
                $paciente->fumador = $request->fumador;  
            }else{
                $paciente->fumador = null;  
            }
            if($request->bebedor != "Desconocido"){
                $paciente->bebedor = $request->bebedor;
            }else{
                $paciente->bebedor = null;  
            }
            if($request->carcinogenos != "Desconocido"){
                $paciente->carcinogenos = $request->carcinogenos;
            }else{
                $paciente->carcinogenos = null;  
            }
            //Guardamos los cambios en la base de datos a la base de datos
            $paciente->save();

            return redirect()->route('datospaciente',$id)->with('success','Paciente modificado correctamente');
        }catch(QueryException $e){
            return redirect()->route('datospaciente',$id)->with('SQLerror','Introduce una fecha valida');
        }
        
    }
}
