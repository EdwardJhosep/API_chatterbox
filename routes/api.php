<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación
Route::post('/login', 'App\Http\Controllers\AuthController@login');
Route::post('/register', 'App\Http\Controllers\AuthController@register')->name('register');

// Ruta de subir avatar (accesible para todos)
Route::post('upload-avatar', 'App\Http\Controllers\AuthController@uploadAvatar');

// Rutas sin protección de autenticación
Route::prefix('user')->group(function () {
    Route::get('/{userId}', 'App\Http\Controllers\UserController@show');
    Route::put('/{userId}', 'App\Http\Controllers\ProfileController@update');
});

Route::prefix('contacts')->group(function () {
    Route::get('/', 'App\Http\Controllers\ContactController@index');
    Route::post('/', 'App\Http\Controllers\UserController@addContact');
    Route::delete('/{contactId}', 'App\Http\Controllers\UserController@deleteContact');
});

Route::prefix('conversation')->group(function () {
    Route::get('/{conversationId}/messages', 'App\Http\Controllers\ConversationController@showMessages');
    Route::delete('/{conversationId}', 'App\Http\Controllers\ConversationController@deleteConversation');
    Route::post('/{conversationId}/message', 'App\Http\Controllers\UserController@sendMessage');
});

Route::prefix('group')->group(function () {
    Route::get('/{groupId}/messages', 'App\Http\Controllers\GroupConversationController@showMessages');
    Route::delete('/{groupId}', 'App\Http\Controllers\GroupConversationController@deleteGroup');
    Route::post('/{groupId}/add-participant', 'App\Http\Controllers\GroupConversationController@addParticipant');
    Route::delete('/{groupId}/remove-participant/{participantId}', 'App\Http\Controllers\GroupConversationController@removeParticipant');
    Route::post('/message', 'App\Http\Controllers\UserController@createGroupAndSendMessage');
});

Route::prefix('status')->group(function () {
    Route::get('/{userId}', 'App\Http\Controllers\StatusController@show');
    Route::delete('/{userId}', 'App\Http\Controllers\StatusController@delete');
    Route::post('/', 'App\Http\Controllers\UserController@uploadStory');
    Route::delete('/{statusId}', 'App\Http\Controllers\UserController@deleteStory');
});
