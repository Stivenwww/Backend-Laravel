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
     * Constructor que aplica el middleware de autenticación a todas las rutas
     * excepto login y register.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     * Método para iniciar sesión verificando credenciales y devolviendo un token JWT.
     * También guarda el token en una cookie.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        // Validación de campos requeridos
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        // Si la validación falla, devuelve los errores
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Intenta autenticar con las credenciales proporcionadas
        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Credenciales no válidas'], 401);
        }

        // Guarda el token JWT en una cookie HTTP-only para mayor seguridad
        $cookie = cookie('jwt_token', $token, auth()->factory()->getTTL(), '/', null, false, true);

        // Devuelve la respuesta con el token y la cookie
        return $this->createNewToken($token)->withCookie($cookie);
    }

    /**
     * Register a User.
     * Método para registrar un nuevo usuario en el sistema,
     * genera una contraseña aleatoria y envía un correo con las credenciales.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        // Validación de campos para el registro
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:100|unique:users',
            'tipo_identificacion' => 'required|string',
            'numero_identificacion' => 'required|string|unique:users',
        ]);

        // Si la validación falla, devuelve los errores
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Genera una contraseña aleatoria de 10 caracteres
        $password = Str::random(10);

        // Crea el nuevo usuario en la base de datos
        $user = User::create([
            'email' => $request->email,
            'tipo_identificacion' => $request->tipo_identificacion,
            'numero_identificacion' => $request->numero_identificacion,
            'password' => Hash::make($password), // Encripta la contraseña
        ]);

        // Envía un correo electrónico con las credenciales al nuevo usuario
        Mail::to($user->email)
            ->send(new SoporteMailable($user->email, $password));

        // Devuelve respuesta exitosa con los datos del usuario creado
        return response()->json([
            'message' => 'Usuario registrado exitosamente. Se ha enviado un correo con las credenciales.',
            'user' => $user
        ], 201);
    }

    /**
     * Log the user out (Invalidate the token).
     * Método para cerrar la sesión del usuario, invalidando el token
     * y eliminando la cookie.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        // Cierra la sesión del guard 'api' (JWT)
        auth()->logout();

        // También cierra la sesión en el guard 'web' por si acaso
        auth('web')->logout();

        // Elimina la cookie del token JWT estableciendo tiempo de expiración negativo
        $cookie = cookie('jwt_token', '', -1);

        // Devuelve mensaje de éxito con la cookie eliminada
        return response()->json(['message' => 'Sesión cerrada exitosamente'])->withCookie($cookie);
    }

    /**
     * Refresh a token.
     * Método para refrescar el token JWT cuando está próximo a expirar.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        // Obtiene un nuevo token conservando la identidad del usuario
        $token = auth()->refresh();

        // Crea la respuesta con el token actualizado y actualiza la cookie
        $response = $this->createNewToken($token);
        $cookie = cookie('jwt_token', $token, auth()->factory()->getTTL(), '/', null, false, true);

        // Devuelve la respuesta con la cookie actualizada
        return $response->withCookie($cookie);
    }

    /**
     * Get the authenticated User.
     * Método para obtener los datos del usuario autenticado actualmente.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        // Devuelve los datos del usuario autenticado
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     * Método auxiliar para crear una estructura de respuesta consistente
     * con el token JWT y los datos del usuario.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        // Estructura la respuesta con el token, tipo, tiempo de expiración y datos del usuario
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60, // Convierte minutos a segundos
            'user' => auth()->user()
        ]);
    }

    /**
     * Check if user is authenticated.
     * Método para verificar si el usuario está autenticado,
     * útil para validar sesiones desde el frontend.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkAuth()
    {
        try {
            // Intenta obtener el usuario autenticado con el guard 'api'
            if (!$user = auth('api')->user()) {
                return response()->json(['error' => 'No autenticado'], 401);
            }

            // Si tiene usuario, devuelve confirmación y datos
            return response()->json([
                'authenticated' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            // Captura cualquier excepción como un error de autenticación
            return response()->json(['error' => 'No autenticado'], 401);
        }
    }
}
