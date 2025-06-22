<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $data['asunto'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', 'Atkinson Hyperlegible', Arial, sans-serif; background-color: #ffffff; color: #2c3e50; line-height: 1.6;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="650" cellpadding="0" cellspacing="0" border="0" style="max-width: 650px; background: #ffffff; border-radius: 20px; box-shadow: 0 12px 35px rgba(12, 43, 90, 0.15); overflow: hidden;">
                    <!-- HEADER -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #0c2b5a, #0a4c8b); text-align: center; padding: 35px 20px;">
                            <img src="https://drive.google.com/uc?export=view&id=1I_Wb8rN_qhM5DvIa1oatC21DYhiKAjDN" alt="Uniautónoma del Cauca" width="150" style="margin-bottom: 15px;">
                            <h1 style="font-size: 28px; font-weight: 700; margin: 0; color: #ffffff;">{{ $data['asunto'] }}</h1>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding: 35px 30px; font-size: 16px;">
                            <p style="font-size: 20px; margin-bottom: 25px; color: #0c2b5a; font-weight: 500;">
                                Nuevo mensaje de <strong>{{ $data['nombre'] }}</strong>
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0;">
                                <tr>
                                    <td style="background-color: rgba(22, 120, 194, 0.05); padding: 20px; border-radius: 12px; border-left: 5px solid #1678c2;">
                                        <strong>Email de contacto:</strong> {{ $data['email'] }}<br>
                                        @if(isset($data['telefono']) && !empty($data['telefono']))
                                        <strong>Teléfono:</strong> {{ $data['telefono'] }}<br>
                                        @endif
                                        <br>
                                        <strong>Mensaje:</strong><br>
                                        {!! nl2br(e($data['mensaje'])) !!}
                                    </td>
                                </tr>
                            </table>

                            <!-- Professional note -->
                            <p style="text-align: center; font-size: 15px; color: #34495e; margin-top: 40px;">
                                Por favor, responda a este mensaje a la mayor brevedad posible para garantizar una atención oportuna y eficaz.
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background-color: #051835; color: #ffffff; padding: 30px 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px;">
                            <table width="100%">
                                <tr>
                                    <td align="center">
                                        <img src="https://drive.google.com/uc?export=view&id=1I_Wb8rN_qhM5DvIa1oatC21DYhiKAjDN" alt="Uniautónoma del Cauca" width="150" style="margin-bottom: 15px;">
                                        <h3 style="font-size: 20px; font-weight: 700; color: #00a2ff; text-transform: uppercase;">Formulario de contacto - Uniautónoma</h3>
                                        <p style="font-size: 12px; color: rgba(255, 255, 255, 0.6); margin: 5px 0;">
                                            &copy; 2025 Uniautónoma del Cauca. Todos los derechos reservados.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
