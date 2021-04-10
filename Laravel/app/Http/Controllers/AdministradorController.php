<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;
use Illuminate\Support\Facades\Validator;

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
        $todosUsuarios = Usuarios::all();
        return view('crearusuario');
    }

    public function validarNuevoUsuario($request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'correo' => 'required|email',
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

    public function crearNuevoUsuario(Request $request)
    {
        $validator = $this->validarNuevoUsuario($request);
        //Comprobamos que las contraseñas coicidan
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

    public function eliminarUsuario($id)
    {
        $usuarioEliminar = Usuarios::find($id);

        $usuarioEliminar->delete();
        return redirect()->route('usuarios');
    }

    public function verModificarUsuario($id){
        $usuarioModificar = Usuarios::find($id);
        return view('modificarusuario',['usuario' => $usuarioModificar]);
    }

    public function modificarUsuario(Request $request,$id){
        $usuarioModificar = Usuarios::find($id);
        $usuarioModificar->nombre = $request->nombre;
        $usuarioModificar->apellidos = $request->apellidos;
        $usuarioModificar->email = $request->correo;
        $usuarioModificar->id_rol = $request->rol;
        $usuarioModificar->save();

        return redirect()->route('usuarios');
    }
}
