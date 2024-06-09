<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;

class StatusController extends Controller
{
    // Mostrar estado de un usuario
    public function show($userId)
    {
        $status = Status::where('user_id', $userId)->latest()->first();

        return response()->json(['status' => $status]);
    }

    // Eliminar estado de un usuario
    public function delete($userId)
    {
        $status = Status::where('user_id', $userId)->latest()->first();

        if (!$status) {
            return response()->json(['message' => 'No se encontró ningún estado para este usuario'], 404);
        }

        $status->delete();

        return response()->json(['message' => 'Estado eliminado correctamente']);
    }
}
