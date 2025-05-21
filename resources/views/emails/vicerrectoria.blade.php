<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homologación Pendiente por Revisar</title>
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
                                        <!-- URL corregida para la imagen -->
                                        <img src="https://drive.google.com/uc?export=view&id=1PDYXH441oPwMX2HFaR2qK0oapII_GtJG" alt="Quimerito" width="100" height="100" style="display: block; margin: 0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <h1 style="font-family: 'Atkinson Hyperlegible', Arial, sans-serif; font-size: 28px; font-weight: 700; margin: 0; padding: 12px 20px; color: #ffffff; text-align: center; background-color: rgba(0, 0, 0, 0.15); border-radius: 12px; display: inline-block; box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);">Homologación Pendiente por Revisar</h1>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td class="email-content" style="padding: 35px 30px; font-size: 16px; background: #ffffff;">
                            <!-- GREETING -->
                            <p style="font-size: 20px; margin-bottom: 25px; color: #0c2b5a; font-weight: 500; border-bottom: 2px solid rgba(12, 43, 90, 0.1); padding-bottom: 12px;">Estimado/a <strong>Vicerrector</strong>,</p>

                            <!-- MENSAJE PERSONALIZADO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td style="background-color: rgba(22, 120, 194, 0.05); padding: 25px; border-radius: 16px; font-size: 16px; line-height: 1.7; border-left: 5px solid #1678c2; box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);">
                                        <p>Le informamos que el Coordinador Académico ha <strong>modificado el estado</strong> de una solicitud de homologación. Ahora requiere su <strong>revisión y validación final</strong>.</p>

                                        <p>Por favor, revise la información registrada. Usted debe decidir si:</p>
                                        <ul style="padding-left: 20px; margin-bottom: 0;">
                                            <li style="margin-bottom: 8px;"><strong>Aprueba</strong> la solicitud de homologación,</li>
                                            <li style="margin-bottom: 8px;"><strong>Rechaza</strong> la solicitud, o</li>
                                            <li><strong>Cierra</strong> el proceso si ya no se requiere acción adicional.</li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>

                            <!-- RADICADO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center" style="font-size: 20px; font-weight: 600; color: #0a4c8b; text-align: center; padding: 20px; border: 2px dashed #1678c2; background-color: rgba(16, 76, 139, 0.05); border-radius: 16px;">
                                        Número de radicado: <strong>{{ $numero_radicado }}</strong>
                                    </td>
                                </tr>
                            </table>

                            <!-- ESTADO -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0;">
                                <tr>
                                    <td align="center" class="estado estado-{{ str_replace(' ', '-', $estado) }}" style="text-align: center; font-size: 18px; font-weight: 600; padding: 20px 15px; border-radius: 16px; color: #ffffff; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); background:
                                    @if($estado == 'Radicado')
                                        linear-gradient(135deg, #0c2b5a, #1678c2)
                                    @elseif($estado == 'En revisión')
                                        linear-gradient(135deg, #b15911, #e67e22)
                                    @elseif($estado == 'Aprobado')
                                        linear-gradient(135deg, #1d7d4d, #27ae60)
                                    @elseif($estado == 'Rechazado')
                                        linear-gradient(135deg, #a51f1f, #e53e3e)
                                    @elseif($estado == 'Cerrado')
                                        linear-gradient(135deg, #2c3e50, #576574)
                                    @else
                                        linear-gradient(135deg, #0c2b5a, #1678c2)
                                    @endif
                                    ;">
                                        Estado actual: {{ $estado }}
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
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $primer_nombre }} {{ $segundo_nombre }} {{ $primer_apellido }} {{ $segundo_apellido }}</span>
                                                </td>
                                                <td width="50%" valign="top" style="padding-left: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Correo electrónico:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $email }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" valign="top" style="padding-right: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Programa destino:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $programa_destino }}</span>
                                                </td>
                                                <td width="50%" valign="top" style="padding-left: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Finalizó estudios:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $finalizo_estudios }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="50%" valign="top" style="padding-right: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Fecha de solicitud:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $fecha_solicitud }}</span>
                                                </td>
                                                <td width="50%" valign="top" style="padding-left: 10px; padding-bottom: 15px;">
                                                    <span style="font-weight: 600; color: #0c2b5a; display: block; margin-bottom: 6px; font-size: 14px;">Estado actual:</span>
                                                    <span style="font-size: 16px; background-color: rgba(255, 255, 255, 0.7); padding: 8px 12px; border-radius: 6px; border-left: 3px solid #1678c2; display: block;">{{ $estado }}</span>
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
                                        <a href="{{ config('homologaciones.url_sistema') }}/homologaciones/admin/solicitudes/{{ $numero_radicado }}" style="display: inline-block; min-width: 240px; text-align: center; background: linear-gradient(135deg, #0a4c8b, #1678c2); color: #ffffff; padding: 16px 30px; border-radius: 50px; text-decoration: none; font-weight: 600; box-shadow: 0 6px 15px rgba(10, 76, 139, 0.3); font-family: 'Atkinson Hyperlegible', Arial, sans-serif; font-size: 17px; letter-spacing: 0.5px; border: none;">
                                            Revisar y Tomar Decisión
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
                                        <!-- URL corregida para la imagen del footer -->
                                        <img src="https://drive.google.com/uc?export=view&id=1PDYXH441oPwMX2HFaR2qK0oapII_GtJG" alt="Uniautónoma del Cauca" width="150" style="display: block; margin: 0 auto;">
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 15px; text-align: center; color: #00a2ff; text-transform: uppercase; letter-spacing: 1px;">Sistema de Homologaciones</h3>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 15px; font-size: 12px; color: rgba(255, 255, 255, 0.6);">
                                        <p>Este es un correo automático del Sistema de Homologaciones de la Universidad Autónoma del Cauca.</p>
                                        <p>No responda a este correo. Para soporte, contacte al administrador del sistema.</p>
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
