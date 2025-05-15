<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $titulo_correo }}</title>
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

        .estado {
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            margin: 15px 0;
            padding: 10px;
            border-radius: 8px;
        }

        .estado-Radicado {
            color: #0074d9;
            background-color: #e6f2ff;
        }

        .estado-En-revisión {
            color: #f39c12;
            background-color: #fff7e6;
        }

        .estado-Aprobado {
            color: #2ecc71;
            background-color: #e6fff0;
        }

        .estado-Rechazado {
            color: #e74c3c;
            background-color: #ffe6e6;
        }

        .estado-Cerrado {
            color: #7f8c8d;
            background-color: #f0f0f0;
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

        .mensaje-personalizado {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #0074d9;
            margin: 20px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        .button {
            display: block;
            width: 90%;
            margin: 30px auto 10px;
            text-align: center;
            background: #0074d9;
            color: #ffffff;
            padding: 14px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #0056a4;
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
            {{ $titulo_correo }}
        </div>

        <div class="content">
            <p>Estimado/a {{ $primer_nombre }} {{ $primer_apellido }}:</p>

            <div class="mensaje-personalizado">
                {{ $mensaje_personalizado }}
            </div>

            <div class="radicado">
                Número de radicado: {{ $numero_radicado }}
            </div>

            <div class="estado estado-{{ str_replace(' ', '-', $estado) }}">
                Estado actual: {{ $estado }}
            </div>

            <div class="student-info">
                <h3>Datos del Aspirante</h3>
                <p><span class="label">Nombre completo:</span> {{ $primer_nombre }} {{ $segundo_nombre }} {{ $primer_apellido }} {{ $segundo_apellido }}</p>
                <p><span class="label">Programa destino:</span> {{ $programa_destino }}</p>
                <p><span class="label">Finalizó estudios:</span> {{ $finalizo_estudios }}</p>
                <p><span class="label">Fecha de solicitud:</span> {{ $fecha_solicitud }}</p>
            </div>

            <a href="{{ config('homologaciones.url_sistema') }}/homologaciones/admin/solicitudes/{{ $numero_radicado }}" class="button">
                Ver Detalles en el Sistema
            </a>
        </div>

        <div class="footer">
            <p>Este es un correo automático del Sistema de Homologaciones de la Corporación Universitaria Autónoma del Cauca.</p>
            <p>No responda a este correo. Para soporte, contacte al administrador del sistema.</p>
        </div>
    </div>
</body>
</html>
