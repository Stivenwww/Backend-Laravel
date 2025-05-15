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
            background: #003366;
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
            color: #003366;
            text-align: center;
            margin: 20px 0;
            padding: 12px;
            border: 1px dashed #003366;
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

        .status-Radicado {
            background-color: #0074d9;
        }

        .status-En-revisión {
            background-color: #f39c12;
        }

        .status-Aprobado {
            background-color: #27ae60;
        }

        .status-Rechazado {
            background-color: #e74c3c;
        }

        .status-Cerrado {
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
            color: #003366;
            border-bottom: 1px solid #c0d3e8;
            padding-bottom: 8px;
            font-size: 18px;
        }

        .label {
            font-weight: 600;
            color: #004080;
        }

        .message-box {
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            background-color: #f9f9f9;
            border-left: 4px solid #003366;
        }

        .button {
            display: block;
            width: 90%;
            margin: 30px auto 10px;
            text-align: center;
            background: #003366;
            color: #ffffff;
            padding: 14px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }

        .button:hover {
            background: #002244;
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
            Notificación de Solicitud de Homologación
        </div>

        <div class="content">
            <p>Estimada Secretaria</p>

            @php
                function obtenerMensajePorEstado($estado) {
                    switch ($estado) {
                        case 'Radicado':
                            return 'Se ha recibido una nueva solicitud de homologación que requiere su atención. ' .
                                   'Por favor, proceda con la revisión inicial de documentos y requisitos.';
                        case 'En revisión':
                            return 'La solicitud ha pasado a estado de revisión. ' .
                                   'Se requiere su seguimiento para coordinar el análisis académico correspondiente.';
                        case 'Aprobado':
                            return 'La solicitud de homologación ha sido APROBADA. ' .
                                   'Se requiere su gestión para la emisión de resolución y notificación oficial.';
                        case 'Rechazado':
                            return 'La solicitud de homologación ha sido RECHAZADA. ' .
                                   'Favor proceder con los procedimientos administrativos para cierre del expediente.';
                        case 'Cerrado':
                            return 'El proceso de homologación ha sido cerrado. ' .
                                   'Favor archivar el expediente en el repositorio digital correspondiente.';
                        default:
                            return 'La solicitud de homologación ha cambiado de estado. ' .
                                   'Se requiere su intervención para continuar con el proceso administrativo.';
                    }
                }

                $mensajeEstado = obtenerMensajePorEstado($datos['estado']);
            @endphp

            <p>{{ $mensajeEstado }}</p>

            <div class="radicado">
                Número de radicado: {{ $datos['numero_radicado'] }}
                <br>
                <span class="status-badge status-{{ str_replace(' ', '-', $datos['estado']) }}">
                    {{ $datos['estado'] }}
                </span>
            </div>

            <div class="student-info">
                <h3>Datos del Estudiante</h3>
                <p><span class="label">Nombre completo:</span> {{ $datos['primer_nombre'] }} {{ $datos['segundo_nombre'] }}
                    {{ $datos['primer_apellido'] }} {{ $datos['segundo_apellido'] }}</p>
                <p><span class="label">Correo electrónico:</span> {{ $datos['email'] }}</p>
                <p><span class="label">Programa destino:</span> {{ $datos['programa_destino'] }}</p>
                <p><span class="label">Finalizó estudios:</span> {{ $datos['finalizo_estudios'] }}</p>
                <p><span class="label">Fecha de solicitud:</span> {{ $datos['fecha_solicitud'] }}</p>
            </div>


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
