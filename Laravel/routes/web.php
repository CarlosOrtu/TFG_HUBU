<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PacientesController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\DatosPersonalesController;
use App\Http\Controllers\DatosPacienteController;
use App\Http\Controllers\EnfermedadController;
use App\Http\Controllers\AntecedentesController;
use App\Http\Controllers\TratamientosController;
use App\Http\Controllers\ReevaluacionesController;
use App\Http\Controllers\SeguimientosController;
use App\Http\Controllers\ComentariosController;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\PercentilesController;
use App\Http\Controllers\ExportarDatosController;
use App\Http\Controllers\BaseDeDatosSinteticaController;


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
Route::get('/eliminar/paciente', [PacientesController::class, 'verEliminarPaciente'])->name('vereliminarpaciente');
Route::get('/eliminar/paciente/{id}', [PacientesController::class, 'eliminarPaciente'])->name('eliminarpaciente');

//Rutas de datos personales
Route::get('/datos/personales', [DatosPersonalesController::class, 'verDatosPersonales'])->name('datospersonales');
Route::put('/datos/personales', [DatosPersonalesController::class, 'modificarDatosPersonales']);
Route::get('/modificar/contrasena', [DatosPersonalesController::class, 'verModificarContrasena'])->name('modificarcontrasena');
Route::put('/modificar/contrasena', [DatosPersonalesController::class, 'modificarContrasena']);

//Rutas de gestión de usuarios
Route::get('/administrar/usuarios', [AdministradorController::class, 'verUsuarios'])->name('usuarios');
Route::get('/eliminar/usuario/{id}', [AdministradorController::class, 'eliminarUsuario'])->name('eliminarusuario');
Route::get('/nuevo/usuario', [AdministradorController::class, 'verCrearNuevoUsuario'])->name('nuevousuario');
Route::post('/nuevo/usuario', [AdministradorController::class, 'crearNuevoUsuario']);
Route::get('/modificar/usuario/{id}', [AdministradorController::class, 'verModificarUsuario'])->name('modificarusuario');
Route::put('/modificar/usuario/{id}', [AdministradorController::class, 'modificarUsuario']);

//Rutas de visualización y modificación de datos de un paciente
Route::get('/ver/paciente/{id}', [DatosPacienteController::class , 'verPacienteSinModificar'])->name('verdatospaciente');
Route::get('/paciente/{id}', [DatosPacienteController::class , 'verPaciente'])->name('datospaciente');
Route::put('/paciente/{id}', [DatosPacienteController::class , 'cambiarDatosPaciente']);

//Rutas de visualización y modificación de datos de la enfermedad 
Route::get('/ver/paciente/{id}/enfermedad/datosgenerales', [EnfermedadController::class , 'verDatosEnfermedadSinModificar'])->name('verdatosenfermedad');
Route::get('/paciente/{id}/enfermedad/datosgenerales', [EnfermedadController::class , 'verDatosEnfermedad'])->name('datosenfermedad');
Route::put('/paciente/{id}/enfermedad/datosgenerales', [EnfermedadController::class , 'guardarDatosEnfermedad']);
//Rutas de visualización, modificación y eliminación de sintomas
Route::get('/ver/paciente/{id}/enfermedad/sintomas', [EnfermedadController::class, 'verDatosSintomasSinModificar'])->name('verdatossintomas');
Route::get('/paciente/{id}/enfermedad/sintomas', [EnfermedadController::class, 'verDatosSintomas'])->name('datossintomas');
Route::post('/paciente/{id}/enfermedad/sintomas', [EnfermedadController::class, 'crearDatosSintomas'])->name('datossintomascrear');
Route::put('/paciente/{id}/enfermedad/sintomas/{num_sintoma}', [EnfermedadController::class, 'modificarDatosSintomas'])->name('datossintomasmodificar');
Route::delete('/paciente/{id}/enfermedad/sintomas/{num_sintoma}', [EnfermedadController::class, 'eliminarSintoma'])->name('datossintomaseliminar');
Route::put('/paciente/{id}/enfermedad/sintomas/modificar/fecha', [EnfermedadController::class, 'modificarFechaSintomas'])->name('modificarfechasintomas');
//Rutas de visualización, creacción, modificación y eliminación de metastasis
Route::get('/ver/paciente/{id}/enfermedad/metastasis', [EnfermedadController::class, 'verMetastasisSinModificar'])->name('vermetastasis');
Route::get('/paciente/{id}/enfermedad/metastasis', [EnfermedadController::class, 'verMetastasis'])->name('metastasis');
Route::post('/paciente/{id}/enfermedad/metastasis', [EnfermedadController::class, 'crearMetastasis'])->name('metastasiscrear');
Route::put('/paciente/{id}/enfermedad/metastasis/{num_metastasis}', [EnfermedadController::class, 'modificarMetastasis'])->name('metastasismodificar');
Route::delete('/paciente/{id}/enfermedad/metastasis/{num_metastasis}', [EnfermedadController::class, 'eliminarMetastasis'])->name('metastasiseliminar');
//Rutas de visualización, creacción, modificación y eliminación de pruebas realizadas
Route::get('/ver/paciente/{id}/enfermedad/pruebas', [EnfermedadController::class, 'verPruebasSinModificar'])->name('verpruebas');
Route::get('/paciente/{id}/enfermedad/pruebas', [EnfermedadController::class, 'verPruebas'])->name('pruebas');
Route::post('/paciente/{id}/enfermedad/pruebas', [EnfermedadController::class, 'crearPruebas'])->name('pruebascrear');
Route::put('/paciente/{id}/enfermedad/pruebas/{num_prueba}', [EnfermedadController::class, 'modificarPruebas'])->name('pruebasmodificar');
Route::delete('/paciente/{id}/enfermedad/pruebas/{num_prueba}', [EnfermedadController::class, 'eliminarPruebas'])->name('pruebaseliminar');
//Rutas de visualización, creacción, modificación y eliminación de técnicas realizadas
Route::get('/ver/paciente/{id}/enfermedad/tecnicas', [EnfermedadController::class, 'verTecnicasSinModificar'])->name('vertecnicas');
Route::get('/paciente/{id}/enfermedad/tecnicas', [EnfermedadController::class, 'verTecnicas'])->name('tecnicas');
Route::post('/paciente/{id}/enfermedad/tecnicas', [EnfermedadController::class, 'crearTecnicas'])->name('tecnicascrear');
Route::put('/paciente/{id}/enfermedad/tecnicas/{num_tecnica}', [EnfermedadController::class, 'modificarTecnicas'])->name('tecnicasmodificar');
Route::delete('/paciente/{id}/enfermedad/tecnicas/{num_tecnica}', [EnfermedadController::class, 'eliminarTecnicas'])->name('tecnicaseliminar');
//Rutas de visualización, creacción, modificación y eliminación de otros tumores
Route::get('/ver/paciente/{id}/enfermedad/otrostumores', [EnfermedadController::class, 'verOtrosTumoresSinModificar'])->name('verotrostumores');
Route::get('/paciente/{id}/enfermedad/otrostumores', [EnfermedadController::class, 'verOtrosTumores'])->name('otrostumores');
Route::post('/paciente/{id}/enfermedad/otrostumores', [EnfermedadController::class, 'crearOtrosTumores'])->name('otrostumorescrear');
Route::put('/paciente/{id}/enfermedad/otrostumores/{num_otrostumores}', [EnfermedadController::class, 'modificarOtrosTumores'])->name('otrostumoresmodificar');
Route::delete('/paciente/{id}/enfermedad/otrostumores/{num_otrostumores}', [EnfermedadController::class, 'eliminarOtrosTumores'])->name('otrostumoreseliminar');
//Rutas de visualización, creacción, modificación y eliminación de biomarcadores
Route::get('/ver/paciente/{id}/enfermedad/biomarcadores', [EnfermedadController::class, 'verBiomarcadoresSinModificar'])->name('verbiomarcadores');
Route::get('/paciente/{id}/enfermedad/biomarcadores', [EnfermedadController::class, 'verBiomarcadores'])->name('biomarcadores');
Route::post('/paciente/{id}/enfermedad/biomarcadores', [EnfermedadController::class, 'guardarBiomarcadores']);
Route::delete('/paciente/{id}/enfermedad/biomarcadores/{num_biomarcador}', [EnfermedadController::class, 'eliminarbiomarcadores'])->name('eliminarbiomarcador');

//Rutas de visualización antecedentes medicos
Route::get('/ver/paciente/{id}/antecedentes/medicos', [AntecedentesController::class, 'verAntecedentesMedicosSinModificar'])->name('verantecedentesmedicos');
Route::get('/paciente/{id}/antecedentes/medicos', [AntecedentesController::class, 'verAntecedentesMedicos'])->name('antecedentesmedicos');
Route::post('/paciente/{id}/antecedentes/medicos', [AntecedentesController::class, 'crearAntecedentesMedicos'])->name('antecedentemedicocrear');
Route::put('/paciente/{id}/antecedentes/medicos/{num_antecendente_medico}', [AntecedentesController::class, 'modificarAntecedentesMedicos'])->name('antecedentemedicomodificar');
Route::delete('/paciente/{id}/antecedentes/medicos/{num_antecendente_medico}', [AntecedentesController::class, 'eliminarAntecedentesMedicos'])->name('antecedentemedicoeliminar');
//Rutas de visualización antecedentes oncologicos
Route::get('/ver/paciente/{id}/antecedentes/oncologicos', [AntecedentesController::class, 'verAntecedentesOncologicosSinModificar'])->name('verantecedentesoncologicos');
Route::get('/paciente/{id}/antecedentes/oncologicos', [AntecedentesController::class, 'verAntecedentesOncologicos'])->name('antecedentesoncologicos');
Route::post('/paciente/{id}/antecedentes/oncologicos', [AntecedentesController::class, 'crearAntecedentesOncologicos'])->name('antecedenteoncologicocrear');
Route::put('/paciente/{id}/antecedentes/oncologicos/{num_antecendente_oncologico}', [AntecedentesController::class, 'modificarAntecedentesOncologicos'])->name('antecedenteoncologicomodificar');
Route::delete('/paciente/{id}/antecedentes/oncologicos/{num_antecendente_oncologico}', [AntecedentesController::class, 'eliminarAntecedentesOncologicos'])->name('antecedenteoncologicoeliminar');
//Rutas de visualización antecedentes oncologicos
Route::get('/ver/paciente/{id}/antecedentes/familiares', [AntecedentesController::class, 'verAntecedentesFamiliaresSinModificar'])->name('verantecedentesfamiliares');
Route::get('/paciente/{id}/antecedentes/familiares', [AntecedentesController::class, 'verAntecedentesFamiliares'])->name('antecedentesfamiliares');
Route::post('/paciente/{id}/antecedentes/familiares', [AntecedentesController::class, 'crearAntecedentesFamiliares'])->name('antecedentefamiliarcrear');
Route::put('/paciente/{id}/antecedentes/familiares/{num_antecendente_familiar}', [AntecedentesController::class, 'modificarAntecedentesFamiliares'])->name('antecedentefamiliarmodificar');
Route::delete('/paciente/{id}/antecedentes/familiares/{num_antecendente_familiar}', [AntecedentesController::class, 'eliminarAntecedentesFamiliares'])->name('antecedentefamiliareliminar');

//Rutas de visualización radioterapida
Route::get('/ver/paciente/{id}/tratamientos/radioterapia',  [TratamientosController::class, 'verRadioterapiaSinModificar'])->name('verradioterapias');
Route::get('/paciente/{id}/tratamientos/radioterapia',  [TratamientosController::class, 'verRadioterapia'])->name('radioterapias');
Route::post('/paciente/{id}/tratamientos/radioterapia', [TratamientosController::class, 'crearRadioterapia'])->name('radioterapiacrear');
Route::put('/paciente/{id}/tratamientos/radioterapia/{num_radioterapia}', [TratamientosController::class, 'modificarRadioterapia'])->name('radioterapiamodificar');
Route::delete('/paciente/{id}/tratamientos/radioterapia/{num_radioterapia}', [TratamientosController::class, 'eliminarRadioterapia'])->name('radioterapiaeliminar');
//Rutas de visualización radioterapida
Route::get('/ver/paciente/{id}/tratamientos/cirugia',  [TratamientosController::class, 'verCirugiaSinModificar'])->name('vercirugias');
Route::get('/paciente/{id}/tratamientos/cirugia',  [TratamientosController::class, 'verCirugia'])->name('cirugias');
Route::post('/paciente/{id}/tratamientos/cirugia', [TratamientosController::class, 'crearCirugia'])->name('cirugiacrear');
Route::put('/paciente/{id}/tratamientos/cirugia/{num_cirugia}', [TratamientosController::class, 'modificarCirugia'])->name('cirugiamodificar');
Route::delete('/paciente/{id}/tratamientos/cirugia/{num_cirugia}', [TratamientosController::class, 'eliminarCirugia'])->name('cirugiaeliminar');
//Rutas de visualización quimioterapia
Route::get('/ver/paciente/{id}/tratamientos/quimioterapia',  [TratamientosController::class, 'verQuimioterapiaSinModificar'])->name('verquimioterapias');
Route::get('/paciente/{id}/tratamientos/quimioterapia',  [TratamientosController::class, 'verQuimioterapia'])->name('quimioterapias');
Route::post('/paciente/{id}/tratamientos/quimioterapia', [TratamientosController::class, 'crearQuimioterapia'])->name('quimioterapiacrear');
Route::put('/paciente/{id}/tratamientos/quimioterapia/{num_quimioterapia}', [TratamientosController::class, 'modificarQuimioterapia'])->name('quimioterapiamodificar');
Route::delete('/paciente/{id}/tratamientos/quimioterapia/{num_quimioterapia}', [TratamientosController::class, 'eliminarQuimioterapia'])->name('quimioterapiaeliminar');
//Ruta de visualización de secuencia de tratamientos
Route::get('/paciente/{id}/tratamientos/secuencia',  [TratamientosController::class, 'verSecuenciaTratamientos'])->name('secuencia');
Route::get('/paciente/{id}/ver/tratamientos/secuencia',  [TratamientosController::class, 'verSecuenciaTratamientos'])->name('versecuencia');

//Rutas de visualización reevaluaciones
Route::get('/ver/paciente/{id}/reevaluaciones',  [ReevaluacionesController::class, 'verReevaluacionSinModificar'])->name('verreevaluaciones');
Route::get('/paciente/{id}/reevaluaciones/nueva',  [ReevaluacionesController::class, 'verReevaluacionNueva'])->name('reevaluacionesnuevas');
Route::post('/paciente/{id}/reevaluaciones/nueva',  [ReevaluacionesController::class, 'crearReevaluación'])->name('crearreevaluacion');
Route::get('/paciente/{id}/reevaluaciones/modificar/{num_reevaluacion}',  [ReevaluacionesController::class, 'verReevaluacion'])->name('vermodificarreevaluacion');
Route::put('/paciente/{id}/reevaluaciones/modificar/{num_reevaluacion}',  [ReevaluacionesController::class, 'modificarReevaluación'])->name('modificareevaluacion');
Route::delete('/paciente/{id}/reevaluaciones/modificar/{num_reevaluacion}',  [ReevaluacionesController::class, 'eliminarReevaluación'])->name('eliminarreevaluacion');

//Rutas de visualización reevaluaciones
Route::get('/ver/paciente/{id}/seguimientos',  [SeguimientosController::class, 'verSeguimientoSinModificar'])->name('verseguimientos');
Route::get('/paciente/{id}/seguimientos/nuevo',  [SeguimientosController::class, 'verSeguimientoNuevo'])->name('seguimientosnuevos');
Route::post('/paciente/{id}/seguimientos/nuevo',  [SeguimientosController::class, 'crearseguimiento'])->name('crearseguimiento');
Route::get('/paciente/{id}/seguimientos/modificar/{num_seguimiento}',  [SeguimientosController::class, 'verSeguimiento'])->name('vermodificarseguimiento');
Route::put('/paciente/{id}/seguimientos/modificar/{num_seguimiento}',  [SeguimientosController::class, 'modificarSeguimiento'])->name('modificarseguimiento');
Route::delete('/paciente/{id}/seguimientos/modificar/{num_seguimiento}',  [SeguimientosController::class, 'eliminarSeguimiento'])->name('eliminarseguimiento');

//Rutas de visualización reevaluaciones
Route::get('/ver/paciente/{id}/comentarios',  [ComentariosController::class, 'verComentarioSinModificar'])->name('vercomentarios');
Route::get('/paciente/{id}/comentarios/nuevo',  [ComentariosController::class, 'verComentarioNuevo'])->name('comentarionuevo');
Route::post('/paciente/{id}/comentarios/nuevo',  [ComentariosController::class, 'crearComentario'])->name('crearcomentario');
Route::get('/paciente/{id}/comentarios/modificar/{num_comentario}',  [ComentariosController::class, 'verComentario'])->name('vermodificarcomentario');
Route::put('/paciente/{id}/comentarios/modificar/{num_comentario}',  [ComentariosController::class, 'modificarComentario'])->name('modificarcomentario');
Route::delete('/paciente/{id}/comentarios/modificar/{num_comentario}',  [ComentariosController::class, 'eliminarComentario'])->name('eliminarcomentario');

//Rutas de visualización de las graficas
Route::get('/ver/graficas',  [GraficosController::class, 'verGraficas'])->name('vergraficas');
Route::post('/ver/graficas',  [GraficosController::class, 'imprimirGraficas'])->name('imprimirgrafica');

//Rutas de visualización de percentiles
Route::get('/ver/percentiles',  [PercentilesController::class, 'verPercentiles'])->name('verpercentiles');
Route::post('/ver/percentiles',  [PercentilesController::class, 'imprimirPercentiles'])->name('imprimirpercentiles');

//Rutas para exportar los datos
Route::get('/ver/exportardatos', [ExportarDatosController::class, 'verExportarDatos'])->name('verexportardatos');
Route::post('/ver/exportardatos', [ExportarDatosController::class, 'exportarDatos'])->name('exportardatos');

//Rutas para crear la base de datos sintética
Route::get('/ver/basesintetica', [BaseDeDatosSinteticaController::class, 'verBaseSintetica'])->name('verbasesintetica');
Route::post('/ver/basesintetica', [BaseDeDatosSinteticaController::class, 'crearBaseSintetica'])->name('crearbasesintetica');

Route::get('/prueba/rutas', [BaseDeDatosSinteticaController::class, 'prueba'])->name('prueba');