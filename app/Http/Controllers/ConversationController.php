<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    // Mostrar mensajes de una conversación
    public function showMessages($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);
        $messages = $conversation->messages()->with('user')->get();

        return response()->json(['messages' => $messages]);
    }

    // Eliminar conversación
    public function deleteConversation($conversationId)
    {
        $conversation = Conversation::findOrFail($conversationId);

        DB::transaction(function () use ($conversation) {
            $conversation->messages()->delete();
            $conversation->delete();
        });

        return response()->json(['message' => 'Conversación eliminada correctamente']);
    }
}
