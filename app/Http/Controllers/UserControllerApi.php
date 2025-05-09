<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
* Controlador API para gestionar usuarios del sistema
* Maneja operaciones CRUD mediante procedimientos almacenados
*/
class UserControllerApi extends Controller
{
   /**
    * Obtiene todos los usuarios del sistema
    *
    * @return \Illuminate\Http\JsonResponse Lista de usuarios en formato JSON
    */
   public function traerUsuarios()
   {
       try {
           // Ejecuta el procedimiento almacenado que devuelve todos los usuarios
           $usuarios = DB::select('CALL ObtenerUsuarios()');
           return response()->json($usuarios);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al obtener los usuarios',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Obtiene un usuario específico por su ID
    *
    * @param int $id Identificador del usuario
    * @return \Illuminate\Http\JsonResponse Datos del usuario o mensaje de error
    */
   public function llevarUsuario($id)
   {
       try {
           // Ejecuta el procedimiento almacenado con el ID como parámetro
           $usuario = DB::select('CALL ObtenerUsuarioPorId(?)', [$id]);

           // Verifica si se encontraron resultados
           if (!empty($usuario)) {
               return response()->json([
                   'mensaje' => 'Usuario encontrado',
                   'datos' => $usuario[0] // Devuelve solo el primer registro
               ], 200);
           } else {
               // Si no hay resultados, devuelve un 404
               return response()->json([
                   'mensaje' => 'Usuario no encontrado',
               ], 404);
           }
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al obtener el usuario',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Crea un nuevo usuario en el sistema
    *
    * @param \Illuminate\Http\Request $request Datos del nuevo usuario
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function insertarUsuario(Request $request)
   {
       try {
           // Validación completa de los datos de entrada
           $validated = $request->validate([
               'primer_nombre' => 'required|string|max:50',
               'segundo_nombre' => 'nullable|string|max:50',
               'primer_apellido' => 'required|string|max:50',
               'segundo_apellido' => 'nullable|string|max:50',
               'email' => 'required|email|unique:users,email',
               'password' => 'required|string|min:8|confirmed',
               'tipo_identificacion' => 'required|in:Tarjeta de Identidad,Cédula de Ciudadanía,Cédula de Extranjería',
               'numero_identificacion' => 'nullable|string|max:20|unique:users,numero_identificacion',
               'institucion_origen_id' => 'nullable|exists:instituciones,id_institucion',
               'facultad_id' => 'nullable|exists:facultades,id_facultad',
               'telefono' => 'nullable|string|max:20',
               'direccion' => 'nullable|string|max:255',
               'pais_id' => 'nullable|exists:paises,id_pais',
               'departamento_id' => 'nullable|exists:departamentos,id_departamento',
               'municipio_id' => 'nullable|exists:municipios,id_municipio',
               'rol_id' => 'nullable|exists:roles,id_rol',
               'activo' => 'boolean',
           ]);

           // Genera hash seguro para la contraseña
           $password = $request->password ? Hash::make($request->password) : null;

           // Ejecuta el procedimiento almacenado con todos los parámetros
           DB::statement('CALL InsertarUsuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
               $request->primer_nombre,
               $request->segundo_nombre,
               $request->primer_apellido,
               $request->segundo_apellido,
               $request->email,
               $password,
               $request->tipo_identificacion,
               $request->numero_identificacion,
               $request->institucion_origen_id,
               $request->facultad_id,
               $request->telefono,
               $request->direccion,
               $request->pais_id,
               $request->departamento_id,
               $request->municipio_id,
               $request->rol_id ?? 1,            // Valor predeterminado para rol
               $request->activo ?? true          // Valor predeterminado para estado
           ]);

           // Respuesta de éxito con código 201 (Created)
           return response()->json([
               'mensaje' => 'Usuario insertado correctamente'
           ], 201);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al insertar el usuario',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Actualiza los datos de un usuario existente
    *
    * @param \Illuminate\Http\Request $request Nuevos datos del usuario
    * @param int $id Identificador del usuario a actualizar
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function actualizarUsuario(Request $request, $id)
   {
       try {
           // Validación de datos con regla 'sometimes' para permitir actualizaciones parciales
           $validated = $request->validate([
               'primer_nombre' => 'sometimes|required|string|max:50',
               'segundo_nombre' => 'sometimes|nullable|string|max:50',
               'primer_apellido' => 'sometimes|required|string|max:50',
               'segundo_apellido' => 'sometimes|nullable|string|max:50',
                'email' => 'required|email|unique:users,email,' . $id . ',id_usuario',
               'password' => 'sometimes|nullable|string|min:8|confirmed',
               'tipo_identificacion' => 'sometimes|required|in:Tarjeta de Identidad,Cédula de Ciudadanía,Cédula de Extranjería',
               'numero_identificacion' => "sometimes|nullable|string|max:20|unique:users,numero_identificacion,{$id},id_usuario",
               'institucion_origen_id' => 'sometimes|nullable|exists:instituciones,id_institucion',
               'facultad_id' => 'sometimes|nullable|exists:facultades,id_facultad',
               'telefono' => 'sometimes|nullable|string|max:20',
               'direccion' => 'sometimes|nullable|string|max:255',
               'pais_id' => 'sometimes|nullable|exists:paises,id_pais',
               'departamento_id' => 'sometimes|nullable|exists:departamentos,id_departamento',
               'municipio_id' => 'sometimes|nullable|exists:municipios,id_municipio',
               'rol_id' => 'sometimes|nullable|exists:roles,id_rol',
               'activo' => 'sometimes|boolean',
           ]);

           // Solo genera hash de contraseña si se proporciona una nueva
           $password = null;
           if ($request->has('password') && !empty($request->password)) {
               $password = Hash::make($request->password);
           }

           // Ejecuta el procedimiento almacenado con todos los parámetros
           DB::statement('CALL ActualizarUsuario(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
               $id,
               $request->primer_nombre,
               $request->segundo_nombre,
               $request->primer_apellido,
               $request->segundo_apellido,
               $request->email,
               $password,
               $request->tipo_identificacion,
               $request->numero_identificacion,
               $request->institucion_origen_id,
               $request->facultad_id,
               $request->telefono,
               $request->direccion,
               $request->pais_id,
               $request->departamento_id,
               $request->municipio_id,
               $request->rol_id ?? 1,            // Valor predeterminado para rol
               $request->activo ?? true          // Valor predeterminado para estado
           ]);

           // Respuesta de éxito
           return response()->json([
               'mensaje' => 'Usuario actualizado correctamente'
           ], 200);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al actualizar el usuario',
               'error' => $e->getMessage()
           ], 500);
       }
   }

   /**
    * Elimina un usuario del sistema
    *
    * @param int $id Identificador del usuario a eliminar
    * @return \Illuminate\Http\JsonResponse Confirmación o error
    */
   public function eliminarUsuario($id)
   {
       try {
           // Ejecuta el procedimiento almacenado para eliminar un usuario
           DB::statement('CALL EliminarUsuario(?)', [$id]);

           // Respuesta de éxito
           return response()->json([
               'mensaje' => 'Usuario eliminado correctamente'
           ], 200);
       } catch (\Exception $e) {
           // Manejo de errores con respuesta 500
           return response()->json([
               'mensaje' => 'Error al eliminar el usuario',
               'error' => $e->getMessage()
           ], 500);
       }
   }
}
