<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Credenciales de Homologaciones</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 80%;
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #0056b3;
            text-align: center;
        }
        p {
            color: #333;
            font-size: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Credenciales de acceso</h1>
        <p>Estimado/a aspirante,</p>
        <p>Le informamos que se han generado sus credenciales de acceso a la plataforma de homologaciones de la Universidad Autónoma del Cauca.</p>
        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>Contraseña:</strong> {{ $password }}</p>
        <p>Por favor, ingrese a la plataforma y valide sus credenciales.</p>
        <p>Si tiene alguna duda, no dude en contactarnos.</p>
        <div class="footer">
            <p>Atentamente, <br> Equipo de Soporte de Homologaciones</p>
        </div>
    </div>
</body>
</html>

