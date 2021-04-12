<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Usuarios;
use App\Rules\ComprobarContrasenasIguales;
use App\Rules\CorreoUnico;
use Auth;


class DatosPersonalesController extends Controller
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

    public function validarDatos($request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'apellidos' => 'required',
            'correo' => ['required','email', new CorreoUnico(Auth::user()->id_usuario)],
        ],
        [
        'required' => 'El campo :attribute no puede estar vacio',
        'email' => 'Debe de ser una dirección de correo valida',
        'unique' => 'El correo ya está en uso de'
        ]);

        return $validator;
    }

    public function validarContrasenas($request)
    {
        $validator = Validator::make($request->all(), [
            'contrasena_antigua' => ['required', new ComprobarContrasenasIguales],
            'contrasena_nueva' => 'required|same:contrasena_nueva_repetida',
            'contrasena_nueva_repetida' => 'required'
        ],
        [
        'required' => 'El campo :attribute no puede estar vacio',
        'same' => 'Las dos contraseñas deben coincidir'
        ]);

        return $validator;
    }

    //Metodo que retorna la vista datospersonales
    public function verDatosPersonales()
    {
        return view('datospersonales');
    }

    public function modificarDatosPersonales(Request $request)
    {
        $validator = $this->validarDatos($request);
        //Comprobamos si hay algun error de validacion
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $usuarioModificar = Usuarios::find(Auth::user()->id_usuario);
        $usuarioModificar->nombre = $request->nombre;
        $usuarioModificar->apellidos = $request->apellidos;
        $usuarioModificar->email = $request->correo;

        $usuarioModificar->save();
        return redirect()->route('datospersonales');
    }

    //Metodo que retorna la vista modificarcontrasena
    public function verModificarContrasena()
    {
        return view('modificarcontrasena');
    }

    public function modificarContrasena(Request $request)
    {
        $validator = $this->validarContrasenas($request);
        //Comprobamos si hay algun error de validacion
        if($validator->fails())
            return back()->withErrors($validator->errors())->withInput();
        $usuarioModificar = Usuarios::find(Auth::user()->id_usuario);
        $usuarioModificar->contrasena = bcrypt($request->contrasena_nueva);

        $usuarioModificar->save();
        return redirect()->route('datospersonales');
    }
}
