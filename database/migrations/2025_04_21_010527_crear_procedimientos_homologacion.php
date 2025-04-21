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

            -- PROCEDIMIENTOS PARA PAISES
            CREATE PROCEDURE ActualizarPais(IN paisId INT, IN nombrePais VARCHAR(100))
            BEGIN
                UPDATE paises SET nombre = nombrePais WHERE id_pais = paisId;
            END;

            CREATE PROCEDURE EliminarPais(IN paisId INT)
            BEGIN
                DELETE FROM paises WHERE id_pais = paisId;
            END;

            CREATE PROCEDURE InsertarPais(IN nombrePais VARCHAR(100))
            BEGIN
                INSERT INTO paises (nombre) VALUES (nombrePais);
            END;

            CREATE PROCEDURE ObtenerPaisPorId(IN paisId INT)
            BEGIN
                SELECT p.id_pais, p.nombre AS pais
                FROM paises p
                WHERE p.id_pais = paisId;
            END;

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

            -- PROCEDIMIENTOS PARA DEPARTAMENTOS
            CREATE PROCEDURE ActualizarDepartamento(
                IN departamentoId INT,
                IN nombreDepartamento VARCHAR(255),
                IN paisId INT
            )
            BEGIN
                UPDATE departamentos
                SET nombre = nombreDepartamento, pais_id = paisId
                WHERE id_departamento = departamentoId;
            END;

            CREATE PROCEDURE EliminarDepartamento(IN departamentoId INT)
            BEGIN
                DELETE FROM departamentos WHERE id_departamento = departamentoId;
            END;

            CREATE PROCEDURE InsertarDepartamento(
                IN nombreDepartamento VARCHAR(255),
                IN paisId INT
            )
            BEGIN
                INSERT INTO departamentos (nombre, pais_id)
                VALUES (nombreDepartamento, paisId);
            END;

            CREATE PROCEDURE ObtenerDepartamentoPorId(IN departamentoId INT)
            BEGIN
                SELECT d.id_departamento, d.nombre AS departamento, p.nombre AS pais
                FROM departamentos d
                JOIN paises p ON d.pais_id = p.id_pais
                WHERE d.id_departamento = departamentoId;
            END;

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

            -- PROCEDIMIENTOS PARA MUNICIPIOS
            CREATE PROCEDURE ActualizarMunicipio(
                IN municipioId INT,
                IN nombreMunicipio VARCHAR(255),
                IN departamentoId INT
            )
            BEGIN
                UPDATE municipios
                SET nombre = nombreMunicipio, departamento_id = departamentoId
                WHERE id_municipio = municipioId;
            END;

            CREATE PROCEDURE EliminarMunicipio(IN municipioId INT)
            BEGIN
                DELETE FROM municipios WHERE id_municipio = municipioId;
            END;

            CREATE PROCEDURE InsertarMunicipio(
                IN nombreMunicipio VARCHAR(255),
                IN departamentoId INT
            )
            BEGIN
                INSERT INTO municipios (nombre, departamento_id)
                VALUES (nombreMunicipio, departamentoId);
            END;

            CREATE PROCEDURE ObtenerMunicipioPorId(IN municipioId INT)
            BEGIN
                SELECT m.id_municipio, m.nombre AS municipio, d.nombre AS departamento, p.nombre AS pais
                FROM municipios m
                JOIN departamentos d ON m.departamento_id = d.id_departamento
                JOIN paises p ON d.pais_id = p.id_pais
                WHERE m.id_municipio = municipioId;
            END;

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

            -- PROCEDIMIENTOS PARA INSTITUCIONES
            CREATE PROCEDURE ActualizarInstitucion(
                IN institucionId INT,
                IN p_nombre VARCHAR(255),
                IN p_codigo_ies VARCHAR(50),
                IN p_municipio_id INT,
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

            CREATE PROCEDURE EliminarInstitucion(IN institucionId INT)
            BEGIN
                DELETE FROM instituciones WHERE id_institucion = institucionId;
            END;

            CREATE PROCEDURE InsertarInstitucion(
                IN nombreInstitucion VARCHAR(255),
                IN codigoIes VARCHAR(20),
                IN municipioId INT,
                IN tipoInstitucion VARCHAR(100)
            )
            BEGIN
                INSERT INTO instituciones (nombre, codigo_ies, municipio_id, tipo)
                VALUES (nombreInstitucion, codigoIes, municipioId, tipoInstitucion);
            END;

            CREATE PROCEDURE ObtenerInstitucionPorId(IN institucionId INT)
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



                        -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (PROGRAMAS)
            DROP PROCEDURE IF EXISTS ActualizarPrograma;
            DROP PROCEDURE IF EXISTS EliminarPrograma;
            DROP PROCEDURE IF EXISTS InsertarPrograma;
            DROP PROCEDURE IF EXISTS ObtenerProgramaPorId;
            DROP PROCEDURE IF EXISTS ObtenerProgramas;

            -- PROCEDIMIENTOS PARA PROGRAMAS
            CREATE PROCEDURE ActualizarPrograma(
                IN programaId INT,
                IN p_institucion_id INT,
                IN p_facultad_id INT,
                IN p_nombre VARCHAR(255),
                IN p_codigo_snies VARCHAR(20),
                IN p_tipo_formacion ENUM('Técnico', 'Tecnólogo', 'Profesional'),
                IN p_metodologia ENUM('Presencial','Virtual','Híbrido')
            )
            BEGIN
                UPDATE programas
                SET institucion_id = p_institucion_id,
                    facultad_id = p_facultad_id,
                    nombre = p_nombre,
                    codigo_snies = p_codigo_snies,
                    tipo_formacion = p_tipo_formacion,
                    metodologia = p_metodologia
                WHERE id_programa = programaId;
            END;

            CREATE PROCEDURE EliminarPrograma(IN programaId INT)
            BEGIN
                DELETE FROM programas WHERE id_programa = programaId;
            END;

            CREATE PROCEDURE InsertarPrograma(
                IN p_institucion_id INT,
                IN p_facultad_id INT,
                IN p_nombre VARCHAR(255),
                IN p_codigo_snies VARCHAR(20),
                IN p_tipo_formacion ENUM('Técnico', 'Tecnólogo', 'Profesional'),
                IN p_metodologia ENUM('Presencial','Virtual','Híbrido')
            )
            BEGIN
                INSERT INTO programas (institucion_id, facultad_id, nombre, codigo_snies, tipo_formacion, metodologia)
                VALUES (p_institucion_id, p_facultad_id, p_nombre, p_codigo_snies, p_tipo_formacion, p_metodologia);
            END;

            CREATE PROCEDURE ObtenerProgramaPorId(IN programaId INT)
            BEGIN
                SELECT pr.id_programa,
                       pr.nombre AS programa,
                       i.nombre AS institucion,
                       pr.codigo_snies,
                       pr.tipo_formacion,
                       pr.metodologia
                FROM programas pr
                JOIN instituciones i ON pr.institucion_id = i.id_institucion
                WHERE pr.id_programa = programaId;
            END;

            CREATE PROCEDURE ObtenerProgramas()
            BEGIN
                SELECT pr.id_programa,
                       pr.nombre AS programa,
                       i.nombre AS institucion,
                       pr.codigo_snies,
                       pr.tipo_formacion,
                       pr.metodologia
                FROM programas pr
                JOIN instituciones i ON pr.institucion_id = i.id_institucion
                ORDER BY pr.nombre ASC;
            END;



                        -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (ASIGNATURAS)
            DROP PROCEDURE IF EXISTS ActualizarAsignatura;
            DROP PROCEDURE IF EXISTS EliminarAsignatura;
            DROP PROCEDURE IF EXISTS InsertarAsignatura;
            DROP PROCEDURE IF EXISTS ObtenerAsignaturas;
            DROP PROCEDURE IF EXISTS ObtenerAsignaturaPorId;

            -- PROCEDIMIENTOS PARA ASIGNATURAS
            CREATE PROCEDURE ActualizarAsignatura(
                IN p_id_asignatura INT,
                IN p_programa_id INT,
                IN p_nombre VARCHAR(255),
                IN p_tipo ENUM('Materia', 'Competencia'),
                IN p_codigo_asignatura VARCHAR(30),
                IN p_creditos INT,
                IN p_semestre INT,
                IN p_horas_sena INT,
                IN p_tiempo_presencial INT,
                IN p_tiempo_independiente INT,
                IN p_horas_totales_semanales INT,
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

            CREATE PROCEDURE EliminarAsignatura(IN asignaturaId INT)
            BEGIN
                DELETE FROM asignaturas WHERE id_asignatura = asignaturaId;
            END;

            CREATE PROCEDURE InsertarAsignatura(
                IN p_programa_id INT,
                IN p_nombre VARCHAR(255),
                IN p_tipo ENUM('Materia', 'Competencia'),
                IN p_codigo_asignatura VARCHAR(30),
                IN p_creditos INT,
                IN p_semestre INT,
                IN p_horas_sena INT,
                IN p_tiempo_presencial INT,
                IN p_tiempo_independiente INT,
                IN p_horas_totales_semanales INT,
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
                       p.nombre AS programa
                FROM asignaturas a
                JOIN programas p ON a.programa_id = p.id_programa
                ORDER BY a.nombre ASC;
            END;

            CREATE PROCEDURE ObtenerAsignaturaPorId(IN asignaturaId INT)
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
                       p.nombre AS programa
                FROM asignaturas a
                JOIN programas p ON a.programa_id = p.id_programa
                WHERE a.id_asignatura = asignaturaId;
            END;



                        -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (SOLICITUDES)
            DROP PROCEDURE IF EXISTS ActualizarSolicitud;
            DROP PROCEDURE IF EXISTS EliminarSolicitud;
            DROP PROCEDURE IF EXISTS InsertarSolicitud;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudPorId;
            DROP PROCEDURE IF EXISTS ObtenerSolicitudes;

            -- PROCEDIMIENTOS PARA SOLICITUDES
            CREATE PROCEDURE ActualizarSolicitud(
                IN p_id_solicitud SMALLINT,
                IN p_usuario_id SMALLINT,
                IN p_programa_destino_id SMALLINT,
                IN p_finalizo_estudios ENUM('Si', 'No'),
                IN p_fecha_finalizacion_estudios DATE,
                IN p_fecha_ultimo_semestre_cursado DATE,
                IN p_estado ENUM('Radicado', 'En revisión', 'Aprobado', 'Rechazado', 'Cerrado'),
                IN p_numero_radicado VARCHAR(50),
                IN p_ruta_pdf_resolucion VARCHAR(255)
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
                    numero_radicado = p_numero_radicado,
                    ruta_pdf_resolucion = p_ruta_pdf_resolucion,
                    updated_at = NOW()
                WHERE id_solicitud = p_id_solicitud;
            END;

            CREATE PROCEDURE EliminarSolicitud(
                IN p_id_solicitud SMALLINT
            )
            BEGIN
                DELETE FROM solicitudes WHERE id_solicitud = p_id_solicitud;
            END;

            CREATE PROCEDURE InsertarSolicitud(
                IN p_usuario_id SMALLINT,
                IN p_programa_destino_id SMALLINT,
                IN p_finalizo_estudios ENUM('Si', 'No'),
                IN p_fecha_finalizacion_estudios DATE,
                IN p_fecha_ultimo_semestre_cursado DATE,
                IN p_estado ENUM('Radicado', 'En revisión', 'Aprobado', 'Rechazado', 'Cerrado'),
                IN p_numero_radicado VARCHAR(50),
                IN p_ruta_pdf_resolucion VARCHAR(255)
            )
            BEGIN
                INSERT INTO solicitudes (
                    usuario_id,
                    programa_destino_id,
                    finalizo_estudios,
                    fecha_finalizacion_estudios,
                    fecha_ultimo_semestre_cursado,
                    estado,
                    numero_radicado,
                    ruta_pdf_resolucion,
                    created_at,
                    updated_at
                ) VALUES (
                    p_usuario_id,
                    p_programa_destino_id,
                    p_finalizo_estudios,
                    p_fecha_finalizacion_estudios,
                    p_fecha_ultimo_semestre_cursado,
                    p_estado,
                    p_numero_radicado,
                    p_ruta_pdf_resolucion,
                    NOW(),
                    NOW()
                );
            END;

            CREATE PROCEDURE ObtenerSolicitudPorId(
                IN p_id_solicitud SMALLINT
            )
            BEGIN
                SELECT
                    s.id_solicitud,
                    s.usuario_id,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.fecha_solicitud,
                    s.estado,
                    s.numero_radicado,
                    s.ruta_pdf_resolucion AS pdf_resolucion,

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
                INNER JOIN user u ON s.usuario_id = u.id_usuario
                INNER JOIN programas prog ON s.programa_destino_id = prog.id_programa
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones inst ON u.institucion_origen_id = inst.id_institucion
                WHERE s.id_solicitud = p_id_solicitud;
            END;

            CREATE PROCEDURE ObtenerSolicitudes()
            BEGIN
                SELECT
                    s.id_solicitud,
                    s.usuario_id,
                    s.finalizo_estudios,
                    s.fecha_finalizacion_estudios,
                    s.fecha_ultimo_semestre_cursado,
                    s.fecha_solicitud,
                    s.estado,
                    s.numero_radicado,
                    s.ruta_pdf_resolucion AS pdf_resolucion,

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



                        -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (USERS)
            DROP PROCEDURE IF EXISTS ObtenerUsuarios;
            DROP PROCEDURE IF EXISTS ObtenerUsuarioPorId;
            DROP PROCEDURE IF EXISTS InsertarUsuario;
            DROP PROCEDURE IF EXISTS ActualizarUsuario;
            DROP PROCEDURE IF EXISTS EliminarUsuario;

            -- PROCEDIMIENTOS PARA USERS
            CREATE PROCEDURE ObtenerUsuarios()
            BEGIN
                SELECT
                    u.id_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    i.nombre AS institucion_origen,
                    f.nombre AS facultad,
                    u.telefono,
                    u.direccion,
                    p.nombre AS pais,
                    d.nombre AS departamento,
                    m.nombre AS municipio,
                    u.created_at,
                    u.updated_at
                FROM users u
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion
                LEFT JOIN facultades f ON u.facultad_id = f.id_facultad
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                ORDER BY u.primer_nombre ASC;
            END;

            CREATE PROCEDURE ObtenerUsuarioPorId(IN usuarioId INT)
            BEGIN
                SELECT
                    u.id_usuario,
                    u.primer_nombre,
                    u.segundo_nombre,
                    u.primer_apellido,
                    u.segundo_apellido,
                    u.email,
                    u.tipo_identificacion,
                    u.numero_identificacion,
                    i.nombre AS institucion_origen,
                    f.nombre AS facultad,
                    u.telefono,
                    u.direccion,
                    p.nombre AS pais,
                    d.nombre AS departamento,
                    m.nombre AS municipio,
                    u.created_at,
                    u.updated_at
                FROM users u
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion
                LEFT JOIN facultades f ON u.facultad_id = f.id_facultad
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                WHERE u.id_usuario = usuarioId;
            END;

            CREATE PROCEDURE InsertarUsuario(
                IN p_primer_nombre VARCHAR(50),
                IN p_segundo_nombre VARCHAR(50),
                IN p_primer_apellido VARCHAR(50),
                IN p_segundo_apellido VARCHAR(50),
                IN p_email VARCHAR(100),
                IN p_tipo_identificacion ENUM('Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería'),
                IN p_numero_identificacion VARCHAR(20),
                IN p_institucion_origen_id INT,
                IN p_facultad_id INT,
                IN p_telefono VARCHAR(20),
                IN p_direccion VARCHAR(255),
                IN p_pais_id INT,
                IN p_departamento_id INT,
                IN p_municipio_id INT,
                IN p_rol_id INT
            )
            BEGIN
                INSERT INTO users (
                    primer_nombre, segundo_nombre, primer_apellido, segundo_apellido,
                    email, tipo_identificacion, numero_identificacion,
                    institucion_origen_id, facultad_id, telefono, direccion,
                    pais_id, departamento_id, municipio_id, rol_id,
                    created_at, updated_at
                )
                VALUES (
                    p_primer_nombre, p_segundo_nombre, p_primer_apellido, p_segundo_apellido,
                    p_email, p_tipo_identificacion, p_numero_identificacion,
                    p_institucion_origen_id, p_facultad_id, p_telefono, p_direccion,
                    p_pais_id, p_departamento_id, p_municipio_id, p_rol_id,
                    NOW(), NOW()
                );
            END;

            CREATE PROCEDURE ActualizarUsuario(
                IN usuarioId INT,
                IN p_primer_nombre VARCHAR(50),
                IN p_segundo_nombre VARCHAR(50),
                IN p_primer_apellido VARCHAR(50),
                IN p_segundo_apellido VARCHAR(50),
                IN p_email VARCHAR(100),
                IN p_tipo_identificacion ENUM('Tarjeta de Identidad', 'Cédula de Ciudadanía', 'Cédula de Extranjería'),
                IN p_numero_identificacion VARCHAR(20),
                IN p_institucion_origen_id INT,
                IN p_facultad_id INT,
                IN p_telefono VARCHAR(20),
                IN p_direccion VARCHAR(255),
                IN p_pais_id INT,
                IN p_departamento_id INT,
                IN p_municipio_id INT,
                IN p_rol_id INT
            )
            BEGIN
                UPDATE users
                SET primer_nombre = p_primer_nombre,
                    segundo_nombre = p_segundo_nombre,
                    primer_apellido = p_primer_apellido,
                    segundo_apellido = p_segundo_apellido,
                    email = p_email,
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
                    updated_at = NOW()
                WHERE id_usuario = usuarioId;
            END;

            CREATE PROCEDURE EliminarUsuario(IN usuarioId INT)
            BEGIN
                DELETE FROM users WHERE id_usuario = usuarioId;
            END;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (DOCUMENTS)
            DROP PROCEDURE IF EXISTS ObtenerDocumentos;
            DROP PROCEDURE IF EXISTS ObtenerDocumentoPorId;
            DROP PROCEDURE IF EXISTS InsertarDocumento;
            DROP PROCEDURE IF EXISTS ActualizarDocumento;
            DROP PROCEDURE IF EXISTS EliminarDocumento;

            -- PROCEDIMIENTOS PARA DOCUMENTS
            CREATE PROCEDURE ObtenerDocumentos()
            BEGIN
                SELECT
                    d.id_documento,
                    CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                    s.id_solicitud,
                    d.tipo,
                    d.ruta,
                    d.fecha_subida,
                    d.created_at,
                    d.updated_at
                FROM documents d
                LEFT JOIN users u ON d.usuario_id = u.id_usuario
                LEFT JOIN solicitudes s ON d.solicitud_id = s.id_solicitud
                ORDER BY d.fecha_subida DESC;
            END;

            CREATE PROCEDURE ObtenerDocumentoPorId(IN documentoId INT)
            BEGIN
                SELECT
                    d.id_documento,
                    CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                    s.id_solicitud,
                    d.tipo,
                    d.ruta,
                    d.fecha_subida,
                    d.created_at,
                    d.updated_at
                FROM documents d
                LEFT JOIN users u ON d.usuario_id = u.id_usuario
                LEFT JOIN solicitudes s ON d.solicitud_id = s.id_solicitud
                WHERE d.id_documento = documentoId;
            END;

            CREATE PROCEDURE InsertarDocumento(
                IN p_solicitud_id INT,
                IN p_usuario_id INT,
                IN p_tipo VARCHAR(255),
                IN p_ruta VARCHAR(255)
            )
            BEGIN
                INSERT INTO documents (
                    solicitud_id, usuario_id, tipo, ruta, fecha_subida,
                    created_at, updated_at
                )
                VALUES (
                    p_solicitud_id, p_usuario_id, p_tipo, p_ruta, NOW(),
                    NOW(), NOW()
                );
            END;

            CREATE PROCEDURE ActualizarDocumento(
                IN documentoId INT,
                IN p_solicitud_id INT,
                IN p_usuario_id INT,
                IN p_tipo VARCHAR(255),
                IN p_ruta VARCHAR(255)
            )
            BEGIN
                UPDATE documents
                SET solicitud_id = p_solicitud_id,
                    usuario_id = p_usuario_id,
                    tipo = p_tipo,
                    ruta = p_ruta,
                    updated_at = NOW()
                WHERE id_documento = documentoId;
            END;

            CREATE PROCEDURE EliminarDocumento(IN documentoId INT)
            BEGIN
                DELETE FROM documents WHERE id_documento = documentoId;
            END;



            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (FACULTIES)
            DROP PROCEDURE IF EXISTS ObtenerFacultades;
            DROP PROCEDURE IF EXISTS ObtenerFacultadPorId;
            DROP PROCEDURE IF EXISTS InsertarFacultad;
            DROP PROCEDURE IF EXISTS ActualizarFacultad;
            DROP PROCEDURE IF EXISTS EliminarFacultad;

            -- PROCEDIMIENTOS PARA FACULTIES
            CREATE PROCEDURE ObtenerFacultades()
            BEGIN
                SELECT
                    f.id_facultad,
                    i.nombre AS institucion,
                    f.nombre,
                    f.created_at,
                    f.updated_at
                FROM faculties f
                LEFT JOIN institutions i ON f.institucion_id = i.id_institucion
                ORDER BY f.nombre ASC;
            END;

            CREATE PROCEDURE ObtenerFacultadPorId(IN facultadId INT)
            BEGIN
                SELECT
                    f.id_facultad,
                    i.nombre AS institucion,
                    f.nombre,
                    f.created_at,
                    f.updated_at
                FROM faculties f
                LEFT JOIN institutions i ON f.institucion_id = i.id_institucion
                WHERE f.id_facultad = facultadId;
            END;

            CREATE PROCEDURE InsertarFacultad(
                IN p_institucion_id INT,
                IN p_nombre VARCHAR(255)
            )
            BEGIN
                INSERT INTO faculties (institucion_id, nombre, created_at, updated_at)
                VALUES (p_institucion_id, p_nombre, NOW(), NOW());
            END;

            CREATE PROCEDURE ActualizarFacultad(
                IN facultadId INT,
                IN p_institucion_id INT,
                IN p_nombre VARCHAR(255)
            )
            BEGIN
                UPDATE faculties
                SET institucion_id = p_institucion_id,
                    nombre = p_nombre,
                    updated_at = NOW()
                WHERE id_facultad = facultadId;
            END;

            CREATE PROCEDURE EliminarFacultad(IN facultadId INT)
            BEGIN
                DELETE FROM faculties WHERE id_facultad = facultadId;
            END;



                        -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (ROLES)
            DROP PROCEDURE IF EXISTS ObtenerRoles;
            DROP PROCEDURE IF EXISTS ObtenerRolPorId;
            DROP PROCEDURE IF EXISTS InsertarRol;
            DROP PROCEDURE IF EXISTS ActualizarRol;
            DROP PROCEDURE IF EXISTS EliminarRol;

            -- PROCEDIMIENTOS PARA ROLES
            CREATE PROCEDURE ObtenerRoles()
            BEGIN
                SELECT * FROM roles ORDER BY id_rol ASC;
            END;

            CREATE PROCEDURE ObtenerRolPorId(IN rolId INT)
            BEGIN
                SELECT * FROM roles WHERE id_rol = rolId;
            END;

            CREATE PROCEDURE InsertarRol(
                IN p_nombre VARCHAR(50)
            )
            BEGIN
                INSERT INTO roles (nombre, created_at, updated_at)
                VALUES (p_nombre, NOW(), NOW());
            END;

            CREATE PROCEDURE ActualizarRol(
                IN rolId INT,
                IN p_nombre VARCHAR(50)
            )
            BEGIN
                UPDATE roles
                SET nombre = p_nombre,
                    updated_at = NOW()
                WHERE id_rol = rolId;
            END;

            CREATE PROCEDURE EliminarRol(IN rolId INT)
            BEGIN
                DELETE FROM roles WHERE id_rol = rolId;
            END;


            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (CONTENIDOS PROGRAMÁTICOS)
            DROP PROCEDURE IF EXISTS ObtenerContenidosProgramaticos;
            DROP PROCEDURE IF EXISTS ObtenerContenidoProgramaticoPorId;
            DROP PROCEDURE IF EXISTS InsertarContenidoProgramatico;
            DROP PROCEDURE IF EXISTS ActualizarContenidoProgramatico;
            DROP PROCEDURE IF EXISTS EliminarContenidoProgramatico;

            -- PROCEDIMIENTOS PARA CONTENIDOS PROGRAMÁTICOS
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

            CREATE PROCEDURE ObtenerContenidoProgramaticoPorId(IN contenidoId INT)
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

            CREATE PROCEDURE InsertarContenidoProgramatico(
                IN p_asignatura_id INT,
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

            CREATE PROCEDURE ActualizarContenidoProgramatico(
                IN contenidoId INT,
                IN p_asignatura_id INT,
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

            CREATE PROCEDURE EliminarContenidoProgramatico(IN contenidoId INT)
            BEGIN
                DELETE FROM contenidos_programaticos WHERE id_contenido = contenidoId;
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
                INNER JOIN users u ON hh.usuario_id = u.id_usuario
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion;
            END;

            -- OBTENER HISTORIAL POR ID
            CREATE PROCEDURE ObtenerHistorialHomologacionPorId(IN p_id INT)
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
                INNER JOIN users u ON hh.usuario_id = u.id_usuario
                LEFT JOIN paises p ON u.pais_id = p.id_pais
                LEFT JOIN departamentos d ON u.departamento_id = d.id_departamento
                LEFT JOIN municipios m ON u.municipio_id = m.id_municipio
                LEFT JOIN instituciones i ON u.institucion_origen_id = i.id_institucion
                WHERE hh.id_historial = p_id;
            END;

            -- INSERTAR
            CREATE PROCEDURE InsertarHistorialHomologacion(
                IN p_solicitud_id INT,
                IN p_usuario_id INT,
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
                IN historialId INT,
                IN p_solicitud_id INT,
                IN p_usuario_id INT,
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
            CREATE PROCEDURE EliminarHistorialHomologacion(IN historialId INT)
            BEGIN
                DELETE FROM historial_homologaciones WHERE id_historial = historialId;
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
                    s.id_solicitud,
                    CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                    a.nombre AS asignatura,
                    a.codigo_asignatura,
                    sa.nota_origen,
                    sa.horas_sena,
                    sa.created_at,
                    sa.updated_at
                FROM solicitud_asignaturas sa
                LEFT JOIN solicitudes s ON sa.solicitud_id = s.id_solicitud
                LEFT JOIN users u ON s.usuario_id = u.id_usuario
                LEFT JOIN asignaturas a ON sa.asignatura_id = a.id_asignatura
                ORDER BY sa.id_solicitud_asignatura ASC;
            END;

            -- OBTENER POR ID
            CREATE PROCEDURE ObtenerSolicitudAsignaturaPorId(IN solicitudAsignaturaId INT)
            BEGIN
                SELECT
                    sa.id_solicitud_asignatura,
                    s.id_solicitud,
                    CONCAT(u.primer_nombre, ' ', u.primer_apellido) AS estudiante,
                    a.nombre AS asignatura,
                    a.codigo_asignatura,
                    sa.nota_origen,
                    sa.horas_sena,
                    sa.created_at,
                    sa.updated_at
                FROM solicitud_asignaturas sa
                LEFT JOIN solicitudes s ON sa.solicitud_id = s.id_solicitud
                LEFT JOIN users u ON s.usuario_id = u.id_usuario
                LEFT JOIN asignaturas a ON sa.asignatura_id = a.id_asignatura
                WHERE sa.id_solicitud_asignatura = solicitudAsignaturaId;
            END;

            -- INSERTAR SOLICITUD ASIGNATURA
            CREATE PROCEDURE InsertarSolicitudAsignatura(
                IN p_solicitud_id INT,
                IN p_asignatura_id INT,
                IN p_nota_origen DECIMAL(3,1),
                IN p_horas_sena INT
            )
            BEGIN
                INSERT INTO solicitud_asignaturas (
                    solicitud_id, asignatura_id, nota_origen, horas_sena, created_at, updated_at
                )
                VALUES (
                    p_solicitud_id, p_asignatura_id, p_nota_origen, p_horas_sena, NOW(), NOW()
                );
            END;

            -- ACTUALIZAR SOLICITUD ASIGNATURA
            CREATE PROCEDURE ActualizarSolicitudAsignatura(
                IN solicitudAsignaturaId INT,
                IN p_solicitud_id INT,
                IN p_asignatura_id INT,
                IN p_nota_origen DECIMAL(3,1),
                IN p_horas_sena INT
            )
            BEGIN
                UPDATE solicitud_asignaturas
                SET solicitud_id = p_solicitud_id,
                    asignatura_id = p_asignatura_id,
                    nota_origen = p_nota_origen,
                    horas_sena = p_horas_sena,
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

            -- OBTENER TODAS LAS HOMOLOGACIONES
            CREATE PROCEDURE ObtenerHomologacionesAsignaturas()
            BEGIN
                SELECT
                    ha.id_homologacion,
                    ha.solicitud_id,

                    -- Asignatura origen
                    ao.id_asignatura   AS asignatura_origen_id,
                    ao.nombre          AS asignatura_origen,
                    i_origen.nombre    AS institucion_origen,   -- Universidad de la asignatura origen

                    -- Asignatura destino
                    ad.id_asignatura   AS asignatura_destino_id,
                    ad.nombre          AS asignatura_destino,
                    i_destino.nombre   AS institucion_destino,  -- Universidad de la asignatura destino

                    -- Nota y horas desde solicitud_asignaturas
                    sa.nota_origen,
                    sa.horas_sena,

                    -- Nota destino
                    ha.nota_destino,

                    -- Datos adicionales
                    ha.fecha,
                    ha.comentarios,
                    ha.created_at,
                    ha.updated_at,

                    -- Estudiante
                    CONCAT(
                    u.primer_nombre, ' ',
                    IFNULL(u.segundo_nombre, ''), ' ',
                    u.primer_apellido, ' ',
                    IFNULL(u.segundo_apellido, '')
                    ) AS estudiante

                FROM homologacion_asignaturas ha
                JOIN asignaturas ao ON ha.asignatura_origen_id  = ao.id_asignatura
                JOIN asignaturas ad ON ha.asignatura_destino_id = ad.id_asignatura
                JOIN solicitudes      s ON ha.solicitud_id       = s.id_solicitud
                JOIN users            u ON s.usuario_id         = u.id_usuario
                LEFT JOIN solicitud_asignaturas sa
                    ON sa.solicitud_id = ha.solicitud_id
                    AND sa.asignatura_id = ha.asignatura_origen_id

                -- Para origen: asignatura → programa → institución
                LEFT JOIN programas   po ON ao.programa_id       = po.id_programa
                LEFT JOIN instituciones i_origen
                    ON po.institucion_id = i_origen.id_institucion

                -- Para destino: asignatura → programa → institución
                LEFT JOIN programas   pd ON ad.programa_id       = pd.id_programa
                LEFT JOIN instituciones i_destino
                    ON pd.institucion_id = i_destino.id_institucion

                ORDER BY ha.id_homologacion ASC;
            END;

            -- OBTENER UNA HOMOLOGACIÓN POR ID
            CREATE PROCEDURE ObtenerHomologacionAsignaturaPorId(IN homologacionId INT)
            BEGIN
                SELECT
                    ha.id_homologacion,
                    ha.solicitud_id,

                    -- Origen
                    ao.id_asignatura   AS asignatura_origen_id,
                    ao.nombre          AS asignatura_origen,
                    i_origen.nombre    AS institucion_origen,

                    -- Destino
                    ad.id_asignatura   AS asignatura_destino_id,
                    ad.nombre          AS asignatura_destino,
                    i_destino.nombre   AS institucion_destino,

                    sa.nota_origen,
                    sa.horas_sena,
                    ha.nota_destino,
                    ha.fecha,
                    ha.comentarios,
                    ha.created_at,
                    ha.updated_at,

                    CONCAT(
                    u.primer_nombre, ' ',
                    IFNULL(u.segundo_nombre, ''), ' ',
                    u.primer_apellido, ' ',
                    IFNULL(u.segundo_apellido, '')
                    ) AS estudiante

                FROM homologacion_asignaturas ha
                JOIN asignaturas ao ON ha.asignatura_origen_id  = ao.id_asignatura
                JOIN asignaturas ad ON ha.asignatura_destino_id = ad.id_asignatura
                JOIN solicitudes      s ON ha.solicitud_id       = s.id_solicitud
                JOIN users            u ON s.usuario_id         = u.id_usuario
                LEFT JOIN solicitud_asignaturas sa
                    ON sa.solicitud_id = ha.solicitud_id
                    AND sa.asignatura_id = ha.asignatura_origen_id

                LEFT JOIN programas   po ON ao.programa_id       = po.id_programa
                LEFT JOIN instituciones i_origen
                    ON po.institucion_id = i_origen.id_institucion

                LEFT JOIN programas   pd ON ad.programa_id       = pd.id_programa
                LEFT JOIN instituciones i_destino
                    ON pd.institucion_id = i_destino.id_institucion

                WHERE ha.id_homologacion = homologacionId;
            END;


            -- INSERTAR HOMOLOGACIÓN
            CREATE PROCEDURE InsertarHomologacionAsignatura(
                IN p_solicitud_id INT,
                IN p_asignatura_origen_id INT,
                IN p_asignatura_destino_id INT,
                IN p_nota_origen DECIMAL(3,1),
                IN p_horas_sena INT,
                IN p_nota_destino DECIMAL(3,1),
                IN p_comentarios TEXT
            )
            BEGIN
                INSERT INTO homologacion_asignaturas (
                    solicitud_id,
                    asignatura_origen_id,
                    asignatura_destino_id,
                    nota_origen,
                    horas_sena,
                    nota_destino,
                    comentarios,
                    created_at,
                    updated_at
                ) VALUES (
                    p_solicitud_id,
                    p_asignatura_origen_id,
                    p_asignatura_destino_id,
                    p_nota_origen,
                    p_horas_sena,
                    p_nota_destino,
                    p_comentarios,
                    NOW(),
                    NOW()
                );
            END;

            -- ACTUALIZAR HOMOLOGACIÓN
            CREATE PROCEDURE ActualizarHomologacionAsignatura(
                IN p_id_homologacion INT,
                IN p_solicitud_id INT,
                IN p_asignatura_origen_id INT,
                IN p_asignatura_destino_id INT,
                IN p_nota_origen DECIMAL(3,1),
                IN p_horas_sena DECIMAL(3,1),
                IN p_nota_destino DECIMAL(3,1),
                IN p_comentarios TEXT
            )
            BEGIN
                UPDATE homologacion_asignaturas
                SET
                    solicitud_id = p_solicitud_id,
                    asignatura_origen_id = p_asignatura_origen_id,
                    asignatura_destino_id = p_asignatura_destino_id,
                    nota_origen = p_nota_origen,
                    horas_sena = p_horas_sena,
                    nota_destino = p_nota_destino,
                    comentarios = p_comentarios,
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

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (USERS)
            DROP PROCEDURE IF EXISTS ObtenerUsuarios;
            DROP PROCEDURE IF EXISTS ObtenerUsuarioPorId;
            DROP PROCEDURE IF EXISTS InsertarUsuario;
            DROP PROCEDURE IF EXISTS ActualizarUsuario;
            DROP PROCEDURE IF EXISTS EliminarUsuario;

            -- ELIMINAR PROCEDIMIENTOS SI EXISTEN (DOCUMENTS)
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
        ");
    }
};
