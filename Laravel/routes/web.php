<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes(['register' => false,'reset' => false,'confirm' => false,'verify' => false]);
Route::get('/', function(){
	return redirect('/login');
});
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/pacientes', [App\Http\Controllers\HomeController::class, 'index']);
