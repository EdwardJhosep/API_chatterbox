<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Listar contactos de un usuario
    public function index(Request $request)
    {
        $user = $request->user();
        $contacts = $user->contacts()->with('contact')->get();

        return response()->json(['contacts' => $contacts]);
    }
}
