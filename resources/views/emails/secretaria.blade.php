<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitud de Homologación</title>
    <!--[if !mso]><!-->
    <link href="https://fonts.googleapis.com/css2?family=Atkinson+Hyperlegible:wght@400;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!--<![endif]-->
</head>

<body style="margin: 0; padding: 0; font-family: 'Inter', 'Atkinson Hyperlegible', Arial, sans-serif; background-color: #ffffff; color: #2c3e50; line-height: 1.6;">
    <!-- CONTAINER -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <!-- MAIN EMAIL CONTAINER -->
                <table class="email-container" width="650" cellpadding="0" cellspacing="0" border="0" style="max-width: 650px; margin: 0 auto; background: #ffffff; border-radius: 20px; box-shadow: 0 12px 35px rgba(12, 43, 90, 0.15); overflow: hidden;">
                    <!-- HEADER -->
                    <tr>
                        <td class="email-header" style="background: linear-gradient(135deg, #0c2b5a, #0a4c8b); text-align: center; padding: 35px 20px; position: relative;">
                            <!-- LOGO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 15px;">
                                        <img src="https://biblioteca.uniautonoma.edu.co/images/logo_home.png" alt="Quimerito" width="100" height="100" style="display: block; margin: 0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <h1 style="font-family: 'Atkinson Hyperlegible', Arial, sans-serif; font-size: 28px; font-weight: 700; margin: 0; padding: 12px 20px; color: #ffffff; text-align: center; background-color: rgba(0, 0, 0, 0.15); border-radius: 12px; display: inline-block; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);">Notificación de Solicitud de Homologación</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td class="email-content" style="padding: 35px 30px; font-size: 16px; background: #ffffff;">
                            <!-- GREETING -->
                            <p style="font-size: 20px; margin-bottom: 25px; color: #0c2b5a; font-weight: 500; border-bottom: 2px solid rgba(12, 43, 90, 0.1); padding-bottom: 12px;">Estimada <strong>Secretaria</strong>,</p>

                            <!-- MENSAJE PERSONALIZADO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td style="background-color: rgba(22, 120, 194, 0.05); padding: 25px; border-radius: 16px; font-size: 16px; line-height: 1.7; border-left: 5px solid #1678c2; box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);">
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

                                        {{ $mensajeEstado }}
                                    </td>
                                </tr>
                            </table>

                            <!-- RADICADO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center" style="font-size: 20px; font-weight: 600; color: #0a4c8b; text-align: center; padding: 20px; border: 2px dashed #1678c2; background-color: rgba(16, 76, 139, 0.05); border-radius: 16px;">
                                        Número de radicado: <strong>{{ $datos['numero_radicado'] }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- ESTADO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center" class="estado estado-{{ str_replace(' ', '-', $datos['estado']) }}" style="text-align: center; font-size: 18px; font-weight: 600; padding: 20px 15px; border-radius: 16px; color: #ffffff; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); background:
                                    @if($datos['estado'] == 'Radicado')
                                        linear-gradient(135deg, #0c2b5a, #1678c2)
                                    @elseif($datos['estado'] == 'En revisión')
                                        linear-gradient(135deg, #b15911, #e67e22)
                                    @elseif($datos['estado'] == 'Aprobado')
                                        linear-gradient(135deg, #1d7d4d, #27ae60)
                                    @elseif($datos['estado'] == 'Rechazado')
                                        linear-gradient(135deg, #a51f1f, #e53e3e)
                                    @elseif($datos['estado'] == 'Cerrado')
                                        linear-gradient(135deg, #2c3e50, #576574)
                                    @else
                                        linear-gradient(135deg, #0c2b5a, #1678c2)
                                    @endif
                                    ;">
                                        Estado actual: {{ $datos['estado'] }}
                                    </td>
                                </tr>
                            </table>

                            <!-- STUDENT INFO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 35px; background-color: #f9fafc; padding: 30px; border-radius: 16px; box-shadow: 0 8px 25px rgba(12, 43, 90, 0.08); border: 1px solid #e0e7ff;">
                                <tr>
                                    <td>
                                        <h3 style="margin-top: 0; color: #0c2b5a; border-bottom: 2px solid #1678c2; padding-bottom: 12px; font-size: 20px; font-family: 'Atkinson Hyperlegible', Arial, sans-serif;">Datos del Estudiante</h3>
                                    </td>
                                </tr>

                                <!-- INFO GRID FOR DESKTOP -->
                                <tr>
                                    <td>
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="50%" valign="top" style="padding-right: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Nombre completo:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $datos['primer_nombre'] }} {{ $datos['segundo_nombre'] }} {{ $datos['primer_apellido'] }} {{ $datos['segundo_apellido'] }}</span>
                                                </td>
                                                <td width="50%" valign="top" style="padding-left: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Correo electrónico:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $datos['email'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" valign="top" style="padding-right: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Programa destino:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $datos['programa_destino'] }}</span>
                                                </td>
                                                <td width="50%" valign="top" style="padding-left: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Finalizó estudios:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $datos['finalizo_estudios'] }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" valign="top" style="padding-right: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Fecha de solicitud:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $datos['fecha_solicitud'] }}</span>
                                                </td>
                                                <td width="50%" valign="top" style="padding-left: 10px; padding-bottom: 15px;">
                                                    <!-- Esta celda se deja vacía para mantener el equilibrio en el diseño -->
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- BUTTON -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top: 40px;">
                                <tr>
                                    <td align="center" style="padding: 10px;">
                                        <a href="{{ config('homologaciones.url_sistema') }}/homologaciones/admin/solicitudes/{{ $datos['numero_radicado'] }}" style="display: inline-block; min-width: 240px; text-align: center; background: linear-gradient(135deg, #0a4c8b, #1678c2); color: #ffffff; padding: 16px 30px; border-radius: 50px; text-decoration: none; font-weight: 600; box-shadow: 0 6px 15px rgba(10, 76, 139, 0.3); font-family: 'Atkinson Hyperlegible', Arial, sans-serif; font-size: 17px; letter-spacing: 0.5px; border: none;">
                                            Gestionar Solicitud
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td class="email-footer" style="background-color: #051835; color: #ffffff; padding: 30px 20px; border-bottom-left-radius: 20px; border-bottom-right-radius: 20px; position: relative;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <img src="https://www.uniautonoma.edu.co/wp-content/uploads/2022/11/logo.png" alt="Uniautónoma del Cauca" width="150" style="display: block; margin: 0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 15px; text-align: center; color: #00a2ff; text-transform: uppercase; letter-spacing: 1px;">Homologaciones Uniautónoma 2025</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <p style="font-size: 14px; color: rgba(255, 255, 255, 0.7); text-align: center; margin-bottom: 5px;">Este es un mensaje automático del Sistema de Homologaciones de la Universidad Autónoma del Cauca.</p>
                                        <p style="font-size: 14px; color: rgba(255, 255, 255, 0.7); text-align: center; margin-top: 0;">Por favor, no responda a este correo. Para soporte, comuníquese con el administrador del sistema.</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 15px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; color: #ffffff; text-decoration: none; text-align: center; vertical-align: middle; line-height: 36px;">
                                                        <img src="https://cdn4.iconfinder.com/data/icons/social-media-icons-the-circle-set/48/facebook_circle-512.png" width="24" height="24" alt="Facebook" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; color: #ffffff; text-decoration: none; text-align: center; vertical-align: middle; line-height: 36px;">
                                                        <img src="https://cdn4.iconfinder.com/data/icons/social-media-icons-the-circle-set/48/instagram_circle-512.png" width="24" height="24" alt="Instagram" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; color: #ffffff; text-decoration: none; text-align: center; vertical-align: middle; line-height: 36px;">
                                                        <img src="https://cdn4.iconfinder.com/data/icons/social-media-icons-the-circle-set/48/twitter_circle-512.png" width="24" height="24" alt="Twitter" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="#" style="display: inline-block; width: 36px; height: 36px; background-color: rgba(255, 255, 255, 0.1); border-radius: 50%; color: #ffffff; text-decoration: none; text-align: center; vertical-align: middle; line-height: 36px;">
                                                        <img src="https://cdn4.iconfinder.com/data/icons/social-media-icons-the-circle-set/48/linkedin_circle-512.png" width="24" height="24" alt="LinkedIn" style="vertical-align: middle;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="font-size: 12px; color: rgba(255, 255, 255, 0.6);">
                                        &copy; 2025 Uniautónoma del Cauca. Todos los derechos reservados.
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
