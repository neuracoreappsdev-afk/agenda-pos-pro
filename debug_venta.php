<?php
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\SalesController;

// 1. Asegurar sesión de caja
$session = \App\Models\CashRegisterSession::where('status', 'open')->first();
if (!$session) {
    $session = \App\Models\CashRegisterSession::create([
        'user_id' => 1,
        'status' => 'open',
        'opening_amount' => 100000,
        'opened_at' => date('Y-m-d H:i:s')
    ]);
}

// 2. Asegurar Especialista y Producto
$specialist = \App\Models\Specialist::first();
if (!$specialist) {
    $specialist = \App\Models\Specialist::create([
        'name' => 'Luis',
        'commissions' => ['services_percentage' => 40, 'products_percentage' => 10]
    ]);
}

$product = \App\Models\Product::first();
if (!$product) {
    $product = \App\Models\Product::create([
        'name' => 'Laca Pro',
        'quantity' => 10,
        'price' => 20000
    ]);
}

$payload = [
  "cliente_id" => 1,
  "origen" => "pos",
  "appointment_id" => null,
  "items" => [
    [
      "tipo" => "servicio",
      "item_id" => 1,
      "cantidad" => 1,
      "precio_unitario" => 50000,
      "specialist_id" => $specialist->id,
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
  "usuario_id" => 1
];

$controller = new SalesController();
try {
    $response = $controller->procesarVenta($payload);
    print_r(json_decode($response->getContent(), true));
} catch (\Exception $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
