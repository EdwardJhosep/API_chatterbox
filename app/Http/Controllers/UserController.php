<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Contact;
use App\Models\GroupConversation;
use App\Models\Message;
use App\Models\Status;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // Agregar contacto
    public function addContact(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:users,id',
        ]);

        $user = $request->user();
        $contactId = $request->input('contact_id');

        // Verificar si ya existe el contacto
        if ($user->contacts()->where('contact_id', $contactId)->exists()) {
            return response()->json(['message' => 'El contacto ya existe']);
        }

        $contact = Contact::create([
            'user_id' => $user->id,
            'contact_id' => $contactId,
        ]);

        return response()->json(['message' => 'Contacto agregado correctamente', 'contact' => $contact]);
    }

    // Eliminar contacto
    public function deleteContact(Request $request, $contactId)
    {
        $user = $request->user();
        
        $contact = $user->contacts()->where('contact_id', $contactId)->first();

        if (!$contact) {
            return response()->json(['message' => 'Contacto no encontrado'], 404);
        }

        $contact->delete();

        return response()->json(['message' => 'Contacto eliminado correctamente']);
    }

    // Crear conversación
    public function createConversation(Request $request)
    {
        $request->validate([
            'participants' => 'required|array',
            'participants.*' => 'exists:users,id',
        ]);

        $participants = $request->input('participants');
        $participants[] = $request->user()->id;

        sort($participants);

        $conversation = DB::transaction(function () use ($participants) {
            $conversation = GroupConversation::create(['name' => 'Grupo de chat']);
            $conversation->participants()->sync($participants);

            return $conversation;
        });

        return response()->json(['message' => 'Conversación creada correctamente', 'conversation' => $conversation]);
    }

    // Enviar mensaje
    public function sendMessage(Request $request, $conversationId)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = $request->user();

        $message = Message::create([
            'conversation_id' => $conversationId,
            'user_id' => $user->id,
            'content' => $request->input('content'),
        ]);

        return response()->json(['message' => 'Mensaje enviado correctamente', 'message' => $message]);
    }

    // Crear grupo y enviar mensaje al grupo
    public function createGroupAndSendMessage(Request $request)
    {
        $request->validate([
            'participants' => 'required|array',
            'participants.*' => 'exists:users,id',
            'message' => 'required|string',
        ]);

        $participants = $request->input('participants');
        $participants[] = $request->user()->id;

        sort($participants);

        $group = DB::transaction(function () use ($participants) {
            $group = GroupConversation::create(['name' => 'Nuevo grupo']);
            $group->participants()->sync($participants);

            return $group;
        });

        // Envío de mensaje
        $user = $request->user();
        $message = Message::create([
            'conversation_id' => $group->id,
            'user_id' => $user->id,
            'content' => $request->input('message'),
        ]);

        return response()->json(['message' => 'Grupo creado y mensaje enviado correctamente', 'group' => $group, 'message' => $message]);
    }

    // Subir historia (historia se puede implementar como un Status)
    public function uploadStory(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $user = $request->user();

        $status = Status::create([
            'user_id' => $user->id,
            'type' => 'story',
            'content' => $request->input('content'),
        ]);

        return response()->json(['message' => 'Historia subida correctamente', 'status' => $status]);
    }

    // Eliminar historia
    public function deleteStory(Request $request, $statusId)
    {
        $user = $request->user();

        $status = $user->statuses()->find($statusId);

        if (!$status) {
            return response()->json(['message' => 'Historia no encontrada'], 404);
        }

        $status->delete();

        return response()->json(['message' => 'Historia eliminada correctamente']);
    }

    // Generar número de 9 dígitos aleatorio
    private function generateRandomNumber()
    {
        return mt_rand(100000000, 999999999);
    }
}
