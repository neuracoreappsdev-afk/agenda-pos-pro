<?php
echo "1. Starting...\n";
require 'bootstrap/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "2. Bootstrap OK\n";

use Carbon\Carbon;

$startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
$endDate = Carbon::now()->format('Y-m-d');

// Test CashMovement
echo "3. Testing CashMovement model...\n";
try {
    $movements = \App\Models\CashMovement::whereBetween('movement_date', [$startDate, $endDate])
        ->where('type', '!=', 'income')
        ->selectRaw('concept, SUM(amount) as total')
        ->groupBy('concept')
        ->get();
    echo "4. Movements OK: " . count($movements) . "\n";
} catch (\Exception $e) {
    echo "4. CashMovement ERROR: " . $e->getMessage() . "\n";
}

// Test rendering view
echo "5. Testing view render...\n";
try {
    $html = view('admin/panel_control', [
        'sales_history' => collect([]),
        'clients_today_scheduled' => 0,
        'clients_today_bought' => 0,
        'average_ticket' => 0,
        'avg_products' => 0,
        'avg_services' => 0,
        'low_stock_count' => 0,
        'total_inventory' => 0,
        'top_products' => collect([]),
        'top_services' => [],
        'services_not_performed' => [],
        'top_specialists' => collect([]),
        'expenses' => [],
        'total_expenses' => 0,
        'all_specialists' => collect([]),
        'selected_specialist' => null,
        'recent_sales' => collect([]),
        'startDate' => $startDate,
        'endDate' => $endDate,
        'totalSalesPeriod' => 0,
        'salesVariation' => 0,
        'salesTodayBySpecialist' => collect([]),
    ])->render();
    echo "6. View render OK! (Length: " . strlen($html) . " bytes)\n";
} catch (\Exception $e) {
    echo "6. View ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "\nDONE!\n";
