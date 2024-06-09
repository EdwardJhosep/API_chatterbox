<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupConversation;
use App\Models\Message;
use Illuminate\Support\Facades\DB;

class GroupConversationController extends Controller
{
    // Mostrar mensajes de un grupo
    public function showMessages($groupId)
    {
        $group = GroupConversation::findOrFail($groupId);
        $messages = $group->messages()->with('user')->get();

        return response()->json(['messages' => $messages]);
    }

    // Eliminar grupo
    public function deleteGroup($groupId)
    {
        $group = GroupConversation::findOrFail($groupId);

        DB::transaction(function () use ($group) {
            $group->messages()->delete();
            $group->delete();
        });

        return response()->json(['message' => 'Grupo eliminado correctamente']);
    }

    // Agregar participante a un grupo
    public function addParticipant(Request $request, $groupId)
    {
        $request->validate([
            'participant_id' => 'required|exists:users,id',
        ]);

        $group = GroupConversation::findOrFail($groupId);

        $group->participants()->attach($request->input('participant_id'));

        return response()->json(['message' => 'Participante agregado al grupo correctamente']);
    }

    // Eliminar participante de un grupo
    public function removeParticipant(Request $request, $groupId, $participantId)
    {
        $group = GroupConversation::findOrFail($groupId);

        $group->participants()->detach($participantId);

        return response()->json(['message' => 'Participante eliminado del grupo correctamente']);
    }
}
