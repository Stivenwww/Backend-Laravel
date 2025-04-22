<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserControllerApi;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';



// SOLO SE USA PARA MOSTRAR O SIMULAR LOS DOS FORMULARIOS
Route::middleware('auth')->group(function(){
    // GET para mostrar la vista
    Route::get('/completar-nombre', fn() => view('completar-nombre'))->name('completar.nombre.form');

    // POST para enviar al controlador
    Route::post('/completar-nombre/{id}', [UserControllerApi::class, 'actualizarUsuario'])
         ->name('completar.nombre');
});
