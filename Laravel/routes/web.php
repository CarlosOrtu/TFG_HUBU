<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\DatosPersonalesController;
use App\Http\Controllers\DatosPacienteController;
use App\Http\Controllers\EnfermedadController;


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

//Rutas de visualización y modificación de datos de un paciente
Route::get('/paciente/{id}', [DatosPacienteController::class , 'verPaciente'])->name('datospaciente');
Route::put('/paciente/{id}', [DatosPacienteController::class , 'cambiarDatosPaciente']);
//Rutas de visualización y modificación de datos de la enfermedad 
Route::get('/paciente/{id}/enfermedad/datosgenerales', [EnfermedadController::class , 'verDatosEnfermedad'])->name('datosenfermedad');
Route::put('/paciente/{id}/enfermedad/datosgenerales', [EnfermedadController::class , 'guardarDatosEnfermedad']);
//Rutas de visualización, modificación y eliminación de sintomas
Route::get('/paciente/{id}/enfermedad/sintomas', [EnfermedadController::class, 'verDatosSintomas'])->name('datossintomas');
Route::post('/paciente/{id}/enfermedad/sintomas', [EnfermedadController::class, 'crearDatosSintomas'])->name('datossintomascrear');
Route::put('/paciente/{id}/enfermedad/sintomas/{num_sintoma}', [EnfermedadController::class, 'modificarDatosSintomas'])->name('datossintomasmodificar');
Route::delete('/paciente/{id}/enfermedad/sintomas/{num_sintoma}', [EnfermedadController::class, 'eliminarSintoma'])->name('datossintomaseliminar');
//Rutas de visualización, creacción, modificación y eliminación de metastasis
Route::get('/paciente/{id}/enfermedad/metastasis', [EnfermedadController::class, 'verMetastasis'])->name('metastasis');
Route::post('/paciente/{id}/enfermedad/metastasis', [EnfermedadController::class, 'crearMetastasis'])->name('metastasiscrear');
Route::put('/paciente/{id}/enfermedad/metastasis/{num_metastasis}', [EnfermedadController::class, 'modificarMetastasis'])->name('metastasismodificar');
Route::delete('/paciente/{id}/enfermedad/metastasis/{num_metastasis}', [EnfermedadController::class, 'eliminarMetastasis'])->name('metastasiseliminar');
//Rutas de visualización, creacción, modificación y eliminación de pruebas realizadas
Route::get('/paciente/{id}/enfermedad/pruebas', [EnfermedadController::class, 'verPruebas'])->name('pruebas');
Route::post('/paciente/{id}/enfermedad/pruebas', [EnfermedadController::class, 'crearPruebas'])->name('pruebascrear');
Route::put('/paciente/{id}/enfermedad/pruebas/{num_prueba}', [EnfermedadController::class, 'modificarPruebas'])->name('pruebasmodificar');
Route::delete('/paciente/{id}/enfermedad/pruebas/{num_prueba}', [EnfermedadController::class, 'eliminarPruebas'])->name('pruebaseliminar');
//Rutas de visualización, creacción, modificación y eliminación de técnicas realizadas
Route::get('/paciente/{id}/enfermedad/tecnicas', [EnfermedadController::class, 'verTecnicas'])->name('tecnicas');
Route::post('/paciente/{id}/enfermedad/tecnicas', [EnfermedadController::class, 'crearTecnicas'])->name('tecnicascrear');
Route::put('/paciente/{id}/enfermedad/tecnicas/{num_tecnica}', [EnfermedadController::class, 'modificarTecnicas'])->name('tecnicasmodificar');
Route::delete('/paciente/{id}/enfermedad/tecnicas/{num_tecnica}', [EnfermedadController::class, 'eliminarTecnicas'])->name('tecnicaseliminar');
//Rutas de visualización, creacción, modificación y eliminación de otros tumores
Route::get('/paciente/{id}/enfermedad/otrostumores', [EnfermedadController::class, 'verOtrosTumores'])->name('otrostumores');
Route::post('/paciente/{id}/enfermedad/otrostumores', [EnfermedadController::class, 'crearOtrosTumores'])->name('otrostumorescrear');
Route::put('/paciente/{id}/enfermedad/otrostumores/{num_otrostumores}', [EnfermedadController::class, 'modificarOtrosTumores'])->name('otrostumoresmodificar');
Route::delete('/paciente/{id}/enfermedad/otrostumores/{num_otrostumores}', [EnfermedadController::class, 'eliminarOtrosTumores'])->name('otrostumoreseliminar');
//Rutas de visualización, creacción, modificación y eliminación de biomarcadores
Route::get('/paciente/{id}/enfermedad/biomarcadores', [EnfermedadController::class, 'verBiomarcadores'])->name('biomarcadores');
Route::post('/paciente/{id}/enfermedad/biomarcadores', [EnfermedadController::class, 'guardarBiomarcadores']);
Route::delete('/paciente/{id}/enfermedad/biomarcadores/{num_biomarcador}', [EnfermedadController::class, 'eliminarbiomarcadores'])->name('eliminarbiomarcador');

