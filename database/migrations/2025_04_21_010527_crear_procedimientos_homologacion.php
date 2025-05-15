<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (PAISES)
            DROP PROCEDURE IF EXISTS ActualizarPais;
            DROP PROCEDURE IF EXISTS EliminarPais;
            DROP PROCEDURE IF EXISTS InsertarPais;
            DROP PROCEDURE IF EXISTS ObtenerPaisPorId;
            DROP PROCEDURE IF EXISTS ObtenerPaises;

            -- ACTUALIZAR PAIS
            CREATE PROCEDURE ActualizarPais(IN paisId SMALLINT, IN nombrePais VARCHAR(100))
            BEGIN
                UPDATE paises SET nombre = nombrePais WHERE id_pais = paisId;
            END;

            -- ELIMINAR PAIS
            CREATE PROCEDURE EliminarPais(IN paisId SMALLINT)
            BEGIN
                DELETE FROM paises WHERE id_pais = paisId;
            END;

            -- INSERTAR PAIS
            CREATE PROCEDURE InsertarPais(IN nombrePais VARCHAR(100))
            BEGIN
                INSERT INTO paises (nombre) VALUES (nombrePais);
            END;

            -- OBTENER PAIS POR ID
            CREATE PROCEDURE ObtenerPaisPorId(IN paisId SMALLINT)
            BEGIN
                SELECT p.id_pais, p.nombre AS pais
                FROM paises p
                WHERE p.id_pais = paisId;
            END;

            -- OBTENER TODOS LOS PAISES
            CREATE PROCEDURE ObtenerPaises()
            BEGIN
                SELECT p.id_pais, p.nombre AS pais
                FROM paises p
                ORDER BY p.nombre ASC;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (DEPARTAMENTOS)
            DROP PROCEDURE IF EXISTS ActualizarDepartamento;
            DROP PROCEDURE IF EXISTS EliminarDepartamento;
            DROP PROCEDURE IF EXISTS InsertarDepartamento;
            DROP PROCEDURE IF EXISTS ObtenerDepartamentoPorId;
            DROP PROCEDURE IF EXISTS ObtenerDepartamentos;

            -- ACTUALIZAR DEPARTAMENTO
            CREATE PROCEDURE ActualizarDepartamento(
                IN departamentoId SMALLINT,
                IN nombreDepartamento VARCHAR(255),
                IN paisId SMALLINT
            )
            BEGIN
                UPDATE departamentos
                SET nombre = nombreDepartamento,
                    pais_id = paisId
                WHERE id_departamento = departamentoId;
            END;

            -- ELIMINAR DEPARTAMENTO
            CREATE PROCEDURE EliminarDepartamento(IN departamentoId SMALLINT)
            BEGIN
                DELETE FROM departamentos WHERE id_departamento = departamentoId;
            END;

            -- INSERTAR DEPARTAMENTO
            CREATE PROCEDURE InsertarDepartamento(
                IN nombreDepartamento VARCHAR(255),
                IN paisId SMALLINT
            )
            BEGIN
                INSERT INTO departamentos (nombre, pais_id)
                VALUES (nombreDepartamento, paisId);
            END;

            -- OBTENER DEPARTAMENTO POR ID
            CREATE PROCEDURE ObtenerDepartamentoPorId(IN departamentoId SMALLINT)
            BEGIN
                SELECT d.id_departamento, d.nombre AS departamento, p.nombre AS pais
                FROM departamentos d
                JOIN paises p ON d.pais_id = p.id_pais
                WHERE d.id_departamento = departamentoId;
            END;

            -- OBTENER TODOS LOS DEPARTAMENTOS
            CREATE PROCEDURE ObtenerDepartamentos()
            BEGIN
                SELECT d.id_departamento, d.nombre AS departamento, p.nombre AS pais
                FROM departamentos d
                JOIN paises p ON d.pais_id = p.id_pais
                ORDER BY d.nombre ASC;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (MUNICIPIOS)
            DROP PROCEDURE IF EXISTS ActualizarMunicipio;
            DROP PROCEDURE IF EXISTS EliminarMunicipio;
            DROP PROCEDURE IF EXISTS InsertarMunicipio;
            DROP PROCEDURE IF EXISTS ObtenerMunicipioPorId;
            DROP PROCEDURE IF EXISTS ObtenerMunicipios;

            -- ACTUALIZAR MUNICIPIO
            CREATE PROCEDURE ActualizarMunicipio(
                IN municipioId SMALLINT,
                IN nombreMunicipio VARCHAR(255),
                IN departamentoId SMALLINT
            )
            BEGIN
                UPDATE municipios
                SET nombre = nombreMunicipio, departamento_id = departamentoId
                WHERE id_municipio = municipioId;
            END;

            -- ELIMINAR MUNICIPIO
            CREATE PROCEDURE EliminarMunicipio(IN municipioId SMALLINT)
            BEGIN
                DELETE FROM municipios WHERE id_municipio = municipioId;
            END;

            CREATE PROCEDURE InsertarMunicipio(
                IN nombreMunicipio VARCHAR(255),
                IN departamentoId SMALLINT
            )
            BEGIN
                INSERT INTO municipios (nombre, departamento_id)
                VALUES (nombreMunicipio, departamentoId);
            END;

            -- OBTENER MUNICIPIO POR ID
            CREATE PROCEDURE ObtenerMunicipioPorId(IN municipioId SMALLINT)
            BEGIN
                SELECT m.id_municipio, m.nombre AS municipio, d.nombre AS departamento, p.nombre AS pais
                FROM municipios m
                JOIN departamentos d ON m.departamento_id = d.id_departamento
                JOIN paises p ON d.pais_id = p.id_pais
                WHERE m.id_municipio = municipioId;
            END;

            -- OBTENER TODOS LOS MUNICIPIOS
            CREATE PROCEDURE ObtenerMunicipios()
            BEGIN
                SELECT m.id_municipio, m.nombre AS municipio, d.nombre AS departamento, p.nombre AS pais
                FROM municipios m
                JOIN departamentos d ON m.departamento_id = d.id_departamento
                JOIN paises p ON d.pais_id = p.id_pais
                ORDER BY m.nombre ASC;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (INSTITUCIONES)
            DROP PROCEDURE IF EXISTS ActualizarInstitucion;
            DROP PROCEDURE IF EXISTS EliminarInstitucion;
            DROP PROCEDURE IF EXISTS InsertarInstitucion;
            DROP PROCEDURE IF EXISTS ObtenerInstitucionPorId;
            DROP PROCEDURE IF EXISTS ObtenerInstituciones;

            -- ACTUALIZAR INSTITUCION
            CREATE PROCEDURE ActualizarInstitucion(
                IN institucionId SMALLINT,
                IN p_nombre VARCHAR(255),
                IN p_codigo_ies VARCHAR(50),
                IN p_municipio_id SMALLINT,
                IN p_tipo VARCHAR(50)
            )
            BEGIN
                UPDATE instituciones
                SET nombre = p_nombre,
                    codigo_ies = p_codigo_ies,
                    municipio_id = p_municipio_id,
                    tipo = p_tipo
                WHERE id_institucion = institucionId;
            END;

            -- ELIMINAR INSTITUCION
            CREATE PROCEDURE EliminarInstitucion(IN institucionId SMALLINT)
            BEGIN
                DELETE FROM instituciones WHERE id_institucion = institucionId;
            END;

            CREATE PROCEDURE InsertarInstitucion(
                IN nombreInstitucion VARCHAR(255),
                IN codigoIes VARCHAR(20),
                IN municipioId SMALLINT,
                IN tipoInstitucion VARCHAR(100)
            )
            BEGIN
                INSERT INTO instituciones (nombre, codigo_ies, municipio_id, tipo)
                VALUES (nombreInstitucion, codigoIes, municipioId, tipoInstitucion);
            END;

            -- OBTENER INSTITUCION POR ID
            CREATE PROCEDURE ObtenerInstitucionPorId(IN institucionId SMALLINT)
            BEGIN
                SELECT i.id_institucion,
                       i.nombre,
                       i.codigo_ies,
                       m.nombre AS municipio,
                       d.nombre AS departamento,
                       i.tipo
                FROM instituciones i
                JOIN municipios m ON i.municipio_id = m.id_municipio
                JOIN departamentos d ON m.departamento_id = d.id_departamento
                WHERE i.id_institucion = institucionId;
            END;

            -- OBTENER TODAS LAS INSTITUCIONES
            CREATE PROCEDURE ObtenerInstituciones()
            BEGIN
                SELECT i.id_institucion,
                       i.nombre,
                       i.codigo_ies,
                       m.nombre AS municipio,
                       d.nombre AS departamento,
                       i.tipo
                FROM instituciones i
                JOIN municipios m ON i.municipio_id = m.id_municipio
                JOIN departamentos d ON m.departamento_id = d.id_departamento
                ORDER BY i.nombre ASC;
            END;




            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (FACULTADES)
            DROP PROCEDURE IF EXISTS ObtenerFacultades;
            DROP PROCEDURE IF EXISTS ObtenerFacultadPorId;
            DROP PROCEDURE IF EXISTS InsertarFacultad;
            DROP PROCEDURE IF EXISTS ActualizarFacultad;
            DROP PROCEDURE IF EXISTS EliminarFacultad;

            -- OBTENER TODAS LAS FACULTADES
            CREATE PROCEDURE ObtenerFacultades()
            BEGIN
                SELECT
                    f.id_facultad,
                    i.nombre AS institucion,
                    f.nombre
                FROM facultades f
                LEFT JOIN instituciones i ON f.institucion_id = i.id_institucion
                ORDER BY f.nombre ASC;
            END;

            -- OBTENER FACULTADES POR ID
            CREATE PROCEDURE ObtenerFacultadPorId(IN facultadId SMALLINT)
            BEGIN
                SELECT
                    f.id_facultad,
                    i.nombre AS institucion,
                    f.nombre
                FROM facultades f
                LEFT JOIN instituciones i ON f.institucion_id = i.id_institucion
                WHERE f.id_facultad = facultadId;
            END;

            -- INSERTAR FACULTAD
            CREATE PROCEDURE InsertarFacultad(
                IN p_institucion_id SMALLINT,
                IN p_nombre VARCHAR(255)
            )
            BEGIN
                INSERT INTO facultades (institucion_id, nombre)
                VALUES (p_institucion_id, p_nombre);
            END;

            -- ACTUALIZAR FACULTAD
            CREATE PROCEDURE ActualizarFacultad(
                IN facultadId SMALLINT,
                IN p_institucion_id SMALLINT,
                IN p_nombre VARCHAR(255)
            )
            BEGIN
                UPDATE facultades
                SET institucion_id = p_institucion_id,
                    nombre = p_nombre
                WHERE id_facultad = facultadId;
            END;

            -- ELIMINAR FACULTAD
            CREATE PROCEDURE EliminarFacultad(IN facultadId SMALLINT)
            BEGIN
                DELETE FROM facultades WHERE id_facultad = facultadId;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN
            DROP PROCEDURE IF EXISTS ActualizarPrograma;
            DROP PROCEDURE IF EXISTS EliminarPrograma;
            DROP PROCEDURE IF EXISTS InsertarPrograma;
            DROP PROCEDURE IF EXISTS ObtenerProgramaPorId;
            DROP PROCEDURE IF EXISTS ObtenerProgramas;

            -- ACTUALIZAR PROGRAMA
            CREATE PROCEDURE ActualizarPrograma(
                IN programaId        SMALLINT,
                IN p_institucion_id  SMALLINT,
                IN p_facultad_id     SMALLINT,
                IN p_nombre          VARCHAR(255),
                IN p_codigo_snies    VARCHAR(20),
                IN p_tipo_formacion  ENUM('Técnico','Tecnólogo','Profesional'),
                IN p_metodologia     ENUM('Presencial','Virtual','Híbrido')
            )
            BEGIN
                UPDATE programas
                SET institucion_id   = p_institucion_id,
                    facultad_id      = p_facultad_id,
                    nombre           = p_nombre,
                    codigo_snies     = p_codigo_snies,
                    tipo_formacion   = p_tipo_formacion,
                    metodologia      = p_metodologia
                WHERE id_programa = programaId;
            END;

            -- ELIMINAR PROGRAMA
            CREATE PROCEDURE EliminarPrograma(
                IN programaId SMALLINT
            )
            BEGIN
                DELETE FROM programas
                WHERE id_programa = programaId;
            END;

            -- INSERTAR PROGRAMA
            CREATE PROCEDURE InsertarPrograma(
                IN p_institucion_id SMALLINT,
                IN p_facultad_id    SMALLINT,
                IN p_nombre         VARCHAR(255),
                IN p_codigo_snies   VARCHAR(20),
                IN p_tipo_formacion ENUM('Técnico','Tecnólogo','Profesional'),
                IN p_metodologia    ENUM('Presencial','Virtual','Híbrido')
            )
            BEGIN
                INSERT INTO programas (
                    institucion_id,
                    facultad_id,
                    nombre,
                    codigo_snies,
                    tipo_formacion,
                    metodologia
                ) VALUES (
                    p_institucion_id,
                    p_facultad_id,
                    p_nombre,
                    p_codigo_snies,
                    p_tipo_formacion,
                    p_metodologia
                );
            END;

            -- OBTENER PROGRAMA POR ID
            CREATE PROCEDURE ObtenerProgramaPorId(
                IN programaId SMALLINT
            )
            BEGIN
                SELECT
                    pr.id_programa,
                    pr.nombre          AS programa,
                    i.nombre           AS institucion,
                    f.nombre           AS facultad,
                    pr.codigo_snies,
                    pr.tipo_formacion,
                    pr.metodologia
                FROM programas pr
                JOIN instituciones i ON pr.institucion_id = i.id_institucion
                LEFT JOIN facultades   f ON pr.facultad_id    = f.id_facultad
                WHERE pr.id_programa = programaId;
            END;

            -- OBTENER TODOS LOS PROGRAMAS
            CREATE PROCEDURE ObtenerProgramas()
            BEGIN
                SELECT
                    pr.id_programa,
                    pr.nombre          AS programa,
                    i.nombre           AS institucion,
                    f.nombre           AS facultad,
                    pr.codigo_snies,
                    pr.tipo_formacion,
                    pr.metodologia
                FROM programas pr
                JOIN instituciones i ON pr.institucion_id = i.id_institucion
                LEFT JOIN facultades   f ON pr.facultad_id    = f.id_facultad
                ORDER BY pr.nombre ASC;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (ROLES)
            DROP PROCEDURE IF EXISTS ObtenerRoles;
            DROP PROCEDURE IF EXISTS ObtenerRolPorId;
            DROP PROCEDURE IF EXISTS InsertarRol;
            DROP PROCEDURE IF EXISTS ActualizarRol;
            DROP PROCEDURE IF EXISTS EliminarRol;

            -- OBTENER TODOS LOS ROLES
            CREATE PROCEDURE ObtenerRoles()
            BEGIN
                SELECT * FROM roles ORDER BY id_rol ASC;
            END;

            -- OBTENER ROL POR ID
            CREATE PROCEDURE ObtenerRolPorId(IN rolId SMALLINT)
            BEGIN
                SELECT * FROM roles WHERE id_rol = rolId;
            END;

            -- INSERTAR ROL
            CREATE PROCEDURE InsertarRol(
                IN p_nombre VARCHAR(50)
            )
            BEGIN
                INSERT INTO roles (nombre)
                VALUES (p_nombre);
            END;

            -- ACTUALIZAR ROL
            CREATE PROCEDURE ActualizarRol(
                IN rolId SMALLINT,
                IN p_nombre VARCHAR(50)
            )
            BEGIN
                UPDATE roles
                SET nombre = p_nombre
                WHERE id_rol = rolId;
            END;

            -- ELIMINAR ROL
            CREATE PROCEDURE EliminarRol(IN rolId SMALLINT)
            BEGIN
                DELETE FROM roles WHERE id_rol = rolId;
            END;




            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (users)
            DROP PROCEDURE IF EXISTS ObtenerUsuarios;
            DROP PROCEDURE IF EXISTS ObtenerUsuarioPorId;
            DROP PROCEDURE IF EXISTS InsertarUsuario;
            DROP PROCEDURE IF EXISTS ActualizarUsuario;
            DROP PROCEDURE IF EXISTS EliminarUsuario;

            CREATE PROCEDURE ObtenerUsuarios()
            BEGIN
                SELECT
                    u.id_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.password,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    i.nombre AS institucion_origen,
                    f.nombre AS facultad,
                    u.telefono,
                    u.direccion,
                    p.nombre AS pais,
                    d.nombre AS departamento,
                    m.nombre AS municipio,
                    r.nombre AS rol,               -- Nuevo: Nombre del rol
                    u.activo,                     -- Nuevo: Estado activo/inactivo
                    u.created_at,
                    u.updated_at
                FROM users u
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion
                LEFT JOIN facultades f ON u.facultad_id = f.id_facultad
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN roles r ON u.rol_id = r.id_rol     -- Nuevo join
                WHERE u.activo = 1
                ORDER BY u.primer_nombre ASC;
            END;

            -- OBTENER LOS USUARIOS ACTIVOS POR ID
            CREATE PROCEDURE ObtenerUsuarioPorId(IN usuarioId SMALLINT)
            BEGIN
                SELECT
                    u.id_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.password,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    i.nombre AS institucion_origen,
                    f.nombre AS facultad,
                    u.telefono,
                    u.direccion,
                    p.nombre AS pais,
                    d.nombre AS departamento,
                    m.nombre AS municipio,
                    r.nombre AS rol,               -- Nuevo: Nombre del rol
                    u.activo,                     -- Nuevo: Estado activo/inactivo
                    u.created_at,
                    u.updated_at
                FROM users u
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion
                LEFT JOIN facultades f ON u.facultad_id = f.id_facultad
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN roles r ON u.rol_id = r.id_rol     -- Nuevo join
                WHERE u.id_usuario = usuarioId
                AND u.activo = 1;
            END;


            CREATE PROCEDURE InsertarUsuario(
                IN p_primer_nombre VARCHAR(50),
                IN p_segundo_nombre VARCHAR(50),
                IN p_primer_apellido VARCHAR(50),
                IN p_segundo_apellido VARCHAR(50),
                IN p_email VARCHAR(100),
                IN p_password VARCHAR(255), -- AÑADIDO
                IN p_tipo_identificacion ENUM('Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería'),
                IN p_numero_identificacion VARCHAR(20),
                IN p_institucion_origen_id SMALLINT,
                IN p_facultad_id SMALLINT,
                IN p_telefono VARCHAR(20),
                IN p_direccion VARCHAR(255),
                IN p_pais_id SMALLINT,
                IN p_departamento_id SMALLINT,
                IN p_municipio_id SMALLINT,
                IN p_rol_id SMALLINT,
                IN p_activo BOOLEAN -- AÑADIDO
            )
            BEGIN
                INSERT INTO users
                 (
                    primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                    email, password, tipo_identificacion, numero_identificacion,
                    institucion_origen_id, facultad_id, telefono, direccion,
                    pais_id, departamento_id, municipio_id, rol_id,
                    activo, created_at, updated_at
                )
                VALUES (
                    p_primer_nombre, p_segundo_nombre, p_primer_apellido, p_segundo_apellido,
                    p_email, p_password, p_tipo_identificacion, p_numero_identificacion,
                    p_institucion_origen_id, p_facultad_id, p_telefono, p_direccion,
                    p_pais_id, p_departamento_id, p_municipio_id, p_rol_id,
                    p_activo, NOW(), NOW()
                );
            END;

            -- ACTUALIZAR USUARIO
            CREATE PROCEDURE ActualizarUsuario(
                IN usuarioId SMALLINT,
                IN p_primer_nombre VARCHAR(50),
                IN p_segundo_nombre VARCHAR(50),
                IN p_primer_apellido VARCHAR(50),
                IN p_segundo_apellido VARCHAR(50),
                IN p_email VARCHAR(100),
                IN p_password VARCHAR(255),
                IN p_tipo_identificacion ENUM('Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería'),
                IN p_numero_identificacion VARCHAR(20),
                IN p_institucion_origen_id SMALLINT,
                IN p_facultad_id SMALLINT,
                IN p_telefono VARCHAR(20),
                IN p_direccion VARCHAR(255),
                IN p_pais_id SMALLINT,
                IN p_departamento_id SMALLINT,
                IN p_municipio_id SMALLINT,
                IN p_rol_id SMALLINT,
                IN p_activo BOOLEAN
            )
            BEGIN
                UPDATE users

                SET primer_nombre = p_primer_nombre,
                    segundo_nombre = p_segundo_nombre,
                    primer_apellido = p_primer_apellido,
                    segundo_apellido = p_segundo_apellido,
                    email = p_email,
                    password = IFNULL(p_password, password),
                    tipo_identificacion = p_tipo_identificacion,
                    numero_identificacion = p_numero_identificacion,
                    institucion_origen_id = p_institucion_origen_id,
                    facultad_id = p_facultad_id,
                    telefono = p_telefono,
                    direccion = p_direccion,
                    pais_id = p_pais_id,
                    departamento_id = p_departamento_id,
                    municipio_id = p_municipio_id,
                    rol_id = p_rol_id,
                    activo = p_activo,
                    updated_at = NOW()
                WHERE id_usuario = usuarioId;
            END;

            -- ELIMINAR USUARIO
            CREATE PROCEDURE EliminarUsuario(IN usuarioId SMALLINT)
            BEGIN
                DELETE FROM users
                 WHERE id_usuario = usuarioId;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ActualizarAsignatura;
            DROP PROCEDURE IF EXISTS EliminarAsignatura;
            DROP PROCEDURE IF EXISTS InsertarAsignatura;
            DROP PROCEDURE IF EXISTS ObtenerAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerAsignaturaPorId;

            -- ACTUALIZAR ASIGNATURA
            CREATE PROCEDURE ActualizarAsignatura(
                IN p_id_asignatura SMALLINT,
                IN p_programa_id SMALLINT,
                IN p_nombre VARCHAR(255),
                IN p_tipo ENUM('Materia', 'Competencia'),
                IN p_codigo_asignatura VARCHAR(30),
                IN p_creditos SMALLINT,
                IN p_semestre SMALLINT,
                IN p_horas_sena SMALLINT,
                IN p_tiempo_presencial SMALLINT,
                IN p_tiempo_independiente SMALLINT,
                IN p_horas_totales_semanales SMALLINT,
                IN p_modalidad ENUM('Teórico', 'Práctico', 'Teórico-Práctico'),
                IN p_metodologia ENUM('Presencial', 'Virtual', 'Híbrido')
            )
            BEGIN
                UPDATE asignaturas
                SET programa_id = p_programa_id,
                    nombre = p_nombre,
                    tipo = p_tipo,
                    codigo_asignatura = p_codigo_asignatura,
                    creditos = p_creditos,
                    semestre = p_semestre,
                    horas_sena = p_horas_sena,
                    tiempo_presencial = p_tiempo_presencial,
                    tiempo_independiente = p_tiempo_independiente,
                    horas_totales_semanales = p_horas_totales_semanales,
                    modalidad = p_modalidad,
                    metodologia = p_metodologia,
                    updated_at = NOW()
                WHERE id_asignatura = p_id_asignatura;
            END;

            -- ELIMINAR ASIGNATURA
            CREATE PROCEDURE EliminarAsignatura(IN asignaturaId SMALLINT)
            BEGIN
                DELETE FROM asignaturas WHERE id_asignatura = asignaturaId;
            END;

            -- INSERTAR ASIGNATURA
            CREATE PROCEDURE InsertarAsignatura(
                IN p_programa_id SMALLINT,
                IN p_nombre VARCHAR(255),
                IN p_tipo ENUM('Materia', 'Competencia'),
                IN p_codigo_asignatura VARCHAR(30),
                IN p_creditos SMALLINT,
                IN p_semestre SMALLINT,
                IN p_horas_sena SMALLINT,
                IN p_tiempo_presencial SMALLINT,
                IN p_tiempo_independiente SMALLINT,
                IN p_horas_totales_semanales SMALLINT,
                IN p_modalidad ENUM('Teórico', 'Práctico', 'Teórico-Práctico'),
                IN p_metodologia ENUM('Presencial', 'Virtual', 'Híbrido')
            )
            BEGIN
                INSERT INTO asignaturas (
                    programa_id,
                    nombre,
                    tipo,
                    codigo_asignatura,
                    creditos,
                    semestre,
                    horas_sena,
                    tiempo_presencial,
                    tiempo_independiente,
                    horas_totales_semanales,
                    modalidad,
                    metodologia,
                    created_at,
                    updated_at
                ) VALUES (
                    p_programa_id,
                    p_nombre,
                    p_tipo,
                    p_codigo_asignatura,
                    p_creditos,
                    p_semestre,
                    p_horas_sena,
                    p_tiempo_presencial,
                    p_tiempo_independiente,
                    p_horas_totales_semanales,
                    p_modalidad,
                    p_metodologia,
                    NOW(),
                    NOW()
                );
            END;

            -- OBTENER TODAS LAS ASIGNATURAS
            CREATE PROCEDURE ObtenerAsignaturas()
            BEGIN
                SELECT a.id_asignatura,
                    a.nombre,
                    a.tipo,
                    a.codigo_asignatura,
                    a.creditos,
                    a.semestre,
                    a.horas_sena,
                    a.tiempo_presencial,
                    a.tiempo_independiente,
                    a.horas_totales_semanales,
                    a.modalidad,
                    a.metodologia,
                    a.created_at,
                    a.updated_at,
                    p.nombre AS programa,
                    i.nombre AS institucion
                FROM asignaturas a
                JOIN programas p ON a.programa_id = p.id_programa
                JOIN instituciones i ON p.institucion_id = i.id_institucion
                ORDER BY a.nombre ASC;
            END;

            -- OBTENER ASIGNATURA POR ID
            CREATE PROCEDURE ObtenerAsignaturaPorId(IN asignaturaId SMALLINT)
            BEGIN
                SELECT a.id_asignatura,
                    a.nombre,
                    a.tipo,
                    a.codigo_asignatura,
                    a.creditos,
                    a.semestre,
                    a.horas_sena,
                    a.tiempo_presencial,
                    a.tiempo_independiente,
                    a.horas_totales_semanales,
                    a.modalidad,
                    a.metodologia,
                    a.created_at,
                    a.updated_at,
                    p.nombre AS programa,
                    i.nombre AS institucion
                FROM asignaturas a
                JOIN programas p ON a.programa_id = p.id_programa
                JOIN instituciones i ON p.institucion_id = i.id_institucion
                WHERE a.id_asignatura = asignaturaId;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (SOLICITUDES)
            DROP PROCEDURE IF EXISTS ActualizarSolicitud;
            DROP PROCEDURE IF EXISTS EliminarSolicitud;
            DROP PROCEDURE IF EXISTS InsertarSolicitud;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudPorId;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudes;

            -- ACTUALIZAR SOLICITUD
            CREATE PROCEDURE ActualizarSolicitud(
                IN p_id_solicitud SMALLINT,
                IN p_usuario_id SMALLINT,
                IN p_programa_destino_id SMALLINT,
                IN p_finalizo_estudios ENUM('Si', 'No'),
                IN p_fecha_finalizacion_estudios DATE,
                IN p_fecha_ultimo_semestre_cursado DATE,
                IN p_estado ENUM('Radicado', 'En revisión', 'Aprobado', 'Rechazado', 'Cerrado')
            )
            BEGIN
                UPDATE solicitudes
                SET
                    usuario_id = p_usuario_id,
                    programa_destino_id = p_programa_destino_id,
                    finalizo_estudios = p_finalizo_estudios,
                    fecha_finalizacion_estudios = p_fecha_finalizacion_estudios,
                    fecha_ultimo_semestre_cursado = p_fecha_ultimo_semestre_cursado,
                    estado = p_estado,
                    updated_at = NOW()
                WHERE id_solicitud = p_id_solicitud;
            END;

            -- ELIMINAR SOLICITUD
            CREATE PROCEDURE EliminarSolicitud(
                IN p_id_solicitud SMALLINT
            )
            BEGIN
                DELETE FROM solicitudes WHERE id_solicitud = p_id_solicitud;
            END;

            -- INSERTAR SOLICITUD (Modificado para permitir la generación automática del número de radicado)
            CREATE PROCEDURE InsertarSolicitud(
                IN p_usuario_id SMALLINT,
                IN p_programa_destino_id SMALLINT,
                IN p_finalizo_estudios ENUM('Si', 'No'),
                IN p_fecha_finalizacion_estudios DATE,
                IN p_fecha_ultimo_semestre_cursado DATE,
                IN p_estado ENUM('Radicado', 'En revisión', 'Aprobado', 'Rechazado', 'Cerrado')
            )
            BEGIN
                DECLARE v_year INT;
                DECLARE v_ultimo_consecutivo INT;
                DECLARE v_nuevo_radicado VARCHAR(50);

                -- Obtener el año actual
                SET v_year = YEAR(CURDATE());

                -- Obtener el último consecutivo del año actual
                SELECT IFNULL(MAX(CAST(SUBSTRING_INDEX(numero_radicado, '-', -1) AS UNSIGNED)), 0)
                INTO v_ultimo_consecutivo
                FROM solicitudes
                WHERE numero_radicado LIKE CONCAT('HOM-', v_year, '-%');

                -- Incrementar el consecutivo
                SET v_ultimo_consecutivo = v_ultimo_consecutivo + 1;

                -- Formar el nuevo número de radicado
                SET v_nuevo_radicado = CONCAT('HOM-', v_year, '-', LPAD(v_ultimo_consecutivo, 4, '0'));

                -- Insertar la solicitud con el número de radicado generado
                INSERT INTO solicitudes (
                    usuario_id,
                    programa_destino_id,
                    finalizo_estudios,
                    fecha_finalizacion_estudios,
                    fecha_ultimo_semestre_cursado,
                    estado,
                    numero_radicado,
                    created_at,
                    updated_at
                ) VALUES (
                    p_usuario_id,
                    p_programa_destino_id,
                    p_finalizo_estudios,
                    p_fecha_finalizacion_estudios,
                    p_fecha_ultimo_semestre_cursado,
                    p_estado,
                    v_nuevo_radicado,
                    NOW(),
                    NOW()
                );
            END;

            -- OBTENER SOLICITUD POR ID
            CREATE PROCEDURE ObtenerSolicitudPorId(
                IN p_id_solicitud SMALLINT
            )
            BEGIN
                SELECT
                    s.id_solicitud,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.fecha_solicitud,
                    s.estado,
                    s.numero_radicado,

                    -- Datos del programa destino
                    prog.nombre AS programa_destino_nombre,

                    -- Datos del usuario
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    u.telefono,
                    u.direccion,

                    -- Nombres en vez de IDs
                    p.nombre AS pais_nombre,
                    d.nombre AS departamento_nombre,
                    m.nombre AS municipio_nombre,
                    inst.nombre AS institucion_origen_nombre

                FROM solicitudes s
                INNER JOIN users u ON s.usuario_id = u.id_usuario
                INNER JOIN programas prog ON s.programa_destino_id = prog.id_programa
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones inst ON u.institucion_origen_id = inst.id_institucion
                WHERE s.id_solicitud = p_id_solicitud;
            END;

            -- OBTENER TODAS LAS SOLICITUDES
            CREATE PROCEDURE ObtenerSolicitudes()
            BEGIN
                SELECT
                    s.id_solicitud,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.fecha_solicitud,
                    s.estado,
                    s.numero_radicado,

                    -- Datos del programa destino
                    prog.nombre AS programa_destino_nombre,

                    -- Datos del usuario
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    u.telefono,
                    u.direccion,

                    -- Nombres en vez de IDs
                    p.nombre AS pais_nombre,
                    d.nombre AS departamento_nombre,
                    m.nombre AS municipio_nombre,
                    inst.nombre AS institucion_origen_nombre

                FROM solicitudes s
                INNER JOIN users u ON s.usuario_id = u.id_usuario
                INNER JOIN programas prog ON s.programa_destino_id = prog.id_programa
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones inst ON u.institucion_origen_id = inst.id_institucion;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (documentos)
            DROP PROCEDURE IF EXISTS ObtenerDocumentos;
            DROP PROCEDURE IF EXISTS ObtenerDocumentoPorId;
            DROP PROCEDURE IF EXISTS InsertarDocumento;
            DROP PROCEDURE IF EXISTS ActualizarDocumento;
            DROP PROCEDURE IF EXISTS EliminarDocumento;

            -- OBTENER TODOS LOS DOCUMENTOS (modificado)
            CREATE PROCEDURE ObtenerDocumentos()
            BEGIN
                SELECT
                    d.id_documento,
                    d.solicitud_id,
                    d.usuario_id,
                    d.tipo,
                    d.ruta,
                    d.fecha_subida,
                    d.created_at,
                    d.updated_at,
                    -- Datos de usuario
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    u.telefono,
                    u.direccion,
                    u.institucion_origen_id,
                    u.facultad_id,
                    u.pais_id,
                    u.departamento_id,
                    u.municipio_id,
                    -- Datos de solicitud
                    s.programa_destino_id,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.estado,
                    s.numero_radicado,
                    s.fecha_solicitud
                FROM documentos d
                LEFT JOIN users u ON d.usuario_id = u.id_usuario
                LEFT JOIN solicitudes s ON d.solicitud_id = s.id_solicitud
                ORDER BY d.fecha_subida DESC;
            END;

            -- OBTENER DOCUMENTO POR ID (modificado)
            CREATE PROCEDURE ObtenerDocumentoPorId(IN documentoId SMALLINT)
            BEGIN
                SELECT
                    d.id_documento,
                    d.solicitud_id,
                    d.usuario_id,
                    d.tipo,
                    d.ruta,
                    d.fecha_subida,
                    d.created_at,
                    d.updated_at,
                    -- Datos de usuario
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    u.telefono,
                    u.direccion,
                    u.institucion_origen_id,
                    u.facultad_id,
                    u.pais_id,
                    u.departamento_id,
                    u.municipio_id,
                    -- Datos de solicitud
                    s.programa_destino_id,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.estado,
                    s.numero_radicado,
                    s.fecha_solicitud
                FROM documentos d
                LEFT JOIN users u ON d.usuario_id = u.id_usuario
                LEFT JOIN solicitudes s ON d.solicitud_id = s.id_solicitud
                WHERE d.id_documento = documentoId;
            END;

            
            -- INSERTAR DOCUMENTO
            CREATE PROCEDURE InsertarDocumento(
                IN p_solicitud_id SMALLINT,
                IN p_usuario_id SMALLINT,
                IN p_tipo VARCHAR(255),
                IN p_ruta VARCHAR(255)
            )
            BEGIN
                INSERT INTO documentos (
                    solicitud_id, usuario_id, tipo, ruta, fecha_subida,
                    created_at, updated_at
                )
                VALUES (
                    p_solicitud_id, p_usuario_id, p_tipo, p_ruta, NOW(),
                    NOW(), NOW()
                );
            END;

            -- ACTUALIZAR DOCUMENTO
            CREATE PROCEDURE ActualizarDocumento(
                IN documentoId SMALLINT,
                IN p_solicitud_id SMALLINT,
                IN p_usuario_id SMALLINT,
                IN p_tipo VARCHAR(255),
                IN p_ruta VARCHAR(255)
            )
            BEGIN
                UPDATE documentos
                SET solicitud_id = p_solicitud_id,
                    usuario_id = p_usuario_id,
                    tipo = p_tipo,
                    ruta = p_ruta,
                    updated_at = NOW()
                WHERE id_documento = documentoId;
            END;

            -- ELIMINAR DOCUMENTO
            CREATE PROCEDURE EliminarDocumento(IN documentoId SMALLINT)
            BEGIN
                DELETE FROM documentos WHERE id_documento = documentoId;
            END;




            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (HISTORIAL HOMOLOGACIONES)
            DROP PROCEDURE IF EXISTS ObtenerHistorialHomologaciones;
            DROP PROCEDURE IF EXISTS ObtenerHistorialHomologacionPorId;
            DROP PROCEDURE IF EXISTS InsertarHistorialHomologacion;
            DROP PROCEDURE IF EXISTS ActualizarHistorialHomologacion;
            DROP PROCEDURE IF EXISTS EliminarHistorialHomologacion;

            -- OBTENER TODO EL HISTORIAL
            CREATE PROCEDURE ObtenerHistorialHomologaciones()
            BEGIN
                SELECT
                    hh.id_historial,
                    hh.estado AS estado_historial,
                    hh.fecha,
                    hh.observaciones,
                    hh.ruta_pdf_resolucion,

                    s.id_solicitud,
                    s.numero_radicado,
                    s.estado AS estado_solicitud,
                    s.fecha_solicitud,
                    s.finalizo_estudios,
                    prog.nombre AS nombre_programa_destino,

                    u.id_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,

                    p.nombre AS pais_nombre,
                    d.nombre AS departamento_nombre,
                    m.nombre AS municipio_nombre,
                    i.nombre AS institucion_origen_nombre

                FROM historial_homologaciones hh
                INNER JOIN solicitudes s ON hh.solicitud_id = s.id_solicitud
                INNER JOIN programas prog ON s.programa_destino_id = prog.id_programa
                INNER JOIN users
                 u ON hh.usuario_id = u.id_usuario
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion;
            END;

            -- OBTENER HISTORIAL POR ID
            CREATE PROCEDURE ObtenerHistorialHomologacionPorId(IN p_id SMALLINT)
            BEGIN
                SELECT
                    hh.id_historial,
                    hh.estado AS estado_historial,
                    hh.fecha,
                    hh.observaciones,
                    hh.ruta_pdf_resolucion,

                    s.id_solicitud,
                    s.numero_radicado,
                    s.estado AS estado_solicitud,
                    s.fecha_solicitud,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.ruta_pdf_resolucion AS resolucion_solicitud,
                    prog.nombre AS nombre_programa_destino,

                    u.id_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    u.telefono,
                    u.direccion,

                    p.nombre AS pais_nombre,
                    d.nombre AS departamento_nombre,
                    m.nombre AS municipio_nombre,
                    i.nombre AS institucion_origen_nombre

                FROM historial_homologaciones hh
                INNER JOIN solicitudes s ON hh.solicitud_id = s.id_solicitud
                INNER JOIN programas prog ON s.programa_destino_id = prog.id_programa
                INNER JOIN users
                 u ON hh.usuario_id = u.id_usuario
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion
                WHERE hh.id_historial = p_id;
            END;

            -- INSERTAR HISTORIAL
            CREATE PROCEDURE InsertarHistorialHomologacion(
                IN p_solicitud_id SMALLINT,
                IN p_usuario_id SMALLINT,
                IN p_estado VARCHAR(20),
                IN p_observaciones TEXT,
                IN p_ruta_pdf_resolucion VARCHAR(255)
            )
            BEGIN
                INSERT INTO historial_homologaciones (
                    solicitud_id, usuario_id, estado, fecha, observaciones, ruta_pdf_resolucion, created_at, updated_at
                )
                VALUES (
                    p_solicitud_id, p_usuario_id, p_estado, NOW(), p_observaciones, p_ruta_pdf_resolucion, NOW(), NOW()
                );
            END;

            -- ACTUALIZAR
            CREATE PROCEDURE ActualizarHistorialHomologacion(
                IN historialId SMALLINT,
                IN p_solicitud_id SMALLINT,
                IN p_usuario_id SMALLINT,
                IN p_estado VARCHAR(20),
                IN p_observaciones TEXT,
                IN p_ruta_pdf_resolucion VARCHAR(255)
            )
            BEGIN
                UPDATE historial_homologaciones
                SET solicitud_id = p_solicitud_id,
                    usuario_id = p_usuario_id,
                    estado = p_estado,
                    observaciones = p_observaciones,
                    ruta_pdf_resolucion = p_ruta_pdf_resolucion,
                    updated_at = NOW()
                WHERE id_historial = historialId;
            END;

            -- ELIMINAR
            CREATE PROCEDURE EliminarHistorialHomologacion(IN historialId SMALLINT)
            BEGIN
                DELETE FROM historial_homologaciones WHERE id_historial = historialId;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (CONTENIDOS PROGRAMÁTICOS)
            DROP PROCEDURE IF EXISTS ObtenerContenidosProgramaticos;
            DROP PROCEDURE IF EXISTS ObtenerContenidoProgramaticoPorId;
            DROP PROCEDURE IF EXISTS InsertarContenidoProgramatico;
            DROP PROCEDURE IF EXISTS ActualizarContenidoProgramatico;
            DROP PROCEDURE IF EXISTS EliminarContenidoProgramatico;

            -- OBTENER TODOS CONTENIDOS PROGRAMATICOS
            CREATE PROCEDURE ObtenerContenidosProgramaticos()
            BEGIN
                SELECT
                    cp.id_contenido,
                    cp.tema,
                    cp.resultados_aprendizaje,
                    cp.descripcion,
                    a.nombre AS nombre_asignatura,
                    cp.created_at,
                    cp.updated_at
                FROM contenidos_programaticos cp
                INNER JOIN asignaturas a ON cp.asignatura_id = a.id_asignatura
                ORDER BY cp.tema ASC;
            END;

            -- OBTENER CONTENIDO PROGRAMATICO POR ID
            CREATE PROCEDURE ObtenerContenidoProgramaticoPorId(IN contenidoId SMALLINT)
            BEGIN
                SELECT
                    cp.id_contenido,
                    cp.tema,
                    cp.resultados_aprendizaje,
                    cp.descripcion,
                    a.nombre AS nombre_asignatura,
                    cp.created_at,
                    cp.updated_at
                FROM contenidos_programaticos cp
                INNER JOIN asignaturas a ON cp.asignatura_id = a.id_asignatura
                WHERE cp.id_contenido = contenidoId;
            END;

            -- INSERTAR CONTENIDO PROGRAMATICO
            CREATE PROCEDURE InsertarContenidoProgramatico(
                IN p_asignatura_id SMALLINT,
                IN p_tema VARCHAR(255),
                IN p_resultados_aprendizaje TEXT,
                IN p_descripcion TEXT
            )
            BEGIN
                INSERT INTO contenidos_programaticos (
                    asignatura_id, tema, resultados_aprendizaje, descripcion, created_at, updated_at
                )
                VALUES (
                    p_asignatura_id, p_tema, p_resultados_aprendizaje, p_descripcion, NOW(), NOW()
                );
            END;

            -- ACTUALIZAR CONTENIDO PROGRAMATICO
            CREATE PROCEDURE ActualizarContenidoProgramatico(
                IN contenidoId SMALLINT,
                IN p_asignatura_id SMALLINT,
                IN p_tema VARCHAR(255),
                IN p_resultados_aprendizaje TEXT,
                IN p_descripcion TEXT
            )
            BEGIN
                UPDATE contenidos_programaticos
                SET asignatura_id = p_asignatura_id,
                    tema = p_tema,
                    resultados_aprendizaje = p_resultados_aprendizaje,
                    descripcion = p_descripcion,
                    updated_at = NOW()
                WHERE id_contenido = contenidoId;
            END;

            -- ELIMINAR CONTENIDO PROGRAMATICO
            CREATE PROCEDURE EliminarContenidoProgramatico(IN contenidoId SMALLINT)
            BEGIN
                DELETE FROM contenidos_programaticos WHERE id_contenido = contenidoId;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (SOLICITUD ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ObtenerSolicitudAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudAsignaturaPorId;
            DROP PROCEDURE IF EXISTS InsertarSolicitudAsignatura;
            DROP PROCEDURE IF EXISTS ActualizarSolicitudAsignatura;
            DROP PROCEDURE IF EXISTS EliminarSolicitudAsignatura;

            -- OBTENER TODAS LAS SOLICITUDES DE ASIGNATURAS
            CREATE PROCEDURE ObtenerSolicitudAsignaturas()
            BEGIN
                SELECT
                    sa.id_solicitud_asignatura,
                    sa.solicitud_id,
                    s.numero_radicado,
                    CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                    i.nombre AS institucion,
                    sa.asignaturas, -- Campo JSON con todas las asignaturas
                    sa.created_at,
                    sa.updated_at
                FROM solicitud_asignaturas sa
                LEFT JOIN solicitudes s ON sa.solicitud_id = s.id_solicitud
                LEFT JOIN users u ON s.usuario_id = u.id_usuario
                LEFT JOIN programas p ON s.programa_destino_id = p.id_programa
                LEFT JOIN instituciones i ON p.institucion_id = i.id_institucion
                ORDER BY sa.id_solicitud_asignatura ASC;
            END;

            -- OBTENER POR ID
            CREATE PROCEDURE ObtenerSolicitudAsignaturaPorId(IN solicitudAsignaturaId INT)
            BEGIN
                SELECT
                    sa.id_solicitud_asignatura,
                    sa.solicitud_id,
                    s.numero_radicado,
                    CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                    i.nombre AS institucion,
                    sa.asignaturas, -- Campo JSON con todas las asignaturas
                    sa.created_at,
                    sa.updated_at
                FROM solicitud_asignaturas sa
                LEFT JOIN solicitudes s ON sa.solicitud_id = s.id_solicitud
                LEFT JOIN users u ON s.usuario_id = u.id_usuario
                LEFT JOIN programas p ON s.programa_destino_id = p.id_programa
                LEFT JOIN instituciones i ON p.institucion_id = i.id_institucion
                WHERE sa.id_solicitud_asignatura = solicitudAsignaturaId;
            END;

            -- INSERTAR SOLICITUD ASIGNATURA (ahora recibe un JSON con las asignaturas)
            CREATE PROCEDURE InsertarSolicitudAsignatura(
                IN p_solicitud_id INT,
                IN p_asignaturas JSON
            )
            BEGIN
                -- Primero verificamos si ya existe un registro para esta solicitud
                DECLARE solicitud_exists INT;

                SELECT COUNT(*) INTO solicitud_exists
                FROM solicitud_asignaturas
                WHERE solicitud_id = p_solicitud_id;

                IF solicitud_exists > 0 THEN
                    -- Si existe, actualizamos
                    UPDATE solicitud_asignaturas
                    SET asignaturas = p_asignaturas,
                        updated_at = NOW()
                    WHERE solicitud_id = p_solicitud_id;
                ELSE
                    -- Si no existe, insertamos nuevo
                    INSERT INTO solicitud_asignaturas (
                        solicitud_id, asignaturas, created_at, updated_at
                    )
                    VALUES (
                        p_solicitud_id, p_asignaturas, NOW(), NOW()
                    );
                END IF;
            END;

            -- ACTUALIZAR SOLICITUD ASIGNATURA
            CREATE PROCEDURE ActualizarSolicitudAsignatura(
                IN solicitudAsignaturaId INT,
                IN p_solicitud_id INT,
                IN p_asignaturas JSON
            )
            BEGIN
                UPDATE solicitud_asignaturas
                SET solicitud_id = p_solicitud_id,
                    asignaturas = p_asignaturas,
                    updated_at = NOW()
                WHERE id_solicitud_asignatura = solicitudAsignaturaId;
            END;

            -- ELIMINAR SOLICITUD ASIGNATURA
            CREATE PROCEDURE EliminarSolicitudAsignatura(IN solicitudAsignaturaId INT)
            BEGIN
                DELETE FROM solicitud_asignaturas WHERE id_solicitud_asignatura = solicitudAsignaturaId;
            END;





            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (HOMOLOGACIÓN ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ObtenerHomologacionesAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerHomologacionAsignaturaPorId;
            DROP PROCEDURE IF EXISTS InsertarHomologacionAsignatura;
            DROP PROCEDURE IF EXISTS ActualizarHomologacionAsignatura;
            DROP PROCEDURE IF EXISTS EliminarHomologacionAsignatura;

            -- OBTENER TODAS LAS HOMOLOGACIONES (VERSIÓN SIMPLE)
            CREATE PROCEDURE ObtenerHomologacionesAsignaturas()
            BEGIN
                SELECT
                    ha.id_homologacion,
                    ha.solicitud_id,
                    s.numero_radicado,
                    CONCAT(
                        u.primer_nombre, ' ',
                        IFNULL(u.segundo_nombre, ''), ' ',
                        u.primer_apellido, ' ',
                        IFNULL(u.segundo_apellido, '')
                    ) AS estudiante,
                    ha.homologaciones, -- Devolvemos el JSON tal cual
                    ha.fecha,
                    ha.ruta_pdf_resolucion,
                    ha.created_at,
                    ha.updated_at
                FROM homologacion_asignaturas ha
                JOIN solicitudes s ON ha.solicitud_id = s.id_solicitud
                JOIN users u ON s.usuario_id = u.id_usuario
                ORDER BY ha.id_homologacion ASC;
            END;

            -- OBTENER UNA HOMOLOGACIÓN POR ID (VERSIÓN SIMPLE)
            CREATE PROCEDURE ObtenerHomologacionAsignaturaPorId(IN homologacionId INT)
            BEGIN
                SELECT
                    ha.id_homologacion,
                    ha.solicitud_id,
                    s.numero_radicado,
                    CONCAT(
                        u.primer_nombre, ' ',
                        IFNULL(u.segundo_nombre, ''), ' ',
                        u.primer_apellido, ' ',
                        IFNULL(u.segundo_apellido, '')
                    ) AS estudiante,
                    ha.homologaciones, -- Devolvemos el JSON tal cual
                    ha.fecha,
                    ha.ruta_pdf_resolucion,
                    ha.created_at,
                    ha.updated_at
                FROM homologacion_asignaturas ha
                JOIN solicitudes s ON ha.solicitud_id = s.id_solicitud
                JOIN users u ON s.usuario_id = u.id_usuario
                WHERE ha.id_homologacion = homologacionId;
            END;

            -- INSERTAR HOMOLOGACIÓN ASIGNATURA (solo para creación inicial, no actualiza)
            CREATE PROCEDURE InsertarHomologacionAsignatura(
                IN p_solicitud_id INT,
                IN p_homologaciones JSON,
                IN p_fecha DATE,
                IN p_ruta_pdf_resolucion VARCHAR(255)
            )
            BEGIN
                -- Verificamos si ya existe un registro para esta solicitud
                DECLARE homologacion_exists INT;

                SELECT COUNT(*) INTO homologacion_exists
                FROM homologacion_asignaturas
                WHERE solicitud_id = p_solicitud_id;

                IF homologacion_exists > 0 THEN
                    -- Si existe, lanzar un error
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'Ya existe una homologación para esta solicitud. Use actualizar en su lugar.';
                ELSE
                    -- Si no existe, insertamos nuevo
                    INSERT INTO homologacion_asignaturas (
                        solicitud_id, homologaciones, fecha, ruta_pdf_resolucion, created_at, updated_at
                    )
                    VALUES (
                        p_solicitud_id, p_homologaciones, p_fecha, p_ruta_pdf_resolucion, NOW(), NOW()
                    );
                END IF;
            END;

            -- ACTUALIZAR HOMOLOGACIÓN (mantiene las asignaturas de origen intactas)
            CREATE PROCEDURE ActualizarHomologacionAsignatura(
            IN p_id_homologacion INT,
            IN p_solicitud_id INT,
            IN p_homologaciones JSON,
            IN p_fecha DATE,
            IN p_ruta_pdf_resolucion VARCHAR(255)
            )
            BEGIN
                UPDATE homologacion_asignaturas
                SET
                    homologaciones = p_homologaciones,
                    fecha = p_fecha,
                    ruta_pdf_resolucion = p_ruta_pdf_resolucion,
                    updated_at = NOW()
                WHERE id_homologacion = p_id_homologacion;
            END;

            -- ELIMINAR HOMOLOGACIÓN
            CREATE PROCEDURE EliminarHomologacionAsignatura(IN homologacionId INT)
            BEGIN
                DELETE FROM homologacion_asignaturas WHERE id_homologacion = homologacionId;
            END;




        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("
            -- ELIMINAR PROCEDIMIENTOS PAISES
            DROP PROCEDURE IF EXISTS ActualizarPais;
            DROP PROCEDURE IF EXISTS EliminarPais;
            DROP PROCEDURE IF EXISTS InsertarPais;
            DROP PROCEDURE IF EXISTS ObtenerPaisPorId;
            DROP PROCEDURE IF EXISTS ObtenerPaises;

            -- ELIMINAR PROCEDIMIENTOS DEPARTAMENTOS
            DROP PROCEDURE IF EXISTS ActualizarDepartamento;
            DROP PROCEDURE IF EXISTS EliminarDepartamento;
            DROP PROCEDURE IF EXISTS InsertarDepartamento;
            DROP PROCEDURE IF EXISTS ObtenerDepartamentoPorId;
            DROP PROCEDURE IF EXISTS ObtenerDepartamentos;

            -- ELIMINAR PROCEDIMIENTOS PARA MUNICIPIOS
            DROP PROCEDURE IF EXISTS ActualizarMunicipio;
            DROP PROCEDURE IF EXISTS EliminarMunicipio;
            DROP PROCEDURE IF EXISTS InsertarMunicipio;
            DROP PROCEDURE IF EXISTS ObtenerMunicipioPorId;
            DROP PROCEDURE IF EXISTS ObtenerMunicipios;

            -- ELIMINAR PROCEDIMIENTOS INSTITUCIONES
            DROP PROCEDURE IF EXISTS ActualizarInstitucion;
            DROP PROCEDURE IF EXISTS EliminarInstitucion;
            DROP PROCEDURE IF EXISTS InsertarInstitucion;
            DROP PROCEDURE IF EXISTS ObtenerInstitucionPorId;
            DROP PROCEDURE IF EXISTS ObtenerInstituciones;

            -- ELIMINAR PROCEDIMIENTOS PROGRAMAS
            DROP PROCEDURE IF EXISTS ActualizarPrograma;
            DROP PROCEDURE IF EXISTS EliminarPrograma;
            DROP PROCEDURE IF EXISTS InsertarPrograma;
            DROP PROCEDURE IF EXISTS ObtenerProgramaPorId;
            DROP PROCEDURE IF EXISTS ObtenerProgramas;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ActualizarAsignatura;
            DROP PROCEDURE IF EXISTS EliminarAsignatura;
            DROP PROCEDURE IF EXISTS InsertarAsignatura;
            DROP PROCEDURE IF EXISTS ObtenerAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerAsignaturaPorId;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (SOLICITUDES)
            DROP PROCEDURE IF EXISTS ActualizarSolicitud;
            DROP PROCEDURE IF EXISTS EliminarSolicitud;
            DROP PROCEDURE IF EXISTS InsertarSolicitud;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudPorId;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudes;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (users)
            )
            DROP PROCEDURE IF EXISTS ObtenerUsuarios;
            DROP PROCEDURE IF EXISTS ObtenerUsuarioPorId;
            DROP PROCEDURE IF EXISTS InsertarUsuario;
            DROP PROCEDURE IF EXISTS ActualizarUsuario;
            DROP PROCEDURE IF EXISTS EliminarUsuario;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (documentos)
            DROP PROCEDURE IF EXISTS ObtenerDocumentos;
            DROP PROCEDURE IF EXISTS ObtenerDocumentoPorId;
            DROP PROCEDURE IF EXISTS InsertarDocumento;
            DROP PROCEDURE IF EXISTS ActualizarDocumento;
            DROP PROCEDURE IF EXISTS EliminarDocumento;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (FACULTIES)
            DROP PROCEDURE IF EXISTS ObtenerFacultades;
            DROP PROCEDURE IF EXISTS ObtenerFacultadPorId;
            DROP PROCEDURE IF EXISTS InsertarFacultad;
            DROP PROCEDURE IF EXISTS ActualizarFacultad;
            DROP PROCEDURE IF EXISTS EliminarFacultad;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (ROLES)
            DROP PROCEDURE IF EXISTS ObtenerRoles;
            DROP PROCEDURE IF EXISTS ObtenerRolPorId;
            DROP PROCEDURE IF EXISTS InsertarRol;
            DROP PROCEDURE IF EXISTS ActualizarRol;
            DROP PROCEDURE IF EXISTS EliminarRol;

              -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (CONTENIDOS PROGRAMÁTICOS)
            DROP PROCEDURE IF EXISTS ObtenerContenidosProgramaticos;
            DROP PROCEDURE IF EXISTS ObtenerContenidoProgramaticoPorId;
            DROP PROCEDURE IF EXISTS InsertarContenidoProgramatico;
            DROP PROCEDURE IF EXISTS ActualizarContenidoProgramatico;
            DROP PROCEDURE IF EXISTS EliminarContenidoProgramatico;

              -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (HISTORIAL HOMOLOGACIONES)
            DROP PROCEDURE IF EXISTS ObtenerHistorialHomologaciones;
            DROP PROCEDURE IF EXISTS ObtenerHistorialHomologacionPorId;
            DROP PROCEDURE IF EXISTS InsertarHistorialHomologacion;
            DROP PROCEDURE IF EXISTS ActualizarHistorialHomologacion;
            DROP PROCEDURE IF EXISTS EliminarHistorialHomologacion;

             -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (SOLICITUD ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ObtenerSolicitudAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudAsignaturaPorId;
            DROP PROCEDURE IF EXISTS InsertarSolicitudAsignatura;
            DROP PROCEDURE IF EXISTS ActualizarSolicitudAsignatura;
            DROP PROCEDURE IF EXISTS EliminarSolicitudAsignatura;


            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (HOMOLOGACIÓN ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ObtenerHomologacionesAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerHomologacionAsignaturaPorId;
            DROP PROCEDURE IF EXISTS InsertarHomologacionAsignatura;
            DROP PROCEDURE IF EXISTS ActualizarHomologacionAsignatura;
            DROP PROCEDURE IF EXISTS EliminarHomologacionAsignatura;
        ");
    }
};
