<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ProfileController extends Controller
{
    // Mostrar perfil de usuario
    public function show($userId)
    {
        $user = User::findOrFail($userId);

        return response()->json(['user' => $user]);
    }

    // Actualizar perfil de usuario
    public function update(Request $request, $userId)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $userId,
        ]);

        $user = User::findOrFail($userId);

        if ($request->has('name')) {
            $user->name = $request->input('name');
        }

        if ($request->has('email')) {
            $user->email = $request->input('email');
        }

        $user->save();

        return response()->json(['message' => 'Perfil actualizado correctamente', 'user' => $user]);
    }
}
