<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\DatosPersonalesController;


//Rutas de login y logout
Auth::routes(['register' => false,'reset' => false,'confirm' => false,'verify' => false]);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
//Routa de redirección de la raiz al login
Route::get('/', function(){
	return redirect('/login');
});

//Rutas de gestión de pacientes
Route::get('/pacientes', [PacientesController::class, 'verPacientes'])->name('pacientes');
Route::get('/nuevo/paciente', [PacientesController::class, 'verCrearNuevoPaciente'])->name('nuevopaciente');
Route::post('/nuevo/paciente', [PacientesController::class, 'crearNuevoPaciente']);

//Rutas de datos personales
Route::get('/datos/personales', [DatosPersonalesController::class, 'verDatosPersonales'])->name('datospersonales');
Route::put('/datos/personales', [DatosPersonalesController::class, 'modificarDatosPersonales']);
Route::get('/modificar/contrasena', [DatosPersonalesController::class, 'verModificarContrasena'])->name('modificarcontrasena');
Route::put('/modificar/contrasena', [DatosPersonalesController::class, 'modificarContrasena']);

//Rutas de gestión de usuarios
Route::get('/administrar/usuarios', [AdministradorController::class, 'verUsuarios'])->name('usuarios');
Route::delete('/eliminar/usuario/{id}', [AdministradorController::class, 'eliminarUsuario'])->name('eliminarusuario');
Route::get('/nuevo/usuario', [AdministradorController::class, 'verCrearNuevoUsuario'])->name('nuevousuario');
Route::post('/nuevo/usuario', [AdministradorController::class, 'crearNuevoUsuario']);
Route::get('/modificar/usuario/{id}', [AdministradorController::class, 'verModificarUsuario'])->name('modificarusuario');
Route::put('/modificar/usuario/{id}', [AdministradorController::class, 'modificarUsuario']);





