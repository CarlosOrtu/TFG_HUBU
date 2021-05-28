<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Comentarios;
use App\Utilidades\Encriptacion;

class ComentariosController extends Controller
{
    private $encriptacion;

	public function __construct()
    {
        $this->middleware('auth');
        $this->encriptacion = new Encriptacion();
    }

    public function verComentarioSinModificar($id)
    {
        $paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
            return view('vercomentarios',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('vercomentarios',['paciente' => $paciente]);
    }

    public function actualizarfechaModificacionPaciente($paciente)
    {
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save();
    }

    public function verComentarioNuevo($id)
    {
    	$paciente = Pacientes::find($id);
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->NHC);
        	return view('comentariosnuevos',['paciente' => $paciente, 'nombre' => $nhcDesencriptado]);
        }

        return view('comentariosnuevos',['paciente' => $paciente]);
    }

    public function validarComentario($request)
    {
		$validator = Validator::make($request->all(), [
            'comentario' => 'required',
        ],
        [
        	'required' => 'El campo :attribute no puede estar vacio',
        ]);	
		
        return $validator;
    }

    public function crearComentario(Request $request, $id)
    {
	    $validator = $this->validarComentario($request);
    	if($validator->fails())
        	return back()->withErrors($validator->errors())->withInput();

        $paciente = Pacientes::find($id);

    	$nuevoComentario = new Comentarios();
    	$nuevoComentario->id_paciente = $id;
    	$nuevoComentario->comentario = $request->comentario;
    	$nuevoComentario->save();

        $this->actualizarfechaModificacionPaciente($paciente);


    	return redirect()->route('comentarionuevo',$id)->with('success','Comentario creado correctamente');
    }

    public function verComentario($id, $num_comentario)
    {
    	$paciente = Pacientes::find($id);
    	$comentarios = $paciente->Comentarios;
    	$comentario = $comentarios[$num_comentario];
        if(env('APP_ENV') == 'production'){      
            $nhcDesencriptado = $this->encriptacion->desencriptar($paciente->nhc);
        	return view('comentarios',['paciente' => $paciente, 'comentario' => $comentario, 'posicion' => $num_comentario, 'nombre' => $nhcDesencriptado]);
        }

        return view('comentarios',['paciente' => $paciente, 'comentario' => $comentario, 'posicion' => $num_comentario]);
    }

    public function modificarComentario(Request $request, $id, $num_comentario)
    {
    	$validator = $this->validarComentario($request);
    	if($validator->fails())
        	return back()->withErrors($validator->errors())->withInput();

    	$paciente = Pacientes::find($id);

    	$comentario = $paciente->Comentarios[$num_comentario];

    	$comentario->id_paciente = $id;
    	$comentario->comentario = $request->comentario;
    	$comentario->save();

        $this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('vermodificarcomentario',['id' => $id, 'num_comentario' => $num_comentario])->with('success','Comentario modificado correctamente');
    }

    public function eliminarComentario($id, $num_comentario)
    {
    	$paciente = Pacientes::find($id);

	    $comentario = $paciente->Comentarios[$num_comentario];
	    $comentario->delete();

        $this->actualizarfechaModificacionPaciente($paciente);

	   	return redirect()->route('comentarionuevo',$id)->with('success','Comentario eliminado correctamente');
    }
}
