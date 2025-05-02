<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Homologación</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background-color: #f2f8ff;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 64, 128, 0.15);
        }

        .header {
            background: #0074d9;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            border-radius: 12px 12px 0 0;
            font-size: 24px;
            font-weight: 600;
        }

        .content {
            padding: 20px;
            font-size: 16px;
            line-height: 1.6;
        }

        .radicado {
            font-size: 18px;
            font-weight: 600;
            color: #0074d9;
            text-align: center;
            margin: 20px 0;
            padding: 12px;
            border: 1px dashed #0074d9;
            background-color: #e6f2ff;
            border-radius: 8px;
        }

        .student-info {
            background-color: #f0f7ff;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }

        .student-info h3 {
            margin-top: 0;
            color: #004080;
            border-bottom: 1px solid #c0d3e8;
            padding-bottom: 8px;
            font-size: 18px;
        }

        .label {
            font-weight: 600;
            color: #004080;
        }

        .button {
            display: block;
            width: 90%;
            margin: 30px auto 10px;
            text-align: center;
            background: #ffffff;
            color: #ffffff;
            padding: 14px;
            border: #004080 2px solid;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .button:hover {
            color: #ffffff;
            background: #1858b8;
            border: #ffffff 2px solid;
        }

        .student-info h3 :hover {
            margin-top: 0;
            color: #ffffff;
            border-bottom: 1px solid #c0d3e8;
            padding-bottom: 8px;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        @media (max-width: 640px) {
            .container {
                margin: 15px;
                padding: 15px;
            }

            .button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            Nueva Solicitud de Homologación
        </div>

        <div class="content">
            <p>Estimado/a aspirante:</p>
            <p>Su solicitud de homologación ha sido registrada exitosamente. Le recomendamos estar atento/a a la plataforma, donde la Corporación Universitaria Autónoma del Cauca le notificará los pasos a seguir. Al ingresar a la plataforma encontrará información adicional relacionada con su solicitud.</p>

            <div class="radicado">
                Número de radicado: {{ $numero_radicado }}
            </div>

            <div class="student-info">
                <h3>Datos del Aspirante</h3>
                <p><span class="label">Nombre completo:</span> {{ $primer_nombre }} {{ $segundo_nombre }} {{ $primer_apellido }} {{ $segundo_apellido }}</p>
                <p><span class="label">Programa destino:</span> {{ $programa_destino }}</p>
                <p><span class="label">Estado actual:</span> {{ $estado }}</p>
            </div>

            <a href="{{ config('homologaciones.url_sistema') }}/homologaciones/admin/solicitudes/{{ $numero_radicado }}" class="button">
                Ver Solicitud en el Sistema
            </a>
        </div>

        <div class="footer">
            <p>Este es un correo automático del Sistema de Homologaciones de la Universidad Autónoma del Cauca.</p>
            <p>No responda a este correo. Para soporte, contacte al administrador del sistema.</p>
        </div>
    </div>
</body>
</html>
