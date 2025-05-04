<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SoporteMailable;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;


class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Credenciales no válidas'], 401);
        }

        // Guardar el token en cookie
        $cookie = cookie('jwt_token', $token, auth()->factory()->getTTL(), '/', null, false, true);

        return $this->createNewToken($token)->withCookie($cookie);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
            'tipo_identificacion' => 'required|string',
            'numero_identificacion' => 'required|string|unique:users',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Generar contraseña automática
        $password = Str::random(10);

        $user = User::create([
            'name' => $request->name ?? explode('@', $request->email)[0],
            'email' => $request->email,
            'tipo_identificacion' => $request->tipo_identificacion,
            'numero_identificacion' => $request->numero_identificacion,
            'password' => Hash::make($password),
        ]);

        // Aquí iría el código para enviar el correo con las credenciales
        // AuthController.php, método register()
        Mail::to($user->email)
            ->send(new SoporteMailable($user->email, $password));


        return response()->json([
            'message' => 'Usuario registrado exitosamente. Se ha enviado un correo con las credenciales.',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        // También cerrar sesión en guard 'web'
        auth('web')->logout();

        // Eliminar la cookie del token JWT
        $cookie = cookie('jwt_token', '', -1);

        return response()->json(['message' => 'Sesión cerrada exitosamente'])->withCookie($cookie);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        $token = auth()->refresh();

        // Crear la respuesta con el token actualizado y actualizar la cookie
        $response = $this->createNewToken($token);
        $cookie = cookie('jwt_token', $token, auth()->factory()->getTTL(), '/', null, false, true);

        return $response->withCookie($cookie);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
    
    public function checkAuth()
    {
        try {
            if (!$user = auth('api')->user()) {
                return response()->json(['error' => 'No autenticado'], 401);
            }

            return response()->json([
                'authenticated' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No autenticado'], 401);
        }
    }
}
