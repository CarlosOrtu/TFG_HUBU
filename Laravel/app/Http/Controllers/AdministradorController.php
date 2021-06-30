<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Validator;
use App\Rules\CorreoUnico;
use Auth;

class AdministradorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('admin');
    }

    //Metodo que retorna la vista usuarios
    public function verUsuarios()
    {
        $todosUsuarios = Usuarios::all();
        return view('usuarios',['usuarios' => $todosUsuarios]);
    }

    public function verCrearNuevoUsuario()
    {
        return view('crearusuario');
    }

    public function validarDatosCrearUsuario($request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'correo' => ['required','email', new CorreoUnico()],
            'contrasena' => 'required|same:contrasena_repetir',
            'contrasena_repetir' => 'required'
        ],
        [
        'required' => 'El campo :attribute no puede estar vacio',
        'same' => 'Las dos contraseñas deben coincidir',
        'email' => 'Debe de ser una dirección de correo valida'
        ]);

        return $validator;
    }

    public function validarDatosModificarUsuario($request, $idPaciente)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'correo' => ['required','email', new CorreoUnico($idPaciente)],
        ],
        [
        'required' => 'El campo :attribute no puede estar vacio',
        'email' => 'Debe de ser una dirección de correo valida'
        ]);

        return $validator;
    }

    public function crearNuevoUsuario(Request $request)
    {
        $validator = $this->validarDatosCrearUsuario($request);
        //Comprobamos si hay algun error de validacion
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        //Creamos el objeto usuario
        $nuevoUsuario = new Usuarios();
        //Le asignamos los valores recibidos desde el metodo POST
        $nuevoUsuario->nombre = $request->nombre;
        $nuevoUsuario->apellidos = $request->apellidos;
        $nuevoUsuario->email = $request->correo;
        $nuevoUsuario->contrasena = bcrypt($request->contrasena);
        $nuevoUsuario->id_rol = $request->rol; 
        $nuevoUsuario->save();
        return redirect()->route('usuarios');
    }

    public function eliminarUsuario($idPaciente)
    {
        $usuarioEliminar = Usuarios::find($idPaciente);
        if($idPaciente == 1)
            return back()->with('errorAdmin','No se puede eliminar el administrador');
        $usuarioEliminar->delete();
        return redirect()->route('usuarios');
    }

    public function verModificarUsuario($idPaciente){
        $usuarioModificar = Usuarios::find($idPaciente);
        return view('modificarusuario',['usuario' => $usuarioModificar]);
    }

    public function modificarUsuario(Request $request,$idPaciente){
        $validator = $this->validarDatosModificarUsuario($request,$idPaciente);
        if($idPaciente == 1 && $request->rol == 2)
            return back()->with('errorAdmin','No se puede quitar el rol de Administrador a este usuario');
        //Comprobamos que las contraseñas coicidan
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();    

        $usuarioModificar = Usuarios::find($idPaciente);
        $usuarioModificar->nombre = $request->nombre;
        $usuarioModificar->apellidos = $request->apellidos;
        $usuarioModificar->email = $request->correo;
        $usuarioModificar->id_rol = $request->rol;
        $usuarioModificar->save();

        return redirect()->route('usuarios');
    }
}
