<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Estado de Solicitud</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Usa el mismo CSS que enviaste arriba */
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
            background: #004080;
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
            color: #004080;
            text-align: center;
            margin: 20px 0;
            padding: 12px;
            border: 1px dashed #004080;
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
            color: #004080;
            padding: 14px;
            border: #004080 2px solid;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s ease;
        }
        .button:hover {
            color: #ffffff;
            background: #004080;
            border: #004080 2px solid;
        }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #777;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Estado de tu solicitud actualizado</div>

        <div class="content">
            <p>Hola <strong>{{ $primer_nombre }} {{ $primer_apellido }}</strong>,</p>
            <p>Te informamos que el estado de tu solicitud con número de radicado <strong>{{ $numero_radicado }}</strong> ha cambiado a:</p>

            <div class="radicado">{{ $estado }}</div>

            <div class="student-info">
                <h3>Detalles de la solicitud</h3>
                <p><span class="label">Programa solicitado:</span> {{ $programa_destino }}</p>
                <p><span class="label">Fecha de solicitud:</span> {{ $fecha_solicitud }}</p>
                <p><span class="label">¿Finalizaste estudios?:</span> {{ $finalizo_estudios }}</p>
            </div>

            <a href="{{ url('/estudiante/solicitudes') }}" class="button">Ver solicitud</a>

            <p style="margin-top: 20px;">Gracias por usar nuestra plataforma.</p>
        </div>

        <div class="footer">
            Universidad Autónoma – Todos los derechos reservados
        </div>
    </div>
</body>
</html>
