<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    // Mostrar detalles de un mensaje
    public function show($messageId)
    {
        $message = Message::findOrFail($messageId);

        return response()->json(['message' => $message]);
    }

    // Eliminar mensaje
    public function delete($messageId)
    {
        $message = Message::findOrFail($messageId);

        $message->delete();

        return response()->json(['message' => 'Mensaje eliminado correctamente']);
    }
}
