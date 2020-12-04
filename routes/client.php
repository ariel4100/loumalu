<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\Logincontroller;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

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
//
//Route::get('/', function () {
//    return view('welcome');
//})->name('home');

//Secciones
Route::get('pedidos', [ClientController::class, 'pedidos'])->name('home');
Route::get('carrito', [ClientController::class, 'carrito'])->name('carrito');
Route::get('estado-cuenta', [ClientController::class, 'estado_cuenta'])->name('estado.cuenta');
Route::get('envios', [ClientController::class, 'envios'])->name('envios');
Route::get('reclamos', [ClientController::class, 'reclamos'])->name('reclamos');
Route::get('historico', [ClientController::class, 'historico'])->name('historico');


