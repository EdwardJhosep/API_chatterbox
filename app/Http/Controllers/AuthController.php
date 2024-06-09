<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

use App\Models\User;

class AuthController extends Controller
{
    /**
     * Registro
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Generar un número de 9 dígitos para mobile_number
        $mobileNumber = strval(mt_rand(100000000, 999999999));

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'mobile_number' => $mobileNumber,
        ]);

        return response()->json(['message' => 'Usuario registrado correctamente', 'user' => $user]);
    }

    /**
     * Login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            return response()->json(['message' => 'Sesión iniciada correctamente', 'user' => $user]);
        } else {
            return response()->json(['error' => 'Credenciales inválidas'], 401);
        }
    }

    /**
     * Logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        auth()->logout();

        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

    /**
     * Subir imagen de perfil
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'id' => 'required|exists:users,id',
        ]);
    
        $user = User::findOrFail($request->input('id'));
    
        // Eliminar el avatar anterior si existe
        if ($user->avatar) {
            $avatarPath = public_path('perfil/' . $user->avatar);
            if (file_exists($avatarPath)) {
                unlink($avatarPath);
            }
        }
    
        // Obtener el archivo de imagen del request
        $avatar = $request->file('avatar');
    
        // Generar un nombre único para la imagen
        $avatarName = $user->id.'_avatar'.time().'.'.$avatar->getClientOriginalExtension();
    
        // Definir la ruta donde se guardará la imagen (public/perfil)
        $avatarPath = 'perfil/';
    
        // Almacenar la imagen en la carpeta public/perfil
        $avatar->move(public_path($avatarPath), $avatarName);
    
        // Guardar el nombre del archivo en el campo 'avatar' de la tabla users
        $user->avatar = $avatarName;
        $user->save();
    
        return response()->json(['message' => 'Avatar subido correctamente']);
    }
}
