<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoMail;

class ContactoControllerApi extends Controller
{
    public function enviar(Request $request)
    {
        // Honeypot
        if (!empty($request->input('empresa'))) {
            return response()->json(['error' => 'Solicitud sospechosa.'], 422);
        }

        $data = $request->validate([
    'nombre' => 'required|string|max:255',
    'email' => 'required|email|max:255',
    'telefono' => 'nullable|string|max:20',
    'asunto' => 'required|string|max:255',
    'mensaje' => 'required|string|max:2000',
]);

        Mail::to('bryandavid.yepes@gmail.com')->send(new ContactoMail($data));

        return response()->json(['message' => 'Mensaje enviado exitosamente.']);
    }
}
