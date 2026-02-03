<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!--[if mso]>
    <style type="text/css">
        body, table, td {font-family: Arial, Helvetica, sans-serif !important;}
    </style>
    <![endif]-->
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: 'Outfit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;">
    
    <!-- Main Container -->
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f5f5f5;">
        <tr>
            <td align="center" style="padding: 40px 20px;">
                
                <!-- Email Card -->
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);">
                    
                    <!-- Header - Minimalist Gray -->
                    <tr>
                        <td style="background: #f8f9fa; padding: 40px 32px; text-align: center; border-bottom: 1px solid #e5e7eb;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <!-- Logo Icon -->
                                        <div style="width: 56px; height: 56px; background-color: #1f2937; border-radius: 14px; margin: 0 auto 16px; line-height: 56px; font-size: 28px; color: white;">
                                            ✓
                                        </div>
                                        <h1 style="margin: 0; font-size: 26px; font-weight: 600; color: #1f2937; letter-spacing: -0.5px;">
                                            Reserva Confirmada
                                        </h1>
                                        <p style="margin: 8px 0 0; font-size: 14px; color: #6b7280;">
                                            Tu cita ha sido agendada exitosamente
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 32px; background-color: #ffffff;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 24px; font-size: 16px; line-height: 1.6; color: #374151;">
                                Hola <strong style="color: #1f2937;">{{ $customerName }}</strong>,
                            </p>
                            <p style="margin: 0 0 32px; font-size: 15px; line-height: 1.7; color: #6b7280;">
                                Tu cita en <strong style="color: #1f2937;">{{ $businessName }}</strong> está lista. Aquí tienes los detalles:
                            </p>
                            
                            <!-- Appointment Details Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9fafb; border-radius: 12px; margin-bottom: 32px; border: 1px solid #e5e7eb;">
                                <tr>
                                    <td style="padding: 24px;">
                                        
                                        @foreach($services as $index => $service)
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: {{ ($index == count($services) - 1) ? '0' : '16px' }}; {{ !($index == count($services) - 1) ? 'border-bottom: 1px solid #e5e7eb; padding-bottom: 16px;' : '' }}">
                                            <tr>
                                                <td width="48" valign="top">
                                                    <div style="width: 40px; height: 40px; background: #1f2937; border-radius: 10px; text-align: center; line-height: 40px; font-size: 16px; font-weight: 700; color: white;">
                                                        {{ $index + 1 }}
                                                    </div>
                                                </td>
                                                <td style="padding-left: 16px;">
                                                    <p style="margin: 0 0 4px; font-size: 16px; font-weight: 600; color: #1f2937;">
                                                        {{ $service['name'] }}
                                                    </p>
                                                    <p style="margin: 0; font-size: 13px; color: #6b7280;">
                                                        Con <span style="color: #374151; font-weight: 500;">{{ $service['specialist'] }}</span> · {{ $service['time'] }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                        @endforeach
                                        
                                        <!-- Date/Time Summary -->
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-top: 20px; border-top: 1px solid #e5e7eb; padding-top: 20px;">
                                            <tr>
                                                <td>
                                                    <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="padding-right: 32px;">
                                                                <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af;">Fecha</p>
                                                                <p style="margin: 0; font-size: 15px; font-weight: 600; color: #1f2937;">{{ $date }}</p>
                                                            </td>
                                                            <td>
                                                                <p style="margin: 0 0 4px; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; color: #9ca3af;">Ubicación</p>
                                                                <p style="margin: 0; font-size: 15px; font-weight: 600; color: #1f2937;">{{ $businessName }}</p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Action Buttons - Minimalist Black -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <a href="{{ $confirmUrl }}" style="display: inline-block; padding: 14px 32px; background: #1f2937; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; border-radius: 8px; letter-spacing: 0.3px;">
                                            ✓ Confirmar Asistencia
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding-right: 8px;">
                                                    <a href="{{ $modifyUrl }}" style="display: inline-block; padding: 12px 24px; background-color: #f3f4f6; color: #374151; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 6px; border: 1px solid #e5e7eb;">
                                                        ✏️ Modificar
                                                    </a>
                                                </td>
                                                <td style="padding-left: 8px;">
                                                    <a href="{{ $cancelUrl }}" style="display: inline-block; padding: 12px 24px; background-color: #f3f4f6; color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 500; border-radius: 6px; border: 1px solid #e5e7eb;">
                                                        ✕ Cancelar
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f9fafb; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px; font-size: 12px; color: #9ca3af; text-align: center;">
                                Este es un correo automático. Por favor no respondas a esta dirección.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #d1d5db; text-align: center;">
                                © {{ date('Y') }} {{ $businessName }} · Todos los derechos reservados
                            </p>
                        </td>
                    </tr>
                    
                </table>
                
            </td>
        </tr>
    </table>
    
</body>
</html>
