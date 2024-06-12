<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Contacto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Carbon\Carbon;

class EstadoController extends Controller
{
    public function subirEstado(Request $request)
    {
        $request->validate([
            'numero_actual' => 'required|string|max:255',
            'estado' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png|max:2048',
            'video' => 'nullable|file|mimes:mp4|max:20480',
        ], [
            'numero_actual.required' => 'El número actual es obligatorio',
            'foto.image' => 'La foto debe ser una imagen válida',
            'video.file' => 'El video debe ser un archivo válido',
        ]);

        try {
            $contacto = Contacto::where('numeroactual', $request->numero_actual)->firstOrFail();

            $fotoRuta = null;
            $videoRuta = null;

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoNombre = time() . '_' . $foto->getClientOriginalName(); // Renombrar la foto para evitar duplicados
                $foto->move(public_path('estados'), $fotoNombre);
                $fotoRuta = 'estados/' . $fotoNombre;
            }

            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoNombre = time() . '_' . $video->getClientOriginalName(); // Renombrar el video para evitar duplicados
                $video->move(public_path('estados'), $videoNombre);
                $videoRuta = 'estados/' . $videoNombre;
            }

            $estado = Estado::create([
                'numero_actual' => $request->numero_actual,
                'estado' => $request->estado,
                'foto_ruta' => $fotoRuta, // Guarda el identificador de la foto
                'video_ruta' => $videoRuta, // Guarda el identificador del video
                'likes' => 0,
                'vistas' => 0,
            ]);

            return response()->json(['message' => 'Estado subido correctamente', 'estado' => $estado], 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Número de contacto no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al subir el estado: ' . $e->getMessage()], 500);
        }
    }

    public function mostrarEstados(Request $request)
    {
        $request->validate([
            'numero' => 'required|string|max:255',
        ], [
            'numero.required' => 'El número es obligatorio',
        ]);

        try {
            // Buscar contactos donde el número actual o agregado coincida con el número proporcionado
            $contactos = Contacto::where('numeroactual', $request->numero)
                ->orWhere('numeroagregado', $request->numero)
                ->get();

            // Obtener los números de los contactos encontrados
            $numerosContactos = $contactos->pluck('numeroactual');

            // Obtener la fecha límite de 24 horas atrás
            $fechaLimite = Carbon::now()->subHours(24);

            // Buscar estados relacionados con los contactos encontrados
            $estados = Estado::whereIn('numero_actual', $numerosContactos)
                ->where('created_at', '>=', $fechaLimite)
                ->get();

            // Eliminar estados más antiguos de 24 horas
            Estado::where('numero_actual', $numerosContactos)
                ->where('created_at', '<', $fechaLimite)
                ->delete();

            return response()->json(['estados' => $estados], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al mostrar los estados: ' . $e->getMessage()], 500);
        }
    }
}
