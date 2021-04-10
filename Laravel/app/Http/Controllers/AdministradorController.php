<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Users;


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
        $todosUsuarios = Users::all();
        return view('usuarios',['usuarios' => $todosUsuarios]);
    }

    public function verCrearNuevoUsuario()
    {
        $todosUsuarios = Users::all();
        return view('crearusuario');
    }

    public function crearNuevoUsuario(Request $request)
    {
        //Comprobamos que las contraseñas coicidan
        if($request->contrasena != $request->contrasena_repetir)
            return back()->withErrors('No coinciden las contraseñas','pass');
        //Creamos el objeto usuario
        $nuevoUsuario = new Users();
        //Le asignamos los valores recibidos desde el metodo POST
        $nuevoUsuario->name = $request->nombre;
        $nuevoUsuario->surname = $request->apellidos;
        $nuevoUsuario->email = $request->correo;
        $nuevoUsuario->password = bcrypt($request->contrasena);
        $nuevoUsuario->id_role = $request->rol; 
        $nuevoUsuario->save();

        return redirect()->route('usuarios');
    }

    public function eliminarUsuario($id)
    {
        $usuarioEliminar = Users::find($id);

        $usuarioEliminar->delete();
        return redirect()->route('usuarios');
    }

    public function verModificarUsuario($id){
        $usuarioModificar = Users::find($id);
        return view('modificarusuario',['usuario' => $usuarioModificar]);
    }

    public function modificarUsuario(Request $request,$id){
        $usuarioModificar = Users::find($id);
        $usuarioModificar->name = $request->nombre;
        $usuarioModificar->surname = $request->apellidos;
        $usuarioModificar->email = $request->correo;
        $usuarioModificar->id_role = $request->rol;
        $usuarioModificar->save();

        return redirect()->route('usuarios');
    }
}
