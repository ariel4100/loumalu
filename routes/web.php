<?php

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
Route::middleware(['auth:sanctum', 'verified'])->get('/adm', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');
Route::get('config', function () {
    \Artisan::call('config:clear');
    dd("config is cleared");
});
Route::get('cache', function () {
    \Artisan::call('optimize');
    dd("Cache is cleared");
});
Route::get('link', function () {
    $exitCode = \Artisan::call('storage:link', [] );
    echo $exitCode; // 0 exit code for no errors.
});

//Secciones
Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('nosotros', [FrontendController::class, 'empresa'])->name('empresa');
Route::get('descargas', [FrontendController::class, 'descargas'])->name('descargas');
Route::get('buscador', [FrontendController::class, 'buscador'])->name('buscador');
Route::get('buscador-pro', [FrontendController::class, 'buscador_pro'])->name('buscador.pro');
Route::get('contacto', [FrontendController::class, 'contacto'])->name('contacto');
Route::post('contacto', [MailController::class, 'contacto'])->name('mail.contacto');
Route::post('presupuesto', [MailController::class, 'presupuesto'])->name('mail.presupuesto');
Route::post('suscriptores', [MailController::class, 'suscriptor'])->name('mail.newsletter');

//productos
Route::get('productos', [FrontendController::class, 'familias'])->name('familias');
Route::get('categoria-producto/{slug}', [FrontendController::class, 'subfamilias'])->name('subfamilias');
Route::get('productos/{slug}', [FrontendController::class, 'productos'])->name('productos');
Route::get('producto/{slug?}', [FrontendController::class, 'producto'])->name('producto');

//Novedades
Route::get('blog/{slug?}', [FrontendController::class, 'novedades'])->name('novedades');
Route::get('blog/noti/{slug}', [FrontendController::class, 'novedad'])->name('novedad');

//Login Clientes
Route::post('ingresar', [Logincontroller::class, 'login'])->name('auth.login');
Route::post('registro', [Logincontroller::class, 'register'])->name('auth.registro');

