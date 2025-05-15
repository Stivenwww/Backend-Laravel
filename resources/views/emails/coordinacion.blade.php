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

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            color: white;
            margin: 5px 0;
        }

        .status-radicado {
            background-color: #0074d9;
        }

        .status-revision {
            background-color: #f39c12;
        }

        .status-aprobado {
            background-color: #27ae60;
        }

        .status-rechazado {
            background-color: #e74c3c;
        }

        .status-cerrado {
            background-color: #7f8c8d;
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
            background: #0074d9;
            color: #ffffff;
            padding: 14px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #0056a3;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .message-box {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            background-color: #f9f9f9;
            border-left: 4px solid #0074d9;
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
            Solicitud de Homologación
        </div>

        <div class="content">
            <p>Estimado Coordinador:</p>

            @php
                $message =
                    'Se ha radicado una nueva solicitud de homologación que requiere de su amable revisión para proceder con el análisis correspondiente.';
                $action =
                    'Le agradecemos ingresar al sistema institucional para validar la solicitud y continuar con el proceso establecido.';

                if ($datos['estado'] == 'En revisión') {
                    $message = 'La solicitud de homologación ha pasado a estado "En revisión" y requiere su atención.';
                    $action = 'Por favor continúe con el proceso de evaluación.';
                } elseif ($datos['estado'] == 'Aprobado') {
                    $message = 'Una solicitud de homologación ha sido APROBADA.';
                    $action = 'La solicitud ha sido procesada exitosamente.';
                } elseif ($datos['estado'] == 'Rechazado') {
                    $message = 'Una solicitud de homologación ha sido RECHAZADA.';
                    $action = 'La solicitud no ha cumplido con los requisitos necesarios.';
                } elseif ($datos['estado'] == 'Cerrado') {
                    $message = 'Una solicitud de homologación ha sido CERRADA.';
                    $action = 'El proceso de homologación ha finalizado.';
                }
            @endphp

            <p>{{ $message }}</p>

            <div class="radicado">
                Número de radicado: {{ $datos['numero_radicado'] }}
                <br>
                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $datos['estado'])) }}">
                    {{ $datos['estado'] }}
                </span>
            </div>

            <div class="student-info">
                <h3>Datos del Estudiante</h3>
                <p><span class="label">Nombre completo:</span> {{ $datos['primer_nombre'] }}
                    {{ $datos['segundo_nombre'] }}
                    {{ $datos['primer_apellido'] }} {{ $datos['segundo_apellido'] }}</p>
                <p><span class="label">Correo electrónico:</span> {{ $datos['email'] }}</p>
                <p><span class="label">Programa destino:</span> {{ $datos['programa_destino'] }}</p>
                <p><span class="label">Finalizó estudios:</span> {{ $datos['finalizo_estudios'] }}</p>
                <p><span class="label">Fecha de solicitud:</span> {{ $datos['fecha_solicitud'] }}</p>
            </div>


            <div class="message-box">
                <p><strong>{{ $action }}</strong></p>
            </div>
            <a href="{{ config('homologaciones.url_sistema') }}/homologaciones/admin/solicitudes/{{ $datos['numero_radicado'] }}"
                class="button">
                Gestionar Solicitud
            </a>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático del Sistema de Homologaciones de la Universidad Autónoma del Cauca.</p>
            <p>Por favor, no responda a este correo. Para soporte, comuníquese con el administrador del sistema.</p>
        </div>
    </div>
</body>

</html>
