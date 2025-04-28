<?php

use App\Http\Controllers\API\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AsignaturaControllerApi;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContenidoProgramaticoControllerApi;
use App\Http\Controllers\DocumentoControllerApi;
use App\Http\Controllers\FacultadControllerApi;
use App\Http\Controllers\HistorialHomologacionControllerApi;
use App\Http\Controllers\HomologacionAsignaturaControllerApi;
use App\Http\Controllers\InstitucionControllerApi;
use App\Http\Controllers\MunicipioControllerApi;
use App\Http\Controllers\PaisControllerApi;
use App\Http\Controllers\DepartamentoControllerApi;
use App\Http\Controllers\ProgramaControllerApi;
use App\Http\Controllers\RolControllerApi;
use App\Http\Controllers\SolicitudAsignaturaControllerApi;
use App\Http\Controllers\SolicitudControllerApi;
use App\Http\Controllers\UserControllerApi;
use App\Mail\SendUserPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Rutas públicas para autenticación
Route::post('/register', [RegisteredUserController::class, 'store'])
    ->name('api.register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->name('api.login');

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('api.logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('api.user');
});


// Rutas para países
Route::get('/paises', [PaisControllerApi::class, 'traerPaises'])->name('paises.get');
Route::get('/paises/{id}', [PaisControllerApi::class, 'llevarPais'])->name('paises.getById');
Route::post('/paises', [PaisControllerApi::class, 'insertarPais'])->name('paises.insert');
Route::put('/paises/{id}', [PaisControllerApi::class, 'actualizarPais'])->name('paises.update');
Route::delete('/paises/{id}', [PaisControllerApi::class, 'eliminarPais'])->name('paises.delete');


// Rutas para departamentos
Route::get('/departamentos', [DepartamentoControllerApi::class, 'traerDepartamentos'])->name('departamentos.get');
Route::get('/departamentos/{id}', [DepartamentoControllerApi::class, 'llevarDepartamento'])->name('departamentos.getById');
Route::post('/departamentos', [DepartamentoControllerApi::class, 'insertarDepartamento'])->name('departamentos.insert');
Route::put('/departamentos/{id}', [DepartamentoControllerApi::class, 'actualizarDepartamento'])->name('departamentos.update');
Route::delete('/departamentos/{id}', [DepartamentoControllerApi::class, 'eliminarDepartamento'])->name('departamentos.delete');


// Rutas para municipios
Route::get('/municipios', [MunicipioControllerApi::class, 'traerMunicipios'])->name('municipios.get');
Route::get('/municipios/{id}', [MunicipioControllerApi::class, 'llevarMunicipio'])->name('municipios.getById');
Route::post('/municipios', [MunicipioControllerApi::class, 'insertarMunicipio'])->name('municipios.insert');
Route::put('/municipios/{id}', [MunicipioControllerApi::class, 'actualizarMunicipio'])->name('municipios.update');
Route::delete('/municipios/{id}', [MunicipioControllerApi::class, 'eliminarMunicipio'])->name('municipios.delete');


// Rutas para instituciones
Route::get('/instituciones', [InstitucionControllerApi::class, 'traerInstituciones'])->name('instituciones.get');
Route::get('/instituciones/{id}', [InstitucionControllerApi::class, 'llevarInstitucion'])->name('instituciones.getById');
Route::post('/instituciones', [InstitucionControllerApi::class, 'insertarInstitucion'])->name('instituciones.insert');
Route::put('/instituciones/{id}', [InstitucionControllerApi::class, 'actualizarInstitucion'])->name('instituciones.update');
Route::delete('/instituciones/{id}', [InstitucionControllerApi::class, 'eliminarInstitucion'])->name('instituciones.delete');


// Rutas para programas
Route::get('/programas', [ProgramaControllerApi::class, 'traerProgramas'])->name('programas.get');
Route::get('/programas/{id}', [ProgramaControllerApi::class, 'llevarPrograma'])->name('programas.getById');
Route::post('/programas', [ProgramaControllerApi::class, 'insertarPrograma'])->name('programas.insert');
Route::put('/programas/{id}', [ProgramaControllerApi::class, 'actualizarPrograma'])->name('programas.update');
Route::delete('/programas/{id}', [ProgramaControllerApi::class, 'eliminarPrograma'])->name('programas.delete');


// Rutas para asignaturas
Route::get('/asignaturas', [AsignaturaControllerApi::class, 'traerAsignaturas'])->name('asignaturas.get');
Route::get('/asignaturas/{id}', [AsignaturaControllerApi::class, 'llevarAsignatura'])->name('asignaturas.getById');
Route::post('/asignaturas', [AsignaturaControllerApi::class, 'insertarAsignatura'])->name('asignaturas.insert');
Route::put('/asignaturas/{id}', [AsignaturaControllerApi::class, 'actualizarAsignatura'])->name('asignaturas.update');
Route::delete('/asignaturas/{id}', [AsignaturaControllerApi::class, 'eliminarAsignatura'])->name('asignaturas.delete');


// Rutas para solicitudes
Route::get('/solicitudes', [SolicitudControllerApi::class, 'traerSolicitudes'])->name('solicitudes.get');
Route::get('/solicitudes/{id}', [SolicitudControllerApi::class, 'llevarSolicitud'])->name('solicitudes.getById');
Route::post('/solicitudes', [SolicitudControllerApi::class, 'insertarSolicitud'])->name('solicitudes.insert');
Route::put('/solicitudes/{id}', [SolicitudControllerApi::class, 'actualizarSolicitud'])->name('solicitudes.update');
Route::delete('/solicitudes/{id}', [SolicitudControllerApi::class, 'eliminarSolicitud'])->name('solicitudes.delete');


// Rutas para usuarios
Route::get('/usuarios', [UserControllerApi::class, 'traerUsuarios'])->name('usuarios.get');
Route::get('/usuarios/{id}', [UserControllerApi::class, 'llevarUsuario'])->name('usuarios.getById');
Route::post('/usuarios', [UserControllerApi::class, 'insertarUsuario'])->name('usuarios.insert');
Route::put('/usuarios/{id}', [UserControllerApi::class, 'actualizarUsuario'])->name('usuarios.update');
Route::delete('/usuarios/{id}', [UserControllerApi::class, 'eliminarUsuario'])->name('usuarios.delete');


// Rutas para documentos
Route::get('/documentos', [DocumentoControllerApi::class, 'traerDocumentos'])->name('documentos.get');
Route::get('/documentos/{id}', [DocumentoControllerApi::class, 'llevarDocumento'])->name('documentos.getById');
Route::post('/documentos', [DocumentoControllerApi::class, 'insertarDocumento'])->name('documentos.insert');
Route::put('/documentos/{id}', [DocumentoControllerApi::class, 'actualizarDocumento'])->name('documentos.update');
Route::delete('/documentos/{id}', [DocumentoControllerApi::class, 'eliminarDocumento'])->name('documentos.delete');


// Rutas para facultades
Route::get('/facultades', [FacultadControllerApi::class, 'traerFacultades'])->name('facultades.get');
Route::get('/facultades/{id}', [FacultadControllerApi::class, 'llevarFacultad'])->name('facultades.getById');
Route::post('/facultades', [FacultadControllerApi::class, 'insertarFacultad'])->name('facultades.insert');
Route::put('/facultades/{id}', [FacultadControllerApi::class, 'actualizarFacultad'])->name('facultades.update');
Route::delete('/facultades/{id}', [FacultadControllerApi::class, 'eliminarFacultad'])->name('facultades.delete');


// Rutas para roles
Route::get('/roles', [RolControllerApi::class, 'traerRoles'])->name('roles.get');
Route::get('/roles/{id}', [RolControllerApi::class, 'llevarRol'])->name('roles.getById');
Route::post('/roles', [RolControllerApi::class, 'insertarRol'])->name('roles.insert');
Route::put('/roles/{id}', [RolControllerApi::class, 'actualizarRol'])->name('roles.update');
Route::delete('/roles/{id}', [RolControllerApi::class, 'eliminarRol'])->name('roles.delete');


// Rutas para contenidos programáticos
Route::get('/contenidos-programaticos', [ContenidoProgramaticoControllerApi::class, 'traerContenidosProgramaticos'])->name('contenidosProgramaticos.get');
Route::get('/contenidos-programaticos/{id}', [ContenidoProgramaticoControllerApi::class, 'llevarContenidoProgramatico'])->name('contenidosProgramaticos.getById');
Route::post('/contenidos-programaticos', [ContenidoProgramaticoControllerApi::class, 'insertarContenidoProgramatico'])->name('contenidosProgramaticos.insert');
Route::put('/contenidos-programaticos/{id}', [ContenidoProgramaticoControllerApi::class, 'actualizarContenidoProgramatico'])->name('contenidosProgramaticos.update');
Route::delete('/contenidos-programaticos/{id}', [ContenidoProgramaticoControllerApi::class, 'eliminarContenidoProgramatico'])->name('contenidosProgramaticos.delete');


// Rutas historial_homologaciones
Route::get('/historial-homologaciones', [HistorialHomologacionControllerApi::class, 'traerHistorialHomologaciones'])->name('historialhomologaciones.get');
Route::get('/historial-homologaciones/{id}', [HistorialHomologacionControllerApi::class, 'llevarHistorialHomologacion'])->name('historialhomologaciones.getById');
Route::post('/historial-homologaciones', [HistorialHomologacionControllerApi::class, 'insertarHistorialHomologacion'])->name('historialhomologaciones.insert');
Route::put('/historial-homologaciones/{id}', [HistorialHomologacionControllerApi::class, 'actualizarHistorialHomologacion'])->name('historialhomologaciones.update');
Route::delete('/historial-homologaciones/{id}', [HistorialHomologacionControllerApi::class, 'eliminarHistorialHomologacion'])->name('historialhomologaciones.delete');


// Rutas para solicitud de asignaturas
Route::get('/solicitud-asignaturas', [SolicitudAsignaturaControllerApi::class, 'traerSolicitudAsignaturas'])->name('solicitudAsignaturas.get');
Route::get('/solicitud-asignaturas/{id}', [SolicitudAsignaturaControllerApi::class, 'llevarSolicitudAsignatura'])->name('solicitudAsignaturas.getById');
Route::post('/solicitud-asignaturas', [SolicitudAsignaturaControllerApi::class, 'insertarSolicitudAsignatura'])->name('solicitudAsignaturas.insert');
Route::put('/solicitud-asignaturas/{id}', [SolicitudAsignaturaControllerApi::class, 'actualizarSolicitudAsignatura'])->name('solicitudAsignaturas.update');
Route::delete('/solicitud-asignaturas/{id}', [SolicitudAsignaturaControllerApi::class, 'eliminarSolicitudAsignatura'])->name('solicitudAsignaturas.delete');


// Rutas para homologación de asignaturas
Route::get('/homologacion-asignaturas', [HomologacionAsignaturaControllerApi::class, 'traerHomologacionAsignaturas'])->name('homologacionasignaturas.get');
Route::get('/homologacion-asignaturas/{id}', [HomologacionAsignaturaControllerApi::class, 'llevarHomologacionAsignatura'])->name('homologacionasignaturas.getById');
Route::post('/homologacion-asignaturas', [HomologacionAsignaturaControllerApi::class, 'insertarHomologacionAsignatura'])->name('homologacionasignaturas.insert');
Route::put('/homologacion-asignaturas/{id}', [HomologacionAsignaturaControllerApi::class, 'actualizarHomologacionAsignatura'])->name('homologacionasignaturas.update');
Route::delete('/homologacion-asignaturas/{id}', [HomologacionAsignaturaControllerApi::class, 'eliminarHomologacionAsignatura'])->name('homologacionasignaturas.delete');
