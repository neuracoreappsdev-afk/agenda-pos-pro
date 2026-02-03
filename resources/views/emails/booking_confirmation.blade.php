<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin: 0; padding: 0; background-color: #0d0d0d; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #0d0d0d;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; width: 100%; background-color: #1a1a1a; border-radius: 16px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    <tr>
                        <td style="background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%); padding: 40px 32px; text-align: center;">
                            <div style="width: 56px; height: 56px; background-color: rgba(255,255,255,0.15); border-radius: 14px; margin: 0 auto 16px; line-height: 56px; font-size: 28px;">✓</div>
                            <h1 style="margin: 0; font-size: 28px; font-weight: 600; color: #ffffff; letter-spacing: -0.5px;">Reserva Agendada</h1>
                            <p style="margin: 8px 0 0; font-size: 14px; color: rgba(255,255,255,0.8);">Confirmación de tu cita en {{ $businessName }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 32px;">
                            <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #e5e5e5;">Hola <strong style="color: #ffffff;">{{ $customerName }}</strong>,</p>
                            <p style="margin: 0 0 32px; font-size: 15px; line-height: 1.7; color: #a3a3a3;">Tu cita ha sido agendada con éxito. Estos son los detalles:</p>
                            
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #262626; border-radius: 12px; margin-bottom: 32px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #737373;">Servicio</p>
                                        <p style="margin: 0 0 16px; font-size: 16px; font-weight: 600; color: #ffffff;">{{ $serviceName }}</p>

                                        <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #737373;">Especialista</p>
                                        <p style="margin: 0 0 16px; font-size: 16px; font-weight: 600; color: #ffffff;">{{ $specialistName }}</p>
                                        
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border-top: 1px solid #404040; padding-top: 20px;">
                                            <tr>
                                                <td style="padding-right: 32px;">
                                                    <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #737373;">Fecha</p>
                                                    <p style="margin: 0; font-size: 15px; font-weight: 600; color: #ffffff;">{{ $dateFormatted }}</p>
                                                </td>
                                                <td>
                                                    <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #737373;">Hora</p>
                                                    <p style="margin: 0; font-size: 15px; font-weight: 600; color: #10a37f;">{{ $timeFormatted }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <a href="{{ $confirmUrl }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #10a37f 0%, #1a7f64 100%); color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 8px; letter-spacing: 0.3px;">✓ Confirmar Asistencia</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding-right: 8px;"><a href="{{ $modifyUrl }}" style="display: inline-block; padding: 12px 24px; background-color: #262626; color: #e5e5e5; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 6px; border: 1px solid #404040;">✏️ Modificar</a></td>
                                                <td style="padding-left: 8px;"><a href="{{ $cancelUrl }}" style="display: inline-block; padding: 12px 24px; background-color: #262626; color: #ef4444; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 6px; border: 1px solid #404040;">✕ Cancelar</a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 24px 32px; background-color: #141414; border-top: 1px solid #262626;">
                            <p style="margin: 0 0 8px; font-size: 12px; color: #737373; text-align: center;">Ubicación: {{ $businessLocation ?? 'Holguines Trade Center' }}</p>
                            <p style="margin: 0; font-size: 12px; color: #525252; text-align: center;">© {{ date('Y') }} {{ $businessName }} · Powered by AgendaPOS PRO</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
