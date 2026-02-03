<?php

namespace App\Services;

use App\Models\Setting;
use Exception;

/**
 * Servicio para interactuar con la API de WhatsApp Business (Meta Cloud API)
 * Gestiona el envío de mensajes, plantillas y botones interactivos
 */
class WhatsAppService
{
    private $apiUrl;
    private $phoneNumberId;
    private $accessToken;

    public function __construct()
    {
        $this->apiUrl = 'https://graph.facebook.com/v18.0';
        $this->phoneNumberId = Setting::get('whatsapp_phone_id', '');
        $this->accessToken = Setting::get('whatsapp_api_token', '');
    }

    /**
     * Envía una plantilla de confirmación de cita con botones
     * 
     * @param string $telefono - Número del cliente (formato: 573001234567)
     * @param array $datos - ['nombre', 'fecha', 'hora', 'servicio', 'especialista']
     * @return array
     */
    public function enviarPlantillaConfirmacion($telefono, $datos)
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $telefono,
            'type' => 'template',
            'template' => [
                'name' => 'confirmacion_cita',
                'language' => [
                    'code' => 'es'
                ],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $datos['nombre']],
                            ['type' => 'text', 'text' => $datos['fecha']],
                            ['type' => 'text', 'text' => $datos['hora']],
                            ['type' => 'text', 'text' => $datos['servicio']],
                            ['type' => 'text', 'text' => $datos['especialista'] ?? 'Nuestro equipo']
                        ]
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'quick_reply',
                        'index' => '0',
                        'parameters' => [
                            ['type' => 'payload', 'payload' => 'CONFIRMAR_' . $datos['cita_id']]
                        ]
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'quick_reply',
                        'index' => '1',
                        'parameters' => [
                            ['type' => 'payload', 'payload' => 'MODIFICAR_' . $datos['cita_id']]
                        ]
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'quick_reply',
                        'index' => '2',
                        'parameters' => [
                            ['type' => 'payload', 'payload' => 'CANCELAR_' . $datos['cita_id']]
                        ]
                    ]
                ]
            ]
        ];

        return $this->enviarMensaje($url, $payload);
    }

    /**
     * Envía un mensaje de texto simple
     * 
     * @param string $telefono
     * @param string $mensaje
     * @return array
     */
    public function enviarMensajeTexto($telefono, $mensaje)
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $telefono,
            'type' => 'text',
            'text' => [
                'body' => $mensaje
            ]
        ];

        return $this->enviarMensaje($url, $payload);
    }

    /**
     * Envía un mensaje con botones interactivos
     * 
     * @param string $telefono
     * @param string $texto
     * @param array $botones - Máximo 3 botones
     * @return array
     */
    public function enviarMensajeConBotones($telefono, $texto, $botones)
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $botonesFormateados = [];
        foreach (array_slice($botones, 0, 3) as $index => $boton) {
            $botonesFormateados[] = [
                'type' => 'reply',
                'reply' => [
                    'id' => $boton['id'] ?? "boton_$index",
                    'title' => substr($boton['texto'], 0, 20) // Max 20 caracteres
                ]
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $telefono,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'button',
                'body' => [
                    'text' => $texto
                ],
                'action' => [
                    'buttons' => $botonesFormateados
                ]
            ]
        ];

        return $this->enviarMensaje($url, $payload);
    }

    /**
     * Envía una lista interactiva (menú desplegable)
     * 
     * @param string $telefono
     * @param string $titulo
     * @param array $opciones
     * @return array
     */
    public function enviarListaInteractiva($telefono, $titulo, $opciones)
    {
        $url = "{$this->apiUrl}/{$this->phoneNumberId}/messages";

        $rows = [];
        foreach (array_slice($opciones, 0, 10) as $index => $opcion) {
            $rows[] = [
                'id' => $opcion['id'] ?? "opcion_$index",
                'title' => substr($opcion['titulo'], 0, 24),
                'description' => substr($opcion['descripcion'] ?? '', 0, 72)
            ];
        }

        $payload = [
            'messaging_product' => 'whatsapp',
            'to' => $telefono,
            'type' => 'interactive',
            'interactive' => [
                'type' => 'list',
                'body' => [
                    'text' => $titulo
                ],
                'action' => [
                    'button' => 'Ver Opciones',
                    'sections' => [
                        [
                            'title' => 'Servicios',
                            'rows' => $rows
                        ]
                    ]
                ]
            ]
        ];

        return $this->enviarMensaje($url, $payload);
    }

    /**
     * Método privado para enviar el mensaje a la API
     * 
     * @param string $url
     * @param array $payload
     * @return array
     */
    private function enviarMensaje($url, $payload)
    {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->accessToken,
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $responseData = json_decode($response, true);

            \Log::info('WhatsApp API Response:', [
                'status' => $httpCode,
                'response' => $responseData
            ]);

            return [
                'success' => $httpCode === 200,
                'status' => $httpCode,
                'data' => $responseData
            ];

        } catch (Exception $e) {
            \Log::error('Error enviando mensaje WhatsApp: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verifica si el servicio está configurado correctamente
     * 
     * @return bool
     */
    public function estaConfigurado()
    {
        return !empty($this->phoneNumberId) && !empty($this->accessToken);
    }
}
