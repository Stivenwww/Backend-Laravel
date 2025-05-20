<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_pais;
    CREATE TRIGGER tr_insert_pais
    AFTER INSERT ON paises
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
             tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
             'paises', 'INSERT', NEW.id_pais,
            NULL,
            JSON_OBJECT('nombre', NEW.nombre),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_pais;
    CREATE TRIGGER tr_update_pais
    AFTER UPDATE ON paises
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
             tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
             'paises', 'UPDATE', NEW.id_pais,
            JSON_OBJECT('nombre', OLD.nombre),
            JSON_OBJECT('nombre', NEW.nombre),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_pais;
    CREATE TRIGGER tr_delete_pais
    AFTER DELETE ON paises
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
             tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
             'paises', 'DELETE', OLD.id_pais,
            JSON_OBJECT('nombre', OLD.nombre),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");

        // No use los trigger de auditoria para los departamentos y municipios porque conusme demasiados recursos.

        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_institucion;
    CREATE TRIGGER tr_insert_institucion
    AFTER INSERT ON instituciones
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
            'instituciones', 'INSERT', NEW.id_institucion,
            NULL,
            JSON_OBJECT('nombre', NEW.nombre, 'codigo_ies', NEW.codigo_ies, 'municipio_id', NEW.municipio_id, 'tipo', NEW.tipo),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_institucion;
    CREATE TRIGGER tr_update_institucion
    AFTER UPDATE ON instituciones
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
            'instituciones', 'UPDATE', NEW.id_institucion,
            JSON_OBJECT('nombre', OLD.nombre, 'codigo_ies', OLD.codigo_ies, 'municipio_id', OLD.municipio_id, 'tipo', OLD.tipo),
            JSON_OBJECT('nombre', NEW.nombre, 'codigo_ies', NEW.codigo_ies, 'municipio_id', NEW.municipio_id, 'tipo', NEW.tipo),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_institucion;
    CREATE TRIGGER tr_delete_institucion
    AFTER DELETE ON instituciones
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
            'instituciones', 'DELETE', OLD.id_institucion,
            JSON_OBJECT('nombre', OLD.nombre, 'codigo_ies', OLD.codigo_ies, 'municipio_id', OLD.municipio_id, 'tipo', OLD.tipo),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");


        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_facultad;
    CREATE TRIGGER tr_insert_facultad
    AFTER INSERT ON facultades
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
            'facultades', 'INSERT', NEW.id_facultad,
            NULL,
            JSON_OBJECT('institucion_id', NEW.institucion_id, 'nombre', NEW.nombre),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_facultad;
    CREATE TRIGGER tr_update_facultad
    AFTER UPDATE ON facultades
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
            'facultades', 'UPDATE', NEW.id_facultad,
            JSON_OBJECT('institucion_id', OLD.institucion_id, 'nombre', OLD.nombre),
            JSON_OBJECT('institucion_id', NEW.institucion_id, 'nombre', NEW.nombre),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_facultad;
    CREATE TRIGGER tr_delete_facultad
    AFTER DELETE ON facultades
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos, fecha, created_at, updated_at
        ) VALUES (
            'facultades', 'DELETE', OLD.id_facultad,
            JSON_OBJECT('institucion_id', OLD.institucion_id, 'nombre', OLD.nombre),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");




        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_programa;
    CREATE TRIGGER tr_insert_programa
    AFTER INSERT ON programas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'programas', 'INSERT', NEW.id_programa,
            NULL,
            JSON_OBJECT(
                'institucion_id', NEW.institucion_id,
                'facultad_id', NEW.facultad_id,
                'nombre', NEW.nombre,
                'codigo_snies', NEW.codigo_snies,
                'tipo_formacion', NEW.tipo_formacion,
                'metodologia', NEW.metodologia
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_programa;
    CREATE TRIGGER tr_update_programa
    AFTER UPDATE ON programas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'programas', 'UPDATE', NEW.id_programa,
            JSON_OBJECT(
                'institucion_id', OLD.institucion_id,
                'facultad_id', OLD.facultad_id,
                'nombre', OLD.nombre,
                'codigo_snies', OLD.codigo_snies,
                'tipo_formacion', OLD.tipo_formacion,
                'metodologia', OLD.metodologia
            ),
            JSON_OBJECT(
                'institucion_id', NEW.institucion_id,
                'facultad_id', NEW.facultad_id,
                'nombre', NEW.nombre,
                'codigo_snies', NEW.codigo_snies,
                'tipo_formacion', NEW.tipo_formacion,
                'metodologia', NEW.metodologia
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_programa;
    CREATE TRIGGER tr_delete_programa
    AFTER DELETE ON programas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'programas', 'DELETE', OLD.id_programa,
            JSON_OBJECT(
                'institucion_id', OLD.institucion_id,
                'facultad_id', OLD.facultad_id,
                'nombre', OLD.nombre,
                'codigo_snies', OLD.codigo_snies,
                'tipo_formacion', OLD.tipo_formacion,
                'metodologia', OLD.metodologia
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");





        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_rol;
    CREATE TRIGGER tr_insert_rol
    AFTER INSERT ON roles
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'roles', 'INSERT', NEW.id_rol,
            NULL,
            JSON_OBJECT(
                'nombre', NEW.nombre
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_rol;
    CREATE TRIGGER tr_update_rol
    AFTER UPDATE ON roles
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'roles', 'UPDATE', NEW.id_rol,
            JSON_OBJECT(
                'nombre', OLD.nombre
            ),
            JSON_OBJECT(
                'nombre', NEW.nombre
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_rol;
    CREATE TRIGGER tr_delete_rol
    AFTER DELETE ON roles
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'roles', 'DELETE', OLD.id_rol,
            JSON_OBJECT(
                'nombre', OLD.nombre
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");




        DB::unprepared("

    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_user;
    CREATE TRIGGER tr_insert_user
    AFTER INSERT ON users
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'users', 'INSERT', NEW.id_usuario,
            NULL,
            JSON_OBJECT(
                'primer_nombre', NEW.primer_nombre,
                'segundo_nombre', NEW.segundo_nombre,
                'primer_apellido', NEW.primer_apellido,
                'segundo_apellido', NEW.segundo_apellido,
                'email', NEW.email,
                'tipo_identificacion', NEW.tipo_identificacion,
                'numero_identificacion', NEW.numero_identificacion,
                'institucion_origen_id', NEW.institucion_origen_id,
                'facultad_id', NEW.facultad_id,
                'telefono', NEW.telefono,
                'direccion', NEW.direccion,
                'pais_id', NEW.pais_id,
                'departamento_id', NEW.departamento_id,
                'municipio_id', NEW.municipio_id,
                'rol_id', NEW.rol_id,
                'activo', NEW.activo
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_user;
    CREATE TRIGGER tr_update_user
    AFTER UPDATE ON users
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'users', 'UPDATE', NEW.id_usuario,
            JSON_OBJECT(
                'primer_nombre', OLD.primer_nombre,
                'segundo_nombre', OLD.segundo_nombre,
                'primer_apellido', OLD.primer_apellido,
                'segundo_apellido', OLD.segundo_apellido,
                'email', OLD.email,
                'tipo_identificacion', OLD.tipo_identificacion,
                'numero_identificacion', OLD.numero_identificacion,
                'institucion_origen_id', OLD.institucion_origen_id,
                'facultad_id', OLD.facultad_id,
                'telefono', OLD.telefono,
                'direccion', OLD.direccion,
                'pais_id', OLD.pais_id,
                'departamento_id', OLD.departamento_id,
                'municipio_id', OLD.municipio_id,
                'rol_id', OLD.rol_id,
                'activo', OLD.activo
            ),
            JSON_OBJECT(
                'primer_nombre', NEW.primer_nombre,
                'segundo_nombre', NEW.segundo_nombre,
                'primer_apellido', NEW.primer_apellido,
                'segundo_apellido', NEW.segundo_apellido,
                'email', NEW.email,
                'tipo_identificacion', NEW.tipo_identificacion,
                'numero_identificacion', NEW.numero_identificacion,
                'institucion_origen_id', NEW.institucion_origen_id,
                'facultad_id', NEW.facultad_id,
                'telefono', NEW.telefono,
                'direccion', NEW.direccion,
                'pais_id', NEW.pais_id,
                'departamento_id', NEW.departamento_id,
                'municipio_id', NEW.municipio_id,
                'rol_id', NEW.rol_id,
                'activo', NEW.activo
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_user;
    CREATE TRIGGER tr_delete_user
    AFTER DELETE ON users
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'users', 'DELETE', OLD.id_usuario,
            JSON_OBJECT(
                'primer_nombre', OLD.primer_nombre,
                'segundo_nombre', OLD.segundo_nombre,
                'primer_apellido', OLD.primer_apellido,
                'segundo_apellido', OLD.segundo_apellido,
                'email', OLD.email,
                'tipo_identificacion', OLD.tipo_identificacion,
                'numero_identificacion', OLD.numero_identificacion,
                'institucion_origen_id', OLD.institucion_origen_id,
                'facultad_id', OLD.facultad_id,
                'telefono', OLD.telefono,
                'direccion', OLD.direccion,
                'pais_id', OLD.pais_id,
                'departamento_id', OLD.departamento_id,
                'municipio_id', OLD.municipio_id,
                'rol_id', OLD.rol_id,
                'activo', OLD.activo
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

");


        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_asignatura;
    CREATE TRIGGER tr_insert_asignatura
    AFTER INSERT ON asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'asignaturas', 'INSERT', NEW.id_asignatura,
            NULL,
            JSON_OBJECT(
                'programa_id', NEW.programa_id,
                'nombre', NEW.nombre,
                'tipo', NEW.tipo,
                'codigo_asignatura', NEW.codigo_asignatura,
                'creditos', NEW.creditos,
                'semestre', NEW.semestre,
                'horas_sena', NEW.horas_sena,
                'tiempo_presencial', NEW.tiempo_presencial,
                'tiempo_independiente', NEW.tiempo_independiente,
                'horas_totales_semanales', NEW.horas_totales_semanales,
                'modalidad', NEW.modalidad,
                'metodologia', NEW.metodologia
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_asignatura;
    CREATE TRIGGER tr_update_asignatura
    AFTER UPDATE ON asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'asignaturas', 'UPDATE', NEW.id_asignatura,
            JSON_OBJECT(
                'programa_id', OLD.programa_id,
                'nombre', OLD.nombre,
                'tipo', OLD.tipo,
                'codigo_asignatura', OLD.codigo_asignatura,
                'creditos', OLD.creditos,
                'semestre', OLD.semestre,
                'horas_sena', OLD.horas_sena,
                'tiempo_presencial', OLD.tiempo_presencial,
                'tiempo_independiente', OLD.tiempo_independiente,
                'horas_totales_semanales', OLD.horas_totales_semanales,
                'modalidad', OLD.modalidad,
                'metodologia', OLD.metodologia
            ),
            JSON_OBJECT(
                'programa_id', NEW.programa_id,
                'nombre', NEW.nombre,
                'tipo', NEW.tipo,
                'codigo_asignatura', NEW.codigo_asignatura,
                'creditos', NEW.creditos,
                'semestre', NEW.semestre,
                'horas_sena', NEW.horas_sena,
                'tiempo_presencial', NEW.tiempo_presencial,
                'tiempo_independiente', NEW.tiempo_independiente,
                'horas_totales_semanales', NEW.horas_totales_semanales,
                'modalidad', NEW.modalidad,
                'metodologia', NEW.metodologia
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_asignatura;
    CREATE TRIGGER tr_delete_asignatura
    AFTER DELETE ON asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'asignaturas', 'DELETE', OLD.id_asignatura,
            JSON_OBJECT(
                'programa_id', OLD.programa_id,
                'nombre', OLD.nombre,
                'tipo', OLD.tipo,
                'codigo_asignatura', OLD.codigo_asignatura,
                'creditos', OLD.creditos,
                'semestre', OLD.semestre,
                'horas_sena', OLD.horas_sena,
                'tiempo_presencial', OLD.tiempo_presencial,
                'tiempo_independiente', OLD.tiempo_independiente,
                'horas_totales_semanales', OLD.horas_totales_semanales,
                'modalidad', OLD.modalidad,
                'metodologia', OLD.metodologia
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");



        DB::unprepared("

    -- 7. MODIFICADO: Trigger simplificado para registrar solicitudes pendientes

    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_solicitud;
    CREATE TRIGGER tr_insert_solicitud
    AFTER INSERT ON solicitudes
    FOR EACH ROW
    BEGIN
        DECLARE accion VARCHAR(50);

        IF NEW.estado = 'En revisión' THEN
            SET accion = 'INSERT-PENDIENTE';
        ELSE
            SET accion = 'INSERT';
        END IF;

        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'solicitudes', accion, NEW.id_solicitud,
            NULL,
            JSON_OBJECT(
                'usuario_id', NEW.usuario_id,
                'programa_destino_id', NEW.programa_destino_id,
                'finalizo_estudios', NEW.finalizo_estudios,
                'fecha_finalizacion_estudios', NEW.fecha_finalizacion_estudios,
                'fecha_ultimo_semestre_cursado', NEW.fecha_ultimo_semestre_cursado,
                'fecha_solicitud', NEW.fecha_solicitud,
                'estado', NEW.estado,
                'numero_radicado', NEW.numero_radicado,
                'ruta_pdf_resolucion', NEW.ruta_pdf_resolucion
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;


    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_solicitud;
    CREATE TRIGGER tr_update_solicitud
    AFTER UPDATE ON solicitudes
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'solicitudes', 'UPDATE', NEW.id_solicitud,
            JSON_OBJECT(
                'usuario_id', OLD.usuario_id,
                'programa_destino_id', OLD.programa_destino_id,
                'finalizo_estudios', OLD.finalizo_estudios,
                'fecha_finalizacion_estudios', OLD.fecha_finalizacion_estudios,
                'fecha_ultimo_semestre_cursado', OLD.fecha_ultimo_semestre_cursado,
                'fecha_solicitud', OLD.fecha_solicitud,
                'estado', OLD.estado,
                'numero_radicado', OLD.numero_radicado,
                'ruta_pdf_resolucion', OLD.ruta_pdf_resolucion
            ),
            JSON_OBJECT(
                'usuario_id', NEW.usuario_id,
                'programa_destino_id', NEW.programa_destino_id,
                'finalizo_estudios', NEW.finalizo_estudios,
                'fecha_finalizacion_estudios', NEW.fecha_finalizacion_estudios,
                'fecha_ultimo_semestre_cursado', NEW.fecha_ultimo_semestre_cursado,
                'fecha_solicitud', NEW.fecha_solicitud,
                'estado', NEW.estado,
                'numero_radicado', NEW.numero_radicado,
                'ruta_pdf_resolucion', NEW.ruta_pdf_resolucion
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_solicitud;
    CREATE TRIGGER tr_delete_solicitud
    AFTER DELETE ON solicitudes
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'solicitudes', 'DELETE', OLD.id_solicitud,
            JSON_OBJECT(
                'usuario_id', OLD.usuario_id,
                'programa_destino_id', OLD.programa_destino_id,
                'finalizo_estudios', OLD.finalizo_estudios,
                'fecha_finalizacion_estudios', OLD.fecha_finalizacion_estudios,
                'fecha_ultimo_semestre_cursado', OLD.fecha_ultimo_semestre_cursado,
                'fecha_solicitud', OLD.fecha_solicitud,
                'estado', OLD.estado,
                'numero_radicado', OLD.numero_radicado,
                'ruta_pdf_resolucion', OLD.ruta_pdf_resolucion
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");




        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_documento;
    CREATE TRIGGER tr_insert_documento
    AFTER INSERT ON documentos
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'documentos', 'INSERT', NEW.id_documento,
            NULL,
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'usuario_id', NEW.usuario_id,
                'tipo', NEW.tipo,
                'ruta', NEW.ruta,
                'fecha_subida', NEW.fecha_subida
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_documento;
    CREATE TRIGGER tr_update_documento
    AFTER UPDATE ON documentos
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'documentos', 'UPDATE', NEW.id_documento,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'usuario_id', OLD.usuario_id,
                'tipo', OLD.tipo,
                'ruta', OLD.ruta,
                'fecha_subida', OLD.fecha_subida
            ),
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'usuario_id', NEW.usuario_id,
                'tipo', NEW.tipo,
                'ruta', NEW.ruta,
                'fecha_subida', NEW.fecha_subida
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_documento;
    CREATE TRIGGER tr_delete_documento
    AFTER DELETE ON documentos
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'documentos', 'DELETE', OLD.id_documento,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'usuario_id', OLD.usuario_id,
                'tipo', OLD.tipo,
                'ruta', OLD.ruta,
                'fecha_subida', OLD.fecha_subida
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");


        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_historial_homologacion;
    CREATE TRIGGER tr_insert_historial_homologacion
    AFTER INSERT ON historial_homologaciones
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'historial_homologaciones', 'INSERT', NEW.id_historial,
            NULL,
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'usuario_id', NEW.usuario_id,
                'estado', NEW.estado,
                'fecha', NEW.fecha,
                'observaciones', NEW.observaciones,
                'ruta_pdf_resolucion', NEW.ruta_pdf_resolucion
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_historial_homologacion;
    CREATE TRIGGER tr_update_historial_homologacion
    AFTER UPDATE ON historial_homologaciones
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'historial_homologaciones', 'UPDATE', NEW.id_historial,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'usuario_id', OLD.usuario_id,
                'estado', OLD.estado,
                'fecha', OLD.fecha,
                'observaciones', OLD.observaciones,
                'ruta_pdf_resolucion', OLD.ruta_pdf_resolucion
            ),
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'usuario_id', NEW.usuario_id,
                'estado', NEW.estado,
                'fecha', NEW.fecha,
                'observaciones', NEW.observaciones,
                'ruta_pdf_resolucion', NEW.ruta_pdf_resolucion
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_historial_homologacion;
    CREATE TRIGGER tr_delete_historial_homologacion
    AFTER DELETE ON historial_homologaciones
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'historial_homologaciones', 'DELETE', OLD.id_historial,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'usuario_id', OLD.usuario_id,
                'estado', OLD.estado,
                'fecha', OLD.fecha,
                'observaciones', OLD.observaciones,
                'ruta_pdf_resolucion', OLD.ruta_pdf_resolucion
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");



        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_contenido_programatico;
    CREATE TRIGGER tr_insert_contenido_programatico
    AFTER INSERT ON contenidos_programaticos
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'contenidos_programaticos', 'INSERT', NEW.id_contenido,
            NULL,
            JSON_OBJECT(
                'asignatura_id', NEW.asignatura_id,
                'tema', NEW.tema,
                'resultados_aprendizaje', NEW.resultados_aprendizaje,
                'descripcion', NEW.descripcion
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_contenido_programatico;
    CREATE TRIGGER tr_update_contenido_programatico
    AFTER UPDATE ON contenidos_programaticos
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'contenidos_programaticos', 'UPDATE', NEW.id_contenido,
            JSON_OBJECT(
                'asignatura_id', OLD.asignatura_id,
                'tema', OLD.tema,
                'resultados_aprendizaje', OLD.resultados_aprendizaje,
                'descripcion', OLD.descripcion
            ),
            JSON_OBJECT(
                'asignatura_id', NEW.asignatura_id,
                'tema', NEW.tema,
                'resultados_aprendizaje', NEW.resultados_aprendizaje,
                'descripcion', NEW.descripcion
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_contenido_programatico;
    CREATE TRIGGER tr_delete_contenido_programatico
    AFTER DELETE ON contenidos_programaticos
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'contenidos_programaticos', 'DELETE', OLD.id_contenido,
            JSON_OBJECT(
                'asignatura_id', OLD.asignatura_id,
                'tema', OLD.tema,
                'resultados_aprendizaje', OLD.resultados_aprendizaje,
                'descripcion', OLD.descripcion
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");




        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_solicitud_asignaturas;
    CREATE TRIGGER tr_insert_solicitud_asignaturas
    AFTER INSERT ON solicitud_asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'solicitud_asignaturas', 'INSERT', NEW.id_solicitud_asignatura,
            NULL,
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'asignaturas', NEW.asignaturas
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_solicitud_asignaturas;
    CREATE TRIGGER tr_update_solicitud_asignaturas
    AFTER UPDATE ON solicitud_asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'solicitud_asignaturas', 'UPDATE', NEW.id_solicitud_asignatura,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'asignaturas', OLD.asignaturas
            ),
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'asignaturas', NEW.asignaturas
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_solicitud_asignaturas;
    CREATE TRIGGER tr_delete_solicitud_asignaturas
    AFTER DELETE ON solicitud_asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'solicitud_asignaturas', 'DELETE', OLD.id_solicitud_asignatura,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'asignaturas', OLD.asignaturas
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");



        DB::unprepared("
    -- TRIGGER INSERT
    DROP TRIGGER IF EXISTS tr_insert_homologacion_asignaturas;
    CREATE TRIGGER tr_insert_homologacion_asignaturas
    AFTER INSERT ON homologacion_asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'homologacion_asignaturas', 'INSERT', NEW.id_homologacion,
            NULL,
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'homologaciones', NEW.homologaciones,
                'fecha', NEW.fecha
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER UPDATE
    DROP TRIGGER IF EXISTS tr_update_homologacion_asignaturas;
    CREATE TRIGGER tr_update_homologacion_asignaturas
    AFTER UPDATE ON homologacion_asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'homologacion_asignaturas', 'UPDATE', NEW.id_homologacion,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'homologaciones', OLD.homologaciones,
                'fecha', OLD.fecha
            ),
            JSON_OBJECT(
                'solicitud_id', NEW.solicitud_id,
                'homologaciones', NEW.homologaciones,
                'fecha', NEW.fecha
            ),
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;

    -- TRIGGER DELETE
    DROP TRIGGER IF EXISTS tr_delete_homologacion_asignaturas;
    CREATE TRIGGER tr_delete_homologacion_asignaturas
    AFTER DELETE ON homologacion_asignaturas
    FOR EACH ROW
    BEGIN
        INSERT INTO auditoria_general (
            tabla_afectada, tipo_accion, id_registro,
            datos_anteriores, datos_nuevos,
            fecha, created_at, updated_at
        ) VALUES (
            'homologacion_asignaturas', 'DELETE', OLD.id_homologacion,
            JSON_OBJECT(
                'solicitud_id', OLD.solicitud_id,
                'homologaciones', OLD.homologaciones,
                'fecha', OLD.fecha
            ),
            NULL,
            CURRENT_TIMESTAMP, NOW(), NOW()
        );
    END;
");






        DB::unprepared("
    -- 3. Trigger para bloquear solicitudes duplicadas del mismo estudiante para la misma asignatura
    DROP TRIGGER IF EXISTS tr_prevent_duplicate_solicitud;
    CREATE TRIGGER tr_prevent_duplicate_solicitud
    BEFORE INSERT ON solicitudes
    FOR EACH ROW
    BEGIN
        DECLARE duplicate_count INT;
        
        -- Verificar si ya existe una solicitud del mismo estudiante para el mismo programa
        SELECT COUNT(*) INTO duplicate_count
        FROM solicitudes
        WHERE usuario_id = NEW.usuario_id
        AND programa_destino_id = NEW.programa_destino_id
        AND estado IN ('Radicado','En revisión','Aprobado','Rechazado','Cerrado');
        
        IF duplicate_count > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se permite solicitudes duplicadas para el mismo programa';
        END IF;
    END;

    -- 4. Trigger para no permitir cambios en la solicitud si ya ha sido aprobada
    DROP TRIGGER IF EXISTS tr_prevent_update_approved_solicitud;
    CREATE TRIGGER tr_prevent_update_approved_solicitud
    BEFORE UPDATE ON solicitudes
    FOR EACH ROW
    BEGIN
        IF OLD.estado = 'Aprobado' THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'No se permiten modificaciones en solicitudes ya aprobadas';
        END IF;
    END;

    -- 5. Trigger para evitar espacios en blanco al principio o al final del nombre del estudiante
    DROP TRIGGER IF EXISTS tr_trim_user_names;
    CREATE TRIGGER tr_trim_user_names
    BEFORE INSERT ON users
    FOR EACH ROW
    BEGIN
        SET NEW.primer_nombre = TRIM(NEW.primer_nombre);
        SET NEW.segundo_nombre = TRIM(NEW.segundo_nombre);
        SET NEW.primer_apellido = TRIM(NEW.primer_apellido);
        SET NEW.segundo_apellido = TRIM(NEW.segundo_apellido);
    END;

    DROP TRIGGER IF EXISTS tr_trim_user_names_update;
    CREATE TRIGGER tr_trim_user_names_update
    BEFORE UPDATE ON users
    FOR EACH ROW
    BEGIN
        SET NEW.primer_nombre = TRIM(NEW.primer_nombre);
        SET NEW.segundo_nombre = TRIM(NEW.segundo_nombre);
        SET NEW.primer_apellido = TRIM(NEW.primer_apellido);
        SET NEW.segundo_apellido = TRIM(NEW.segundo_apellido);
    END;

    -- 6. Trigger para no permitir solicitudes si ya hay una solicitud en proceso
    DROP TRIGGER IF EXISTS tr_prevent_multiple_solicitudes;
    CREATE TRIGGER tr_prevent_multiple_solicitudes
    BEFORE INSERT ON solicitudes
    FOR EACH ROW
    BEGIN
        DECLARE existe INT;

        -- Verificar si ya existe al menos una solicitud en revisión
        SELECT 1 INTO existe
        FROM solicitudes
        WHERE usuario_id = NEW.usuario_id
        AND estado = 'En revisión'
        LIMIT 1;

        IF existe IS NOT NULL THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'El estudiante ya tiene una solicitud en proceso';
        END IF;
    END;

    
    
    -- 8. Trigger para rechazar solicitudes si los créditos de la asignatura origen son inferiores al mínimo permitido
    DROP TRIGGER IF EXISTS tr_validate_creditos_homologacion;
        CREATE TRIGGER tr_validate_creditos_homologacion
    BEFORE INSERT ON homologacion_asignaturas
    FOR EACH ROW
    BEGIN
        DECLARE creditos_origen DECIMAL(5,2);
        DECLARE creditos_destino DECIMAL(5,2);
        DECLARE min_creditos DECIMAL(5,2) DEFAULT 1.0;
        DECLARE asignatura_origen_id INT;
        DECLARE asignatura_destino_id INT;

        -- Extraer IDs de asignaturas del JSON
        SET asignatura_origen_id = CAST(JSON_UNQUOTE(JSON_EXTRACT(NEW.homologaciones, '$.id_asignatura_origen')) AS UNSIGNED);
        SET asignatura_destino_id = CAST(JSON_UNQUOTE(JSON_EXTRACT(NEW.homologaciones, '$.id_asignatura_destino')) AS UNSIGNED);

        -- Verificar que ambas asignaturas existen
        SELECT creditos INTO creditos_origen 
        FROM asignaturas WHERE id_asignatura = asignatura_origen_id;

        SELECT creditos INTO creditos_destino 
        FROM asignaturas WHERE id_asignatura = asignatura_destino_id;

        -- Validar créditos
        IF creditos_origen < min_creditos OR creditos_origen < (creditos_destino * 0.7) THEN

            -- Lanza error y evita la inserción
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Los créditos de la asignatura origen son insuficientes para homologación';
        END IF;
    END;

    -- 9. Trigger para validar que no haya homologaciones activas a la misma facultad
    DROP TRIGGER IF EXISTS tr_validate_facultad_homologacion;
    CREATE TRIGGER tr_validate_facultad_homologacion
    BEFORE INSERT ON solicitudes
    FOR EACH ROW
    BEGIN
        DECLARE facultad_destino_id INT;
        DECLARE existing_homologacion_count INT;
        
        -- Obtener ID de la facultad destino
        SELECT facultad_id INTO facultad_destino_id 
        FROM programas 
        WHERE id_programa = NEW.programa_destino_id;
        
        -- Verificar si ya tiene una homologación activa en la misma facultad
        SELECT COUNT(*) INTO existing_homologacion_count
        FROM solicitudes s
        JOIN programas p ON s.programa_destino_id = p.id_programa
        WHERE s.usuario_id = NEW.usuario_id
        AND p.facultad_id = facultad_destino_id
        AND s.estado = 'Aprobado';
        
        IF existing_homologacion_count > 0 THEN
            SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Ya existe una homologación activa para esta facultad';
        END IF;
    END;
");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
