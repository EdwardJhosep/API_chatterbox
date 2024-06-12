<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mensaje;
use App\Models\Contacto;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MensajeController extends Controller
{
    public function enviarMensaje(Request $request)
    {
        $request->validate([
            'numero_origen' => 'required|string|max:255',
            'numero_destino' => 'required|string|max:255',
            'mensaje' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png|max:2048', // Ajusta los tipos de archivo permitidos y el tamaño máximo para imágenes
            'video' => 'nullable|file|mimes:mp4|max:20480', // Ajusta los tipos de archivo permitidos y el tamaño máximo para videos
        ]);
    
        try {
            // Verificar si el número de origen existe en la tabla de contactos
            $contactoOrigen = Contacto::where('numeroactual', $request->numero_origen)->first();
            if (!$contactoOrigen) {
                return response()->json(['message' => 'Número de origen no encontrado en la lista de contactos'], 404);
            }
    
            // Verificar si el número de destino existe en la tabla de contactos
            $contactoDestino = Contacto::where('numeroagregado', $request->numero_destino)->first();
            if (!$contactoDestino) {
                return response()->json(['message' => 'Número de destino no encontrado en la lista de contactos'], 404);
            }
    
            $fotoNombre = null;
            $fotoRuta = null;
            $videoNombre = null;
            $videoRuta = null;
    
            // Verificar si se envió una foto
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fotoNombre = Str::random(20) . '.' . $foto->getClientOriginalExtension(); // Nombre aleatorio para evitar colisiones
                $foto->move(public_path('mensajes'), $fotoNombre); // Mover a la carpeta 'public/mensajes'
                $fotoRuta = 'mensajes/' . $fotoNombre; // Ruta para guardar en la base de datos
            }
    
            // Verificar si se envió un video
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoNombre = Str::random(20) . '.' . $video->getClientOriginalExtension(); // Nombre aleatorio para evitar colisiones
                $video->move(public_path('mensajes'), $videoNombre); // Mover a la carpeta 'public/mensajes'
                $videoRuta = 'mensajes/' . $videoNombre; // Ruta para guardar en la base de datos
            }
    
            $mensaje = Mensaje::create([
                'numero_origen' => $request->numero_origen,
                'numero_destino' => $request->numero_destino,
                'mensaje' => $request->mensaje,
                'foto_ruta' => $fotoRuta,
                'video_ruta' => $videoRuta,
            ]);
    
            return response()->json(['message' => 'Mensaje enviado correctamente', 'mensaje' => $mensaje], 201);
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al enviar el mensaje: ' . $e->getMessage()], 500);
        }
    }
    public function mostrarMensajes(Request $request)
    {
        // Validar los parámetros de la solicitud
        $request->validate([
            'numero_origen' => 'required|string|max:255',
            'numero_destino' => 'required|string|max:255',
        ]);

        try {
            $numero_origen = $request->input('numero_origen');
            $numero_destino = $request->input('numero_destino');

            // Obtener los mensajes filtrados
            $mensajes = Mensaje::where('numero_origen', $numero_origen)
                ->where('numero_destino', $numero_destino)
                ->get();

            // Si no se encuentran mensajes, se devuelve un mensaje de error
            if ($mensajes->isEmpty()) {
                return response()->json(['message' => 'No se encontraron mensajes para los números dados'], 404);
            }

            // Transformar la respuesta para incluir la URL completa de la foto, si existe
            $mensajes = $mensajes->map(function ($mensaje) {
                $mensajeData = [
                    'mensaje' => $mensaje->mensaje,
                ];

                if ($mensaje->foto_ruta) {
                    $mensajeData['foto_ruta'] = asset($mensaje->foto_ruta);
                }

                return $mensajeData;
            });

            return response()->json(['mensajes' => $mensajes], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al mostrar los mensajes: ' . $e->getMessage()], 500);
        }
    }
    


    public function eliminarMensaje($id)
    {
        try {
            $mensaje = Mensaje::find($id);

            if (!$mensaje) {
                return response()->json(['message' => 'Mensaje no encontrado'], 404);
            }

            // Eliminar la foto adjunta si existe
            if ($mensaje->foto_ruta && file_exists(public_path($mensaje->foto_ruta))) {
                unlink(public_path($mensaje->foto_ruta));
            }

            // Eliminar el video adjunto si existe
            if ($mensaje->video_ruta && file_exists(public_path($mensaje->video_ruta))) {
                unlink(public_path($mensaje->video_ruta));
            }

            $mensaje->delete();

            return response()->json(['message' => 'Mensaje eliminado correctamente'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error al eliminar el mensaje: ' . $e->getMessage()], 500);
        }
    }
    
}
