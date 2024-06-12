<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactoController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/mostrarAvatar', [AuthController::class, 'mostrarAvatar']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/avatar', [AuthController::class, 'uploadAvatar']);
Route::post('/agregarcontacto', [ContactoController::class, 'agregarContacto'])->name('contactos.agregar');
Route::get('/mostarcontacto', [ContactoController::class, 'mostrarContactos']);
Route::delete('/eliminarcontacto', [ContactoController::class, 'eliminarContacto']);



use App\Http\Controllers\MensajeController;
Route::post('/mensajes', [MensajeController::class, 'mostrarMensajes'])->name('mostrar-mensajes');
Route::post('/enviar-mensaje', [MensajeController::class, 'enviarMensaje']);
Route::delete('/eliminar-mensaje/{id}', [MensajeController::class, 'eliminarMensaje']);
use App\Http\Controllers\EstadoController;

Route::post('/estado', [EstadoController::class, 'subirEstado']); // Subir un nuevo estado
Route::post('/estadosmostrar', [EstadoController::class, 'mostrarEstados']);
