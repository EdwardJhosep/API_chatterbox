<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacto;
use App\Models\User;

class ContactoController extends Controller
{
    /**
     * Agrega un nuevo contacto.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function agregarContacto(Request $request)
    {
        // Validar datos
        $request->validate([
            'numeroactual' => 'required|string|max:255',
            'numeroagregado' => 'required|string|max:255',
        ]);

        // Verificar si el número actual existe en la tabla users
        $usuario = User::where('mobile_number', $request->numeroactual)->first();

        if (!$usuario) {
            return response()->json(['message' => 'El número actual no existe en la tabla users'], 404);
        }

        // Verificar si el número agregado ya está en la lista de contactos del número actual
        $contactoExistente = Contacto::where('numeroactual', $request->numeroactual)
            ->where('numeroagregado', $request->numeroagregado)
            ->first();

        if ($contactoExistente) {
            return response()->json(['message' => 'El contacto ya existe'], 409);
        }

        // Crear el nuevo contacto
        Contacto::create([
            'numeroactual' => $request->numeroactual,
            'numeroagregado' => $request->numeroagregado,
        ]);

        // Retornar una respuesta JSON
        return response()->json(['message' => 'Contacto agregado correctamente'], 201);
    }

    /**
     * Muestra todos los contactos de un número actual.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function mostrarContactos(Request $request)
    {
        // Validar datos
        $request->validate([
            'numeroactual' => 'required|string|max:255',
        ]);

        // Verificar si el número actual existe en la tabla users
        $usuario = User::where('mobile_number', $request->numeroactual)->first();

        if (!$usuario) {
            return response()->json(['message' => 'El número actual no existe en la tabla users'], 404);
        }

        // Obtener todos los contactos del número actual
        $contactos = Contacto::where('numeroactual', $request->numeroactual)->get();

        // Retornar una respuesta JSON con los contactos
        return response()->json(['contactos' => $contactos], 200);
    }
}
