<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\PacientesController;

Auth::routes(['register' => false,'reset' => false,'confirm' => false,'verify' => false]);
Route::get('/', function(){
	return redirect('/login');
});
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/pacientes', [PacientesController::class, 'verPacientes'])->name('pacientes');
Route::get('/nuevopaciente', [PacientesController::class, 'verCrearNuevoPaciente'])->name('nuevopaciente');
Route::post('/nuevopaciente', [PacientesController::class, 'crearNuevoPaciente']);