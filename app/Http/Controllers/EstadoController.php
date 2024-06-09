<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;
use App\Models\Contacto;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

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

    public function eliminarEstado($id)
    {
        try {
            $estado = Estado::findOrFail($id);

            // Eliminar archivo de foto
            if ($estado->foto_ruta) {
                $fotoPath = public_path($estado->foto_ruta);
                if (file_exists($fotoPath)) {
                    unlink($fotoPath); // Elimina el archivo físicamente
                }
            }

            // Eliminar archivo de video
            if ($estado->video_ruta) {
                $videoPath = public_path($estado->video_ruta);
                if (file_exists($videoPath)) {
                    unlink($videoPath); // Elimina el archivo físicamente
                }
            }

            $estado->delete();

            return response()->json(['message' => 'Estado eliminado correctamente'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al eliminar el estado: ' . $e->getMessage()], 500);
        }
    }

    public function likeEstado($id)
    {
        try {
            $estado = Estado::findOrFail($id);
            $estado->increment('likes');
            return response()->json(['message' => 'Estado likeado correctamente', 'likes' => $estado->likes], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al likear el estado: ' . $e->getMessage()], 500);
        }
    }

    public function verEstado($id, Request $request)
    {
        try {
            $estado = Estado::findOrFail($id);

            $numeroActual = $request->input('numero_actual');
            
            // Verificar si el usuario tiene permiso para ver el estado
            $contacto = Contacto::where(function($query) use ($numeroActual, $estado) {
                $query->where('numeroactual', $numeroActual)
                      ->where('numeroagregado', $estado->numero_actual);
            })->orWhere(function($query) use ($numeroActual, $estado) {
                $query->where('numeroactual', $estado->numero_actual)
                      ->where('numeroagregado', $numeroActual);
            })->first();

            if (!$contacto) {
                return response()->json(['message' => 'No tienes permiso para ver este estado'], 403);
            }

            $estado->increment('vistas');
            return response()->json(['message' => 'Estado visto correctamente', 'vistas' => $estado->vistas], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Estado no encontrado'], 404);
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al ver el estado: ' . $e->getMessage()], 500);
        }
    }
}
