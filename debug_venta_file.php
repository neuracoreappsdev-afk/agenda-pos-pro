<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\SalesController;

$payload = [
  "cliente_id" => 5,
  "origen" => "pos",
  "appointment_id" => null,
  "items" => [
    [
      "tipo" => "servicio",
      "item_id" => 1,
      "cantidad" => 1,
      "precio_unitario" => 50000,
      "specialist_id" => 1,
      "descuento" => 0,
      "nombre" => "Corte Estilo"
    ]
  ],
  "pagos" => [
    [
      "metodo_pago_id" => 1,
      "monto" => 50000,
      "propina" => 0
    ]
  ],
  "impuestos" => 0,
  "descuento_global" => 0,
  "usuario_id" => 2
];

$controller = new SalesController();
$response = $controller->procesarVenta($payload);
file_put_contents('venta_error.json', $response->getContent());
echo "Done\n";
