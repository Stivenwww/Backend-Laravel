<?php

use App\Http\Controllers\SolicitudCompletaControllerApi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\PaisControllerApi;
use App\Http\Controllers\DepartamentoControllerApi;
use App\Http\Controllers\MunicipioControllerApi;
use App\Http\Controllers\InstitucionControllerApi;
use App\Http\Controllers\ProgramaControllerApi;
use App\Http\Controllers\AsignaturaControllerApi;
use App\Http\Controllers\SolicitudControllerApi;
use App\Http\Controllers\SolicitudAsignaturaControllerApi;
use App\Http\Controllers\HomologacionAsignaturaControllerApi;
use App\Http\Controllers\HistorialHomologacionControllerApi;
use App\Http\Controllers\UserControllerApi;
use App\Http\Controllers\DocumentoControllerApi;
use App\Http\Controllers\FacultadControllerApi;
use App\Http\Controllers\RolControllerApi;
use App\Http\Controllers\ContenidoProgramaticoControllerApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Aquí puedes registrar las rutas de tu API. Están cargadas
| en el grupo 'api' dentro de RouteServiceProvider.
|
| ⚠️ Actualmente NO se está usando autenticación en las rutas de recursos.
| Solo se definieron las rutas de login y registro. Las rutas protegidas
| están comentadas y listas para usarse cuando se implemente JWT correctamente.
*/

// ✅ Ruta de prueba para verificar autenticación (requiere auth:api)
Route::get('/auth/check', [AuthController::class, 'checkAuth'])
    ->middleware('auth:api'); // Esta ruta sí usa autenticación con Passport

// 🔓 Rutas públicas de autenticación (login, registro)
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
});

// 🔒 Rutas protegidas por JWT (aún no activas globalmente)
Route::group(['middleware' => ['jwt.verify'], 'prefix' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user-profile', [AuthController::class, 'userProfile']);
});



// 🔓 Rutas públicas de recursos CRUD (actualmente sin autenticación)
/* Route::group(['middleware' => ['jwt.verify']], function () { */

//Países
Route::get('paises', [PaisControllerApi::class, 'traerPaises']);
Route::get('paises/{id}', [PaisControllerApi::class, 'llevarPais']);
Route::post('paises', [PaisControllerApi::class, 'insertarPais']);
Route::put('paises/{id}', [PaisControllerApi::class, 'actualizarPais']);
Route::delete('paises/{id}', [PaisControllerApi::class, 'eliminarPais']);

// Departamentos
Route::get('departamentos', [DepartamentoControllerApi::class, 'traerDepartamentos']);
Route::get('departamentos/{id}', [DepartamentoControllerApi::class, 'llevarDepartamento']);
Route::post('departamentos', [DepartamentoControllerApi::class, 'insertarDepartamento']);
Route::put('departamentos/{id}', [DepartamentoControllerApi::class, 'actualizarDepartamento']);
Route::delete('departamentos/{id}', [DepartamentoControllerApi::class, 'eliminarDepartamento']);

// Municipios
Route::get('municipios', [MunicipioControllerApi::class, 'traerMunicipios']);
Route::get('municipios/{id}', [MunicipioControllerApi::class, 'llevarMunicipio']);
Route::post('municipios', [MunicipioControllerApi::class, 'insertarMunicipio']);
Route::put('municipios/{id}', [MunicipioControllerApi::class, 'actualizarMunicipio']);
Route::delete('municipios/{id}', [MunicipioControllerApi::class, 'eliminarMunicipio']);

// Instituciones
Route::get('instituciones', [InstitucionControllerApi::class, 'traerInstituciones']);
Route::get('instituciones/{id}', [InstitucionControllerApi::class, 'llevarInstitucion']);
Route::post('instituciones', [InstitucionControllerApi::class, 'insertarInstitucion']);
Route::put('instituciones/{id}', [InstitucionControllerApi::class, 'actualizarInstitucion']);
Route::delete('instituciones/{id}', [InstitucionControllerApi::class, 'eliminarInstitucion']);

// Programas
Route::get('programas', [ProgramaControllerApi::class, 'traerProgramas']);
Route::get('programas/{id}', [ProgramaControllerApi::class, 'llevarPrograma']);
Route::post('programas', [ProgramaControllerApi::class, 'insertarPrograma']);
Route::put('programas/{id}', [ProgramaControllerApi::class, 'actualizarPrograma']);
Route::delete('programas/{id}', [ProgramaControllerApi::class, 'eliminarPrograma']);

// Asignaturas
Route::get('asignaturas', [AsignaturaControllerApi::class, 'traerAsignaturas']);
Route::get('asignaturas/{id}', [AsignaturaControllerApi::class, 'llevarAsignatura']);
Route::post('asignaturas', [AsignaturaControllerApi::class, 'insertarAsignatura']);
Route::put('asignaturas/{id}', [AsignaturaControllerApi::class, 'actualizarAsignatura']);
Route::delete('asignaturas/{id}', [AsignaturaControllerApi::class, 'eliminarAsignatura']);
Route::get('asignaturas/programa/{id_programa}', [AsignaturaControllerApi::class, 'traerAsignaturasPorPrograma']);
Route::get('asignaturas/programa/{id_programa}/asignatura/{id_asignatura}', [AsignaturaControllerApi::class, 'llevarAsignaturaPorPrograma']);

// Solicitudes
Route::get('solicitudes', [SolicitudControllerApi::class, 'traerSolicitudes']);
Route::get('solicitudes/{id}', [SolicitudControllerApi::class, 'llevarSolicitud']);
Route::post('solicitudes', [SolicitudControllerApi::class, 'insertarSolicitud']);
Route::put('solicitudes/{id}', [SolicitudControllerApi::class, 'actualizarSolicitud']);
Route::patch('solicitudes/{id}/estado', [SolicitudControllerApi::class, 'actualizarEstadoSolicitud']);
Route::delete('solicitudes/{id}', [SolicitudControllerApi::class, 'eliminarSolicitud']);

// Solicitud - Asignaturas
Route::get('solicitud-asignaturas', [SolicitudAsignaturaControllerApi::class, 'traerSolicitudAsignaturas']);
Route::get('solicitud-asignaturas/{id}', [SolicitudAsignaturaControllerApi::class, 'llevarSolicitudAsignatura']);
Route::post('solicitud-asignaturas', [SolicitudAsignaturaControllerApi::class, 'insertarSolicitudAsignatura']);
Route::put('solicitud-asignaturas/{id}', [SolicitudAsignaturaControllerApi::class, 'actualizarSolicitudAsignatura']);
Route::delete('solicitud-asignaturas/{id}', [SolicitudAsignaturaControllerApi::class, 'eliminarSolicitudAsignatura']);
Route::get('solicitudes/usuario/{id_usuario}', [SolicitudControllerApi::class, 'obtenerSolicitudesPorUsuario']);

// Homologación - Asignaturas
Route::get('homologacion-asignaturas', [HomologacionAsignaturaControllerApi::class, 'traerHomologacionAsignaturas']);
Route::get('homologacion-asignaturas/{id}', [HomologacionAsignaturaControllerApi::class, 'llevarHomologacionAsignatura']);
Route::post('homologacion-asignaturas', [HomologacionAsignaturaControllerApi::class, 'insertarHomologacionAsignatura']);
Route::put('homologacion-asignaturas/{id}', [HomologacionAsignaturaControllerApi::class, 'actualizarHomologacionAsignatura']);
Route::delete('homologacion-asignaturas/{id}', [HomologacionAsignaturaControllerApi::class, 'eliminarHomologacionAsignatura']);
Route::delete('homologacion-asignaturas/{id}/destinos', [HomologacionAsignaturaControllerApi::class, 'eliminarAsignaturasDestino']);
Route::post('homologacion-asignaturas/{id}/pdf', [HomologacionAsignaturaControllerApi::class, 'actualizarPDFResolucion']);
// Historial de homologaciones
Route::get('historial-homologaciones', [HistorialHomologacionControllerApi::class, 'traerHistorialHomologaciones']);
Route::get('historial-homologaciones/{id}', [HistorialHomologacionControllerApi::class, 'llevarHistorialHomologacion']);
Route::post('historial-homologaciones', [HistorialHomologacionControllerApi::class, 'insertarHistorialHomologacion']);
Route::put('historial-homologaciones/{id}', [HistorialHomologacionControllerApi::class, 'actualizarHistorialHomologacion']);
Route::delete('historial-homologaciones/{id}', action: [HistorialHomologacionControllerApi::class, 'eliminarHistorialHomologacion']);

// Usuarios
Route::get('usuarios', [UserControllerApi::class, 'traerUsuarios']);
Route::get('usuarios/{id}', [UserControllerApi::class, 'llevarUsuario']);
Route::post('usuarios', [UserControllerApi::class, 'insertarUsuario']);
Route::put('usuarios/{id}', [UserControllerApi::class, 'actualizarUsuario']);
Route::delete('usuarios/{id}', [UserControllerApi::class, 'eliminarUsuario']);

// Documentos
Route::get('documentos', [DocumentoControllerApi::class, 'traerDocumentos']);
Route::get('documentos/{id}', [DocumentoControllerApi::class, 'llevarDocumento']);
Route::post('documentos', [DocumentoControllerApi::class, 'insertarDocumento']);
Route::put('documentos/{id}', [DocumentoControllerApi::class, 'actualizarDocumento']);
Route::delete('documentos/{id}', [DocumentoControllerApi::class, 'eliminarDocumento']);
Route::get('documentos/usuario/{usuario_id}', [DocumentoControllerApi::class, 'obtenerDocumentosPorUsuario']);

// Facultades
Route::get('facultades', [FacultadControllerApi::class, 'traerFacultades']);
Route::get('facultades/{id}', [FacultadControllerApi::class, 'llevarFacultad']);
Route::post('facultades', [FacultadControllerApi::class, 'insertarFacultad']);
Route::put('facultades/{id}', [FacultadControllerApi::class, 'actualizarFacultad']);
Route::delete('facultades/{id}', [FacultadControllerApi::class, 'eliminarFacultad']);

// Roles
Route::get('roles', [RolControllerApi::class, 'traerRoles']);
Route::get('roles/{id}', [RolControllerApi::class, 'llevarRol']);
Route::post('roles', [RolControllerApi::class, 'insertarRol']);
Route::put('roles/{id}', [RolControllerApi::class, 'actualizarRol']);
Route::delete('roles/{id}', [RolControllerApi::class, 'eliminarRol']);

// Contenidos Programáticos
Route::get('contenidos-programaticos', [ContenidoProgramaticoControllerApi::class, 'traerContenidosProgramaticos']);
Route::get('contenidos-programaticos/{id}', [ContenidoProgramaticoControllerApi::class, 'llevarContenidoProgramatico']);
Route::post('contenidos-programaticos', [ContenidoProgramaticoControllerApi::class, 'insertarContenidoProgramatico']);
Route::put('contenidos-programaticos/{id}', [ContenidoProgramaticoControllerApi::class, 'actualizarContenidoProgramatico']);
Route::delete('contenidos-programaticos/{id}', [ContenidoProgramaticoControllerApi::class, 'eliminarContenidoProgramatico']);
Route::get('contenidos-programaticos/asignatura/{asignatura_id}', [ContenidoProgramaticoControllerApi::class, 'obtenerContenidosPorAsignatura']);

// });

// En routes/api.php
Route::match(['post', 'put'], '/solicitud-actualizar', [SolicitudCompletaControllerApi::class, 'update']);
Route::post('/solicitud-completa', [SolicitudCompletaControllerApi::class, 'store']);
