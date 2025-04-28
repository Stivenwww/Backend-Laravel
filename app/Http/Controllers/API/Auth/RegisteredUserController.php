<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Mail\SendUserPassword;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request via API.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'tipo_identificacion' => ['required'],
            'numero_identificacion' => ['required', 'string', 'max:20', 'unique:users,numero_identificacion'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generar una contraseña aleatoria segura
        $generatedPassword = Str::random(10);

        // Crear el usuario
        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'tipo_identificacion' => $request->tipo_identificacion,
            'numero_identificacion' => $request->numero_identificacion,
        ]);

        // Disparar evento de registro (por si usas email verification u otros)
        event(new Registered($user));

        // Enviar la contraseña al correo del usuario
        Mail::to($user->email)->send(new SendUserPassword($user->email, $generatedPassword));

        // Crear token para API (si usas Sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso. Revisa tu correo para ver tu contraseña.',
            'user' => $user,
            'token' => $token
        ], 201);
    }
}
