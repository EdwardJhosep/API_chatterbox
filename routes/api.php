<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/avatar', [AuthController::class, 'uploadAvatar']);
Route::post('/agregarcontacto', [ContactoController::class, 'agregarContacto'])->name('contactos.agregar');
Route::get('/mostarcontacto', [ContactoController::class, 'mostrarContactos']);
Route::delete('/eliminarcontacto', [ContactoController::class, 'eliminarContacto']);



use App\Http\Controllers\MensajeController;

Route::post('/enviar-mensaje', [MensajeController::class, 'enviarMensaje']);
Route::delete('/eliminar-mensaje/{id}', [MensajeController::class, 'eliminarMensaje']);
use App\Http\Controllers\EstadoController;

Route::post('/estado', [EstadoController::class, 'subirEstado']); // Subir un nuevo estado
Route::delete('/estado/{id}', [EstadoController::class, 'eliminarEstado']); // Eliminar un estado por ID
Route::post('/estado/{id}/like', [EstadoController::class, 'likeEstado']); // Dar like a un estado por ID
Route::get('/estado/{id}', [EstadoController::class, 'verEstado']); // Ver un estado por ID y verificar permiso