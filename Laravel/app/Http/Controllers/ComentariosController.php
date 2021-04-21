<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Pacientes;
use App\Models\Comentarios;

class ComentariosController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function actualizarfechaModificacionPaciente($paciente)
    {
        $paciente->ultima_modificacion = date("Y-m-d");
        $paciente->save();
    }

    public function verComentarioNuevo($id)
    {
    	$paciente = Pacientes::find($id);
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
    	$comentario = $comentarios[$num_comentario-1];
    	return view('comentarios',['paciente' => $paciente, 'comentario' => $comentario, 'posicion' => $num_comentario]);
    }

    public function modificarComentario(Request $request, $id, $num_comentario)
    {
    	$validator = $this->validarComentario($request);
    	if($validator->fails())
        	return back()->withErrors($validator->errors())->withInput();

    	$paciente = Pacientes::find($id);

    	$comentario = $paciente->Comentarios[$num_comentario-1];

    	$comentario->id_paciente = $id;
    	$comentario->comentario = $request->comentario;
    	$comentario->save();

        $this->actualizarfechaModificacionPaciente($paciente);

    	return redirect()->route('vermodificarcomentario',['id' => $id, 'num_comentario' => $num_comentario])->with('success','Comentario modificado correctamente');
    }

    public function eliminarComentario($id, $num_comentario)
    {
    	$paciente = Pacientes::find($id);

	    $comentario = $paciente->Comentarios[$num_comentario-1];
	    $comentario->delete();

        $this->actualizarfechaModificacionPaciente($paciente);

	   	return redirect()->route('comentarionuevo',$id)->with('success','Comentario eliminado correctamente');
    }
}
