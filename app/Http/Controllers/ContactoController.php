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
            'nombre' => 'required|string|max:255',
            'numeroagregado' => 'required|string|max:255',
        ]);

        // Verificar si el número agregado existe en la tabla users
        $usuarioAgregado = User::where('mobile_number', $request->numeroagregado)->first();

        if (!$usuarioAgregado) {
            return response()->json(['message' => 'El número agregado no existe en la tabla users'], 404);
        }

        // Verificar si el número agregado es el mismo que el número actual
        if ($request->numeroactual === $request->numeroagregado) {
            return response()->json(['message' => 'No puedes agregar tu propio número como contacto'], 400);
        }

        // Verificar si el número agregado ya está en la lista de contactos del número actual
        $contactoExistente = Contacto::where('numeroactual', $request->numeroactual)
            ->where('numeroagregado', $request->numeroagregado)
            ->first();

        if ($contactoExistente) {
            return response()->json(['message' => 'El contacto ya existe en tu lista de contactos'], 409);
        }

        // Crear el nuevo contacto
        Contacto::create([
            'numeroactual' => $request->numeroactual,
            'nombre' => $request->nombre,
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

        // Obtener todos los contactos del número actual, incluyendo el avatar del usuario agregado
        $contactos = Contacto::where('numeroactual', $request->numeroactual)
            ->with(['user' => function ($query) {
                $query->select('mobile_number', 'avatar');
            }])
            ->get();

        // Retornar una respuesta JSON con los contactos
        return response()->json(['contactos' => $contactos], 200);
    }

    /**
     * Elimina un contacto específico.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function eliminarContacto(Request $request)
    {
        // Validar datos
        $request->validate([
            'numeroactual' => 'required|string|max:255',
            'numeroagregado' => 'required|string|max:255',
        ]);

        // Verificar si el contacto existe y pertenece al número actual
        $contacto = Contacto::where('numeroactual', $request->numeroactual)
            ->where('numeroagregado', $request->numeroagregado)
            ->first();

        if (!$contacto) {
            return response()->json(['message' => 'El contacto no existe en tu lista de contactos'], 404);
        }

        // Eliminar el contacto
        $contacto->delete();

        // Retornar una respuesta JSON
        return response()->json(['message' => 'Contacto eliminado correctamente'], 200);
    }
}
