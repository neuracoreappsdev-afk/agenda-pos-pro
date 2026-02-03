<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Package;
use App\Models\Specialist;
use App\Models\CashMovement;
use App\Models\SpecialistAdvance;
use App\Models\Customer;
use App\Models\ReferenceSource;
use App\Models\Appointment;
use App\Models\PurchaseInvoice;
use App\Models\InventoryLog;
use Carbon\Carbon;
use DB;

class ReportsController extends Controller
{
    private function checkSubscription()
    {
        $business_id = session('business_id', 1);
        $sub = \DB::table('saas_subscriptions')
            ->join('saas_plans', 'saas_subscriptions.plan_id', '=', 'saas_plans.id')
            ->where('saas_subscriptions.business_id', $business_id)
            ->select('saas_plans.price')
            ->first();
            
        return ($sub && $sub->price > 0);
    }

    private function renderLock($title)
    {
        return view('admin.subscription.lock', ['title' => $title]);
    }
    
    /**
     * Estado de Resultados
     */
    public function estadoResultados(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $startDT = $dateFrom . ' 00:00:00';
        $endDT = $dateTo . ' 23:59:59';

        // 1. INGRESOS
        $sales = Sale::whereBetween('sale_date', [$startDT, $endDT])->get();
        $totalSales = $sales->sum('total');
        $salesCount = $sales->count();
        $avgTicket = $salesCount > 0 ? $totalSales / $salesCount : 0;
        
        $serviceRevenue = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDT, $endDT])
            ->where('sale_items.item_type', 'servicio')
            ->sum('sale_items.total') ?: 0;

        $productRevenue = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDT, $endDT])
            ->where('sale_items.item_type', 'producto')
            ->sum('sale_items.total') ?: 0;

        // 2. GASTOS
        $expenses = CashMovement::where('type', 'egreso')
                        ->whereBetween('movement_date', [$dateFrom, $dateTo])
                        ->get();
        $totalExpenses = $expenses->sum('amount') ?: 0;
        $expensesCount = $expenses->count();
        
        // Comisiones (También son un gasto/egreso)
        $totalComisiones = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDT, $endDT])
            ->sum('sale_items.commission_value') ?: 0;

        // 3. UTILIDAD
        $totalCost = $totalExpenses + $totalComisiones;
        $netProfit = $totalSales - $totalCost;
        $margin = $totalSales > 0 ? ($netProfit / $totalSales) * 100 : 0;

        // Datos para gráfico o visualización simple
        $heightGastos = $totalSales > 0 ? min(100, ($totalCost / $totalSales) * 100) : 0;
        $heightUtilidad = $totalSales > 0 ? min(100, max(0, ($netProfit / $totalSales) * 100)) : 0;

        return view('admin/informes/estado_resultados', compact(
            'dateFrom', 'dateTo', 'totalSales', 'serviceRevenue', 'productRevenue',
            'expenses', 'totalExpenses', 'totalComisiones', 'netProfit', 'margin', 
            'sales', 'salesCount', 'avgTicket', 'expensesCount', 'totalCost',
            'heightGastos', 'heightUtilidad'
        ));
    }

    /**
     * Gastos
     */
    public function gastos(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        // Use more inclusive types like in movimientosCaja
        $expenses = CashMovement::whereIn('type', ['egreso', 'expense', 'gasto', 'withdrawal', 'salida'])
                        ->whereBetween('movement_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                        ->orderBy('movement_date', 'desc')
                        ->get();
        
        $totalExpenses = $expenses->sum('amount');
        $avgExpense = $expenses->count() > 0 ? $totalExpenses / $expenses->count() : 0;
        
        // Agrupar por categoría/concepto
        $expensesByCategory = $expenses->groupBy('concept')->map(function($group) {
            return $group->sum('amount');
        });

        return view('admin/informes/gastos', compact('dateFrom', 'dateTo', 'expenses', 'totalExpenses', 'avgExpense', 'expensesByCategory'));
    }

    /**
     * Informe de Rentabilidad de Servicios
     */
    public function rentabilidadServicios(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $startDT = $dateFrom . ' 00:00:00';
        $endDT = $dateTo . ' 23:59:59';

        $data = collect(DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDT, $endDT])
            ->where('sale_items.item_type', 'servicio')
            ->select(
                'sale_items.item_id',
                'sale_items.item_name',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('SUM(sale_items.commission_value) as total_commissions')
            )
            ->groupBy('sale_items.item_id', 'sale_items.item_name')
            ->orderBy('total_revenue', 'desc')
            ->get());

        foreach($data as $item) {
            $item->profit = $item->total_revenue - $item->total_commissions;
            $item->margin = $item->total_revenue > 0 ? ($item->profit / $item->total_revenue) * 100 : 0;
        }

        $totalRev = $data->sum('total_revenue') ?: 0;
        $totalComm = $data->sum('total_commissions') ?: 0;
        $netProfit = $totalRev - $totalComm;

        return view('admin/informes/rentabilidad_servicios', compact('dateFrom', 'dateTo', 'data', 'totalRev', 'totalComm', 'netProfit'));
    }

    /**
     * Informe de Rentabilidad de Productos
     */
    public function rentabilidadProductos(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $startDT = $dateFrom . ' 00:00:00';
        $endDT = $dateTo . ' 23:59:59';

        $data = collect(DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDT, $endDT])
            ->where('sale_items.item_type', 'producto')
            ->select(
                'sale_items.item_id',
                'sale_items.item_name',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(sale_items.quantity) as total_qty'),
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('SUM(sale_items.commission_value) as total_commissions')
            )
            ->groupBy('sale_items.item_id', 'sale_items.item_name')
            ->orderBy('total_revenue', 'desc')
            ->get());
            
        // Enriquecer con el costo actual del producto para estimar rentabilidad
        foreach($data as $item) {
            $product = Product::find($item->item_id);
            $item->unit_cost = $product ? $product->cost : 0;
            $item->total_cost = $item->total_qty * $item->unit_cost;
            $item->profit = $item->total_revenue - $item->total_cost - $item->total_commissions;
            $item->margin = $item->total_revenue > 0 ? ($item->profit / $item->total_revenue) * 100 : 0;
        }

        $totalRev = $data->sum('total_revenue') ?: 0;
        $totalCost = $data->sum('total_cost') ?: 0;
        $totalComm = $data->sum('total_commissions') ?: 0;
        $netProfit = $totalRev - $totalCost - $totalComm;

        return view('admin/informes/rentabilidad_productos', compact('dateFrom', 'dateTo', 'data', 'totalRev', 'totalCost', 'totalComm', 'netProfit'));
    }

    /**
     * Presupuesto VS Real
     */
    public function presupuestoVsReal(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));

        // Presupuesto base (esto debería venir de una tabla en una app madura)
        $presupuesto = [
            'ingresos' => 12000000,
            'gastos' => 7000000,
            'utilidad' => 5000000
        ];

        $startDate = "$year-$month-01 00:00:00";
        $endDate = Carbon::parse($startDate)->endOfMonth()->format('Y-m-d 23:59:59');

        $real_ingresos = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total');

        $real_gastos = CashMovement::where('type', 'egreso')
            ->whereBetween('movement_date', [substr($startDate, 0, 10), substr($endDate, 0, 10)])
            ->sum('amount');
        
        $real_comisiones = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate, $endDate])
            ->sum('sale_items.commission_value');
            
        $real_gastos += $real_comisiones;

        $comparativa = [
            'ingresos' => [
                'presupuesto' => $presupuesto['ingresos'],
                'real' => $real_ingresos,
                'variacion' => $presupuesto['ingresos'] > 0 ? round((($real_ingresos - $presupuesto['ingresos']) / $presupuesto['ingresos']) * 100, 1) : 0
            ],
            'gastos' => [
                'presupuesto' => $presupuesto['gastos'],
                'real' => $real_gastos,
                'variacion' => $presupuesto['gastos'] > 0 ? round((($real_gastos - $presupuesto['gastos']) / $presupuesto['gastos']) * 100, 1) : 0
            ],
            'utilidad' => [
                'presupuesto' => $presupuesto['utilidad'],
                'real' => $real_ingresos - $real_gastos,
                'variacion' => $presupuesto['utilidad'] > 0 ? round(((($real_ingresos - $real_gastos) - $presupuesto['utilidad']) / $presupuesto['utilidad']) * 100, 1) : 0
            ]
        ];

        return view('admin/informes/presupuesto_vs_real', compact('year', 'month', 'comparativa'));
    }

    /**
     * Códigos de Verificación
     */
    public function codigosVerificacion(Request $request)
    {
        if (!session('admin_session')) {
            return redirect('admin');
        }

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $verificaciones = Sale::with('customer')
            ->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('sale_date', 'desc')
            ->get()
            ->map(function($sale) {
                return [
                    'codigo' => 'VX-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                    'fecha' => $sale->sale_date,
                    'cliente' => $sale->customer ? ($sale->customer->first_name . ' ' . $sale->customer->last_name) : 'Consumidor Final',
                    'total' => $sale->total,
                    'metodo_pago' => $sale->payment_method,
                    'estado' => 'AUTORIZADO'
                ];
            });

        return view('admin/informes/codigos_verificacion', compact('dateFrom', 'dateTo', 'verificaciones'));
    }

    /**
     * Movimientos de Caja
     */
    public function movimientosCaja(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $movements = CashMovement::whereBetween('movement_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('id', 'desc')
            ->get();

        $totalIngresos = $movements->filter(function($m) {
            return in_array($m->type, ['income', 'ingreso', 'tip']);
        })->sum('amount');

        $totalEgresos = $movements->filter(function($m) {
            return in_array($m->type, ['expense', 'egreso', 'gasto']);
        })->sum('amount');

        $saldoNeto = $totalIngresos - $totalEgresos;

        $ingresosPorMetodo = $movements->filter(function($m) {
            return in_array($m->type, ['income', 'ingreso', 'tip']);
        })->groupBy('payment_method')->map(function($items) {
            return $items->sum('amount');
        });

        return view('admin/informes/movimientos_caja', compact('dateFrom', 'dateTo', 'movements', 'totalIngresos', 'totalEgresos', 'saldoNeto', 'ingresosPorMetodo'));
    }

    /**
     * Movimientos Cuentas Efectivo
     */
    public function movimientosCuentasEfectivo(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $movements = CashMovement::whereBetween('movement_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('id', 'desc')
            ->get();

        $totalIngresos = $movements->filter(function($m) {
            return in_array($m->type, ['income', 'ingreso', 'tip']);
        })->sum('amount');

        $totalEgresos = $movements->filter(function($m) {
            return in_array($m->type, ['expense', 'egreso', 'gasto']);
        })->sum('amount');

        $saldoNeto = $totalIngresos - $totalEgresos;

        $ingresosPorMetodo = $movements->filter(function($m) {
            return in_array($m->type, ['income', 'ingreso', 'tip']);
        })->groupBy('payment_method')->map(function($items) {
            return $items->sum('amount');
        });

        return view('admin/informes/movimientos_cuentas_efectivo', compact('dateFrom', 'dateTo', 'movements', 'totalIngresos', 'totalEgresos', 'saldoNeto', 'ingresosPorMetodo'));
    }

    /**
     * Códigos Verificación Pago
     */
    public function codigosVerificacionPago(Request $request)
    {
        return $this->codigosVerificacion($request);
    }

    /**
     * Porcentaje Franquicias
     */
    public function porcentajeFranquicias(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $sales = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('payment_method', 'Tarjeta')
            ->get();
        
        $totalSales = $sales->sum('total');
        
        return view('admin/informes/porcentaje_franquicias', compact('dateFrom', 'dateTo', 'sales', 'totalSales'));
    }

    /**
     * Informe de Ventas Generales
     */
    public function ventas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $specialistId = $request->input('specialist_id');
        
        $query = Sale::with(['customer', 'specialist'])
                    ->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
        
        if ($specialistId) {
            $query->where('specialist_id', $specialistId);
        }
        
        $sales = $query->orderBy('sale_date', 'desc')->get();
        
        $totalVentas = $sales->sum('total');
        $cantidadVentas = $sales->count();
        $promedioVenta = $cantidadVentas > 0 ? $totalVentas / $cantidadVentas : 0;
        
        $ventasPorMetodo = $sales->groupBy('payment_method')->map(function($group) {
            return $group->sum('total');
        });
        
        // Calculo real de comisiones
        $saleIds = $sales->pluck('id');
        $totalComisionesReal = DB::table('sale_items')->whereIn('sale_id', $saleIds)->sum('commission_value');
        
        $specialists = Specialist::orderBy('name')->get();

        return view('admin/informes/ventas', compact(
            'dateFrom', 'dateTo', 'sales', 'totalVentas', 'cantidadVentas', 
            'promedioVenta', 'ventasPorMetodo', 'specialists', 'specialistId', 'totalComisionesReal'
        ));
    }

    /**
     * Informe de Especialistas
     */
    public function especialistas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $selectedSpecialistId = $request->input('specialist_id');
        
        $specialistsQuery = Specialist::query();
        if ($selectedSpecialistId) {
            $specialistsQuery->where('id', $selectedSpecialistId);
        }
        $specialistsData = $specialistsQuery->orderBy('name')->get();
        
        // Use DB for complex grouping to avoid Eloquent/Collection issues in L5.1
        $salesBySpecialistArr = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'sale_items.specialist_id', 
                DB::raw('COUNT(DISTINCT sale_items.sale_id) as total_ventas'), 
                DB::raw('SUM(sale_items.total) as total_monto'),
                DB::raw('SUM(sale_items.commission_value) as total_comision')
            )
            ->groupBy('sale_items.specialist_id')
            ->get();

        $salesBySpecialist = collect($salesBySpecialistArr)->keyBy('specialist_id');
            
        $totalVentas = $salesBySpecialist->sum('total_monto');
        $allSpecialists = Specialist::orderBy('name')->get();

        return view('admin/informes/especialistas', compact(
            'dateFrom', 'dateTo', 'specialistsData', 'salesBySpecialist', 
            'totalVentas', 'allSpecialists', 'selectedSpecialistId'
        ));
    }

    /**
     * Informe de Clientes
     */
    public function clientes(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $customers = \App\Models\Customer::orderBy('first_name')->get();
        $totalClientes = $customers->count();
        
        $clientesNuevos = \App\Models\Customer::whereMonth('created_at', '=', date('m'))
            ->whereYear('created_at', '=', date('Y'))
            ->count();
            
        return view('admin/informes/clientes', compact('customers', 'totalClientes', 'clientesNuevos'));
    }

    /**
     * Informe de Comisiones (Lógica Corregida)
     */
    public function comisiones(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        if (!$this->checkSubscription()) return $this->renderLock('Comisiones y Liquidación');

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $specialists = Specialist::all();
        
        $commissionsBySpecialist = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->selectRaw('
                sale_items.specialist_id, 
                SUM(sale_items.total) as total_vendido,
                SUM(sale_items.commission_value) as total_comision
            ')
            ->groupBy('sale_items.specialist_id')
            ->get()->keyBy('specialist_id');
        
        $totalComisiones = $commissionsBySpecialist->sum('total_comision');
        
        return view('admin/informes/comisiones', compact('dateFrom', 'dateTo', 'specialists', 'commissionsBySpecialist', 'totalComisiones'));
    }

    /**
     * Informe de Servicios (Listado)
     */
    public function servicios(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $services = Package::where('active', 1)->orderBy('category')->get();
        $servicesByCategory = $services->groupBy('category');
        
        $totalServicios = $services->count();
        $precioPromedio = $services->avg('package_price') ?? 0;
        
        return view('admin/informes/servicios', compact('services', 'servicesByCategory', 'totalServicios', 'precioPromedio'));
    }

    /**
     * Informe de Productos Stock
     */
    public function productosStock(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $products = Product::orderBy('quantity')->get();
        $lowStock = $products->filter(function($p) { return $p->quantity <= $p->min_quantity; });
        $totalProductos = $products->count();
        $valorInventario = $products->sum(function($p) { return $p->quantity * $p->cost; });
        
        return view('admin/informes/productos_stock', compact('products', 'lowStock', 'totalProductos', 'valorInventario'));
    }
    /**
     * Fuentes de Referencia
     */
    /**
     * Fuentes de Referencia
     */
    public function fuentesReferencia(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-01-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Advanced Query: Count AND Revenue per Source
        $data = Customer::leftJoin('sales', 'customers.id', '=', 'sales.customer_id')
            ->whereBetween('customers.created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'customers.reference_source_id', 
                DB::raw('count(DISTINCT customers.id) as total_customers'),
                DB::raw('SUM(sales.total) as total_revenue')
            )
            ->groupBy('customers.reference_source_id')
            ->get();
            
        $sources = \App\Models\ReferenceSource::all()->pluck('name', 'id');
        $sources[0] = 'No asignado';
        
        $chartData = [];
        foreach($data as $row) {
            $name = $sources[$row->reference_source_id] ?? 'Otro';
            $chartData[] = [
                'name' => $name, 
                'y' => $row->total_customers,
                'revenue' => $row->total_revenue ?? 0
            ];
        }
        
        // Sort by Count Desc
        usort($chartData, function($a, $b) {
            return $b['y'] - $a['y'];
        });
        
        return view('admin/informes/fuentes_referencia', compact('dateFrom', 'dateTo', 'chartData'));
    }

    /**
     * Frecuencia de Clientes (Visitas)
     */
    public function frecuenciaClientes(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-d', strtotime('-3 months')));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $salesPerCustomer = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('customer_id', DB::raw('count(*) as count'))
            ->whereNotNull('customer_id')
            ->groupBy('customer_id')
            ->get();
            
        $distribution = [
            '1 visita' => 0,
            '2-3 visitas' => 0,
            '4+ visitas' => 0
        ];
        
        foreach($salesPerCustomer as $row) {
            if($row->count == 1) $distribution['1 visita']++;
            elseif($row->count <= 3) $distribution['2-3 visitas']++;
            else $distribution['4+ visitas']++;
        }
        
        return view('admin/informes/frecuencia_clientes', compact('dateFrom', 'dateTo', 'distribution'));
    }

    /**
     * Ventas por Asesor
     */
    /**
     * Ventas por Asesor (Client Centric)
     */
    public function ventasPorAsesor(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Initial Query
        $stats = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('specialists', 'sale_items.specialist_id', '=', 'specialists.id')
            ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'specialists.id as specialist_id',
                'specialists.name as specialist_name',
                DB::raw('COUNT(DISTINCT sales.id) as total_sales'),
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('COUNT(DISTINCT sales.customer_id) as unique_clients')
            )
            ->groupBy('specialists.id', 'specialists.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
            
        // Calculate Top Client and Retention for each specialist
        // This is expensive but necessary for "Perfect Logic"
        foreach($stats as $s) {
            // Top Client
            $topClient = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->join('customers', 'sales.customer_id', '=', 'customers.id')
                ->where('sale_items.specialist_id', $s->specialist_id)
                ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->select('customers.first_name', 'customers.last_name', DB::raw('SUM(sale_items.total) as total_spent'))
                ->groupBy('customers.id', 'customers.first_name', 'customers.last_name')
                ->orderBy('total_spent', 'desc')
                ->first();
                
            $s->top_client_name = $topClient ? $topClient->first_name . ' ' . $topClient->last_name : 'N/A';
            $s->top_client_amount = $topClient ? $topClient->total_spent : 0;
            
            // Retention (Clients with > 1 visit in period)
            // Fix: Retention logic is complex, approximating by looking at sales count per customer for this specialist
            $retainedCount = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sale_items.specialist_id', $s->specialist_id)
                ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->select('sales.customer_id')
                ->groupBy('sales.customer_id')
                ->havingRaw('COUNT(DISTINCT sales.id) > 1')
                ->get();
                
            $s->retained_clients = count($retainedCount);
            $s->retention_rate = $s->unique_clients > 0 ? ($s->retained_clients / $s->unique_clients) * 100 : 0;
        }
        
        return view('admin/informes/ventas_por_asesor', compact('dateFrom', 'dateTo', 'stats'));
    }

    /**
     * Fichas Técnicas
     */
    public function fichasTecnicas(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $search = $request->input('search');
        
        $query = Customer::orderBy('first_name');
        if($search) {
             $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('identification', 'like', "%$search%");
             });
        }
        $customers = $query->paginate(20);
        
        return view('admin/informes/fichas_tecnicas', compact('customers', 'search'));
    }

    /**
     * Retorno de Nuevos Clientes
     */
    public function retornoNuevosClientes(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $month = $request->input('month', date('m', strtotime('-1 month')));
        $year = $request->input('year', date('Y'));
        
        $newCustomers = Customer::whereMonth('created_at', '=', $month)
            ->whereYear('created_at', '=', $year)
            ->lists('id');
            
        $totalNew = count($newCustomers);
        $returned = 0;
        
        if($totalNew > 0) {
            $returnedRows = Sale::whereIn('customer_id', $newCustomers)
                ->select('customer_id', DB::raw('COUNT(*) as total_sales'))
                ->groupBy('customer_id')
                ->havingRaw('COUNT(*) > 1')
                ->get();
            $returned = count($returnedRows);
        }
        
        return view('admin/informes/retorno_nuevos_clientes', compact('month', 'year', 'totalNew', 'returned'));
    }

    /**
     * Puntos de Fidelidad (Con cálculo real)
     */
    public function puntos(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $customers = Customer::select('customers.*', 
            DB::raw('(SELECT SUM(points) FROM loyalty_points WHERE customer_id = customers.id) as current_points'),
            DB::raw('(SELECT SUM(points) FROM loyalty_points WHERE customer_id = customers.id AND points > 0) as total_earned'),
            DB::raw('(SELECT ABS(SUM(points)) FROM loyalty_points WHERE customer_id = customers.id AND points < 0) as total_redeemed')
        )
        ->orderBy('current_points', 'desc')
        ->paginate(20);
        
        return view('admin/informes/puntos', compact('customers'));
    }

    /**
     * Ventas Detalladas por Servicio
     */
    public function ventasPorServicio(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-01-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $data = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sale_items.item_type', 'package')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'sale_items.item_name',
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('AVG(sale_items.total) as avg_price')
            )
            ->groupBy('sale_items.item_name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return view('admin/informes/ventas_por_servicio', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Exportar Facturas / Ventas
     */
    public function exportarVentas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $sales = Sale::with('customer')
            ->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('sale_date', 'desc')
            ->get();

        return view('admin/informes/exportar_facturas', compact('dateFrom', 'dateTo', 'sales'));
    }

    /**
     * Exportar Compras
     */
    public function exportarCompras(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $purchases = PurchaseInvoice::whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->orderBy('invoice_date', 'desc')
            ->get();

        return view('admin/informes/exportar_compras', compact('dateFrom', 'dateTo', 'purchases'));
    }


    /**
     * Listado de Planes de Suscripción
     */
    public function listadoPlanes(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $plans = \App\Models\CommercialPlan::paginate(15);
        return view('admin/ventas/planes', compact('plans'));
    }

    public function logUsuarios(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $logs = \App\Models\AuditLog::orderBy('created_at', 'desc')->paginate(50);
        return view('admin/informes/log_usuarios', compact('logs'));
    }

    /**
     * Analítica de Chat
     */
    public function analiticaChat(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $totalMessages = \App\Models\Message::count();
        $unreadMessages = \App\Models\Message::where('is_read', 0)->count();
        $recentMessages = \App\Models\Message::orderBy('created_at', 'desc')->take(10)->get();
        
        return view('admin/informes/analitica_chat', compact('totalMessages', 'unreadMessages', 'recentMessages'));
    }

    /**
     * Trazabilidad de Clientes
     */
    public function trazabilidadClientes(Request $request) {
        if (!session('admin_session')) return redirect('admin');
        
        $customerId = $request->input('customer_id');
        $customer = null;
        $history = [];
        
        if($customerId) {
            $customer = Customer::find($customerId);
            if($customer) {
                $sales = Sale::where('customer_id', $customerId)->orderBy('sale_date', 'desc')->get();
                $appointments = \App\Models\Appointment::where('customer_id', $customerId)->orderBy('appointment_datetime', 'desc')->get();
                
                // Combine into history timeline
                foreach($sales as $s) {
                    $history[] = [
                        'type' => 'sale',
                        'date' => $s->sale_date,
                        'details' => 'Venta #' . $s->id . ' - Total: $' . number_format($s->total),
                        'item' => $s
                    ];
                }
                foreach($appointments as $a) {
                    $history[] = [
                        'type' => 'appointment',
                        'date' => $a->appointment_datetime,
                        'details' => 'Cita: ' . ($a->package ? $a->package->package_name : 'Servicio'),
                        'item' => $a
                    ];
                }
                
                // Sort by date desc
                usort($history, function($a, $b) {
                    return strtotime($b['date']) - strtotime($a['date']);
                });
            }
        }
        
        $allCustomers = [];
        if(!$customer) {
             $allCustomers = Customer::orderBy('first_name')->select('id', 'first_name', 'last_name')->get();
        }
        
        return view('admin/informes/trazabilidad_clientes', compact('customer', 'history', 'allCustomers'));
    }

    /**
     * Comisión Detallada por Item
     */
    public function comisionDetallada(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        if (!$this->checkSubscription()) return $this->renderLock('Comisión Detallada');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $items = SaleItem::with(['sale', 'specialist'])
            ->whereHas('sale', function($q) use ($dateFrom, $dateTo) {
                $q->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);
            })
            ->where('commission_value', '>', 0)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin/informes/comision_detallada', compact('dateFrom', 'dateTo', 'items'));
    }

    /**
     * Ventas Especialistas / Clientes
     */
    public function ventasEspecialistaCliente(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        if (!$this->checkSubscription()) return $this->renderLock('Ventas por Especialista/Cliente');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('specialists', 'sale_items.specialist_id', '=', 'specialists.id')
            ->leftJoin('customers', 'sales.customer_id', '=', 'customers.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'specialists.name as specialist_name',
                'customers.first_name', 'customers.last_name',
                'sales.sale_date',
                'sale_items.item_name',
                'sale_items.total',
                'sale_items.commission_value'
            )
            ->orderBy('sales.sale_date', 'desc')
            ->get();
            
        return view('admin/informes/ventas_especialista_cliente', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Planilla de Pago
     */
    public function planillaPago(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        if (!$this->checkSubscription()) return $this->renderLock('Planilla de Pago (Nómina)');

        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $specialists = Specialist::orderBy('name')->get();
        $planilla = [];
        
        foreach($specialists as $s) {
            $commStats = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sale_items.specialist_id', (int)$s->id)
                ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->select(DB::raw('SUM(commission_value) as total_comm'))
                ->first();
                
            $commissions = $commStats ? (float)$commStats->total_comm : 0;
                
            $advances = (float)DB::table('specialist_advances')
                ->where('specialist_id', (int)$s->id)
                ->whereBetween('date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->sum('amount');
                
            $planilla[] = [
                'name' => $s->name,
                'commissions' => $commissions,
                'advances' => $advances,
                'total' => $commissions - $advances
            ];
        }
        
        return view('admin/informes/planilla_pago', compact('dateFrom', 'dateTo', 'planilla'));
    }

    /**
     * Novedades y Deducciones
     */
    public function novedades(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Use direct DB for maximum compatibility
        $novedades = DB::table('specialist_advances')
            ->join('specialists', 'specialist_advances.specialist_id', '=', 'specialists.id')
            ->whereBetween('specialist_advances.date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('specialist_advances.*', 'specialists.name as specialist_name')
            ->orderBy('specialist_advances.date', 'desc')
            ->get();
            
        return view('admin/informes/novedades', compact('dateFrom', 'dateTo', 'novedades'));
    }

    /**
     * Comisiones de Pago Dividido
     */
    public function comisionesPagoDividido(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Multi-specialist sales (if applicable)
        $data = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('specialists', 'sale_items.specialist_id', '=', 'specialists.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'sales.id as sale_id',
                'specialists.name as specialist_name',
                'sale_items.item_name',
                'sale_items.commission_value'
            )
            ->get()
            ->groupBy('sale_id');
            
        return view('admin/informes/comisiones_pago_dividido', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Formato Liquidación Estilistas
     */
    public function liquidacionEstilistas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $specialists = Specialist::all();
        $selectedId = $request->input('specialist_id');
        $specialist = $selectedId ? Specialist::find($selectedId) : null;
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $stats = null;
        if($specialist) {
             $stats = DB::table('sale_items')
                ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
                ->where('sale_items.specialist_id', $specialist->id)
                ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->selectRaw('SUM(total) as gross, SUM(commission_value) as commission')
                ->first();
        }
        
        return view('admin/informes/liquidacion_estilistas', compact('specialists', 'specialist', 'stats', 'dateFrom', 'dateTo'));
    }

    /**
     * Reporte de Novedades (Deducciones, Adelantos, Otros)
     */
    public function reporteNovedades(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $novedades = DB::table('specialist_advances')
            ->join('specialists', 'specialist_advances.specialist_id', '=', 'specialists.id')
            ->whereBetween('specialist_advances.date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('specialist_advances.*', 'specialists.name as specialist_name')
            ->orderBy('specialist_advances.date', 'desc')
            ->get();
            
        return view('admin/informes/reporte_novedades', compact('dateFrom', 'dateTo', 'novedades'));
    }

    /**
     * Bloqueos de Agenda
     */
    public function bloqueosAgenda(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $locks = collect(DB::table('booking_locks')
            ->join('specialists', 'booking_locks.specialist_id', '=', 'specialists.id')
            ->whereBetween('booking_locks.datetime', [$dateFrom . ' 00:00', $dateTo . ' 23:59'])
            ->select('booking_locks.*', 'specialists.name as specialist_name')
            ->get());
            
        return view('admin/informes/bloqueos_agenda', compact('dateFrom', 'dateTo', 'locks'));
    }

    /**
     * Ventas e Impuestos
     */
    public function ventasImpuestos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $sales = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'sales.id', 'sales.sale_date', 
                DB::raw('SUM(sale_items.total) as total'), 
                DB::raw('SUM(sale_items.tax_amount) as total_tax'),
                DB::raw('SUM(sale_items.total - sale_items.tax_amount) as subtotal')
            )
            ->groupBy('sales.id', 'sales.sale_date')
            ->get();
            
        return view('admin/informes/ventas_facturacion_impuestos', compact('dateFrom', 'dateTo', 'sales'));
    }

    /**
     * Ventas por Sede
     */
    public function ventasSede(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Asumiendo que las ventas tienen una sede o que se puede derivar de los especialistas
        $data = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('specialists', 'sale_items.specialist_id', '=', 'specialists.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'specialists.location as sede', 
                DB::raw('COUNT(DISTINCT sales.id) as transacciones'),
                DB::raw('SUM(sale_items.total) as total')
            )
            ->groupBy('specialists.location')
            ->get();
            
        return view('admin/informes/ventas_por_sede', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas por Cliente
     */
    public function ventasCliente(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = Sale::with('customer')
            ->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'customer_id', 
                DB::raw('COUNT(*) as total_compras'),
                DB::raw('SUM(total) as total_invertido')
            )
            ->groupBy('customer_id')
            ->orderBy('total_invertido', 'desc')
            ->take(50)
            ->get();
            
        return view('admin/informes/ventas_por_cliente', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas Prod vs Serv
     */
    public function ventasProdServ(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'sale_items.item_type', 
                DB::raw('COUNT(*) as cantidad'),
                DB::raw('SUM(sale_items.total) as total')
            )
            ->groupBy('sale_items.item_type')
            ->get();
            
        return view('admin/informes/ventas_productos_servicios', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas por Día
     */
    public function ventasDia(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-d', strtotime('-15 days')));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $is_sqlite = (DB::connection()->getDriverName() == 'sqlite');
        $dateFunc = $is_sqlite ? "date(sale_date)" : "DATE(sale_date)";

        $data = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                DB::raw($dateFunc . ' as day'), 
                DB::raw('COUNT(*) as transacciones'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('day')
            ->orderBy('day', 'desc')
            ->get();
            
        return view('admin/informes/ventas_por_dia', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas por Mes
     */
    public function ventasMes(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $year = $request->input('year', date('Y'));
        
        $is_sqlite = (DB::connection()->getDriverName() == 'sqlite');
        $monthFunc = $is_sqlite ? "strftime('%Y-%m', sale_date)" : "DATE_FORMAT(sale_date, '%Y-%m')";

        $data = Sale::whereYear('sale_date', '=', $year)
            ->select(
                DB::raw($monthFunc . ' as month'), 
                DB::raw('COUNT(*) as transacciones'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
            
        return view('admin/informes/ventas_por_mes', compact('year', 'data'));
    }

    /**
     * Ventas por Vendedor (Admin User who took the sale)
     */
    public function ventasVendedor(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = DB::table('sales')
            ->leftJoin('admin', 'sales.user_id', '=', 'admin.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'admin.username as vendedor', 
                DB::raw('COUNT(sales.id) as transacciones'),
                DB::raw('SUM(sales.total) as total')
            )
            ->groupBy('admin.username')
            ->get();
            
        return view('admin/informes/ventas_por_vendedor', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas por Medio de Pago
     */
    public function ventasMedioPago(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'payment_method', 
                DB::raw('COUNT(*) as transacciones'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();
            
        return view('admin/informes/ventas_medios_pago', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas por Tipo de Facturación
     */
    public function ventasTipoFacturacion(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Mocking logic or using a field if exists. 
        // We'll use a generic categorization for now.
        $data = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                DB::raw("CASE WHEN invoice_number IS NOT NULL THEN 'Factura Electrónica' ELSE 'Recibo POS' END as tipo"),
                DB::raw('COUNT(*) as transacciones'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('tipo')
            ->get();
            
        return view('admin/informes/ventas_tipo_facturacion', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Ventas Mandato Detallado (Agregadores / Mandatarios)
     */
    public function ventasMandato(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Items that are products usually act as mandate in many setups
        $data = SaleItem::with('sale')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where('sale_items.item_type', 'producto')
            ->select('sale_items.*')
            ->get();
            
        return view('admin/informes/ventas_mandato_detallado', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Reporte Auto Renta (Fiscal)
     */
    public function reporteAutoRenta(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-01-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        // Simulación de cálculo de retenciones basadas en ventas
        $sales = Sale::whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->get();
        $totalBase = $sales->sum('subtotal');
        $retencionAuto = $totalBase * 0.004; // 0.4% Ejemplo
        
        return view('admin/informes/reporte_auto_renta', compact('dateFrom', 'dateTo', 'totalBase', 'retencionAuto'));
    }

    /**
     * Exportar Recibos (Caja)
     */
    public function exportarRecibos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $movements = CashMovement::whereBetween('created_at', [$dateFrom, $dateTo])
            ->where('type', 'income')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin/informes/exportar_recibos', compact('dateFrom', 'dateTo', 'movements'));
    }

    /**
     * Facturación de Comisiones (Resumen para Cobro)
     */
    public function facturacionComisiones(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $name = $request->input('name');
        
        $query = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        if ($name) {
            $query->whereHas('specialist', function($sq) use ($name) {
                $sq->where('name', 'LIKE', "%$name%");
            });
        }

        $stats = $query->select(
                'sale_items.specialist_id',
                DB::raw('SUM(commission_value) as total_commission'),
                DB::raw('SUM(sale_items.total) as total_sales')
            )
            ->groupBy('sale_items.specialist_id')
            ->get();
            
        $specialists = Specialist::whereIn('id', $stats->pluck('specialist_id'))->get()->keyBy('id');
            
        return view('admin/informes/facturacion_comisiones', compact('dateFrom', 'dateTo', 'stats', 'specialists', 'name'));
    }

    /**
     * Respuestas de Encuestas (Placeholder)
     */
    public function respuestasEncuestas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $surveys = \App\Models\Survey::all();
        $responses = \App\Models\SurveyResponse::with(['customer', 'survey'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin/informes/respuestas_encuestas', compact('surveys', 'responses'));
    }

    /**
     * Saldos de Inventario (Hoy / Fecha)
     */
    public function saldosInventario(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $date = $request->input('date', date('Y-m-d'));
        
        $products = Product::orderBy('name')->get();
        return view('admin/informes/saldos_inventario', compact('products', 'date'));
    }

    /**
     * Movimientos de Inventario (Kardex)
     */
    public function movimientosInventario(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $logs = \App\Models\InventoryLog::with('product')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin/informes/movimientos_inventario', compact('dateFrom', 'dateTo', 'logs'));
    }

    /**
     * Reporte de Agenda (Reservas)
     */
    public function reporteAgenda(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-d'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $appointments = Appointment::with(['customer', 'specialist', 'package'])
            ->whereBetween('booking_date', [$dateFrom, $dateTo])
            ->orderBy('booking_time')
            ->get();
            
        return view('admin/informes/reporte_agenda', compact('dateFrom', 'dateTo', 'appointments'));
    }

    /*
    |--------------------------------------------------------------------------
    | Sales Submodule Methods
    |--------------------------------------------------------------------------
    */
    public function listadoBonos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        $bonos = \App\Models\Bono::with('customer')->orderBy('created_at', 'desc')->get();
        
        return view('admin/informes/listado_bonos', compact('bonos'));
    }

    public function storeBono(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        // Basic validation
        /* $this->validate($request, [
            'amount' => 'required|numeric|min:1',
            'expiry_date' => 'required|date',
            'recipient_name' => 'required'
        ]); */

        $code = strtoupper(uniqid('BONO-'));
        
        \App\Models\Bono::create([
            'code' => $code,
            'buyer_name' => $request->input('buyer_name'),
            'recipient_name' => $request->input('recipient_name'),
            'recipient_email' => $request->input('recipient_email'),
            'amount' => $request->input('amount'),
            'balance' => $request->input('amount'),
            'expiry_date' => $request->input('expiry_date'),
            'status' => 'active',
            'message' => $request->input('message')
        ]);

        return redirect('admin/ventas/bonos')->with('success', 'Bono creado exitosamente. Código: ' . $code);
    }


    public function devoluciones(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $refunds = \App\Models\Refund::orderBy('created_at', 'desc')->get();
        
        $monthStart = date('Y-m-01');
        $salesTotal = \App\Models\Sale::sum('total') ?: 1;
        $refundTotal = \App\Models\Refund::where('status', 'approved')
                         ->where('created_at', '>=', $monthStart)
                         ->sum('amount') ?: 0;

        $topReasonResult = \App\Models\Refund::select('reason', DB::raw('count(*) as total'))
                            ->groupBy('reason')
                            ->orderBy('total', 'desc')
                            ->first();

        $stats = [
            'total_refunded' => \App\Models\Refund::sum('amount'),
            'count_month' => \App\Models\Refund::where('created_at', '>=', $monthStart)->count(),
            'amount_month' => $refundTotal,
            'rate' => round(($refundTotal / $salesTotal) * 100, 1),
            'top_reason' => $topReasonResult ? $topReasonResult->reason : 'N/A'
        ];
        
        return view('admin/informes/devoluciones', compact('refunds', 'stats'));
    }

    /**
     * Reporte de Mensajes de Anuncios (Ads)
     */
    public function mensajesAds(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $campaigns = collect(DB::table('marketing_campaigns')->orderBy('created_at', 'desc')->get());
        $messages = collect(DB::table('marketing_messages')->orderBy('created_at', 'desc')->get());
        
        return view('admin/informes/mensajes_ads', compact('campaigns', 'messages'));
    }

    public function storeDevolucion(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');

        \App\Models\Refund::create([
            'sale_id' => $request->input('sale_id'), // Optional ID manual
            'amount' => $request->input('amount'),
            'reason' => $request->input('reason'),
            'status' => 'approved', // Auto-approve for manual entries
            'user_id' => session('admin_id') // Assuming ID is stored
        ]);

        return redirect('admin/ventas/devoluciones')->with('success', 'Devolución registrada correctamente');
    }



    /**
     * Listado de Pagos de Créditos (Basado en Ventas con pago diferido)
     */
    public function listadoPagosCreditos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        $name = $request->input('name');
        
        $query = Sale::where('payment_method', 'Crédito')
            ->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        if ($name) {
            $query->where(function($q) use ($name) {
                $q->where('customer_name', 'LIKE', "%$name%")
                  ->orWhereHas('customer', function($sq) use ($name) {
                      $sq->where('name', 'LIKE', "%$name%");
                  });
            });
        }

        $sales = $query->orderBy('sale_date', 'desc')->get();
            
        return view('admin/informes/listado_pagos_creditos', compact('dateFrom', 'dateTo', 'sales', 'name'));
    }

    /**
     * Impuesto Ventas Productos
     */
    public function impuestosVentasProductos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sale_items.item_type', 'producto')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'sale_items.item_name',
                DB::raw('SUM(sale_items.total - sale_items.tax_amount) as base'),
                DB::raw('SUM(sale_items.tax_amount) as tax'),
                DB::raw('SUM(sale_items.total) as total')
            )
            ->groupBy('sale_items.item_name')
            ->get();
            
        return view('admin/informes/impuestos_ventas_productos', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Impuesto Compras Productos
     */
    public function impuestosComprasProductos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = PurchaseInvoice::whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->select(
                DB::raw('SUM(subtotal) as base'),
                DB::raw('SUM(tax) as tax'),
                DB::raw('SUM(total) as total')
            )
            ->first();
            
        $invoices = PurchaseInvoice::with('provider')
            ->whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->get();
            
        return view('admin/informes/impuestos_compras_productos', compact('dateFrom', 'dateTo', 'data', 'invoices'));
    }



    /**
     * Historial de Pedidos (Compras)
     */
    public function historialPedidos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $invoices = PurchaseInvoice::with('provider')->orderBy('invoice_date', 'desc')->paginate(20);
        return view('admin/informes/historial_pedidos', compact('invoices'));
    }

    /**
     * Log de Facturas (Auditoría de Ventas)
     */
    public function logFacturas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $sales = Sale::with('user')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin/informes/log_facturas', compact('sales'));
    }

    /**
     * Comparativa de Sedes
     */
    public function comparativaSedes(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));
        
        $data = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('specialists', 'sale_items.specialist_id', '=', 'specialists.id')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'specialists.location as sede', 
                DB::raw('SUM(sale_items.total) as ventas_actual'),
                DB::raw('COUNT(DISTINCT sales.id) as transacciones')
            )
            ->groupBy('specialists.location')
            ->get();
            
        return view('admin/informes/ventas_comparativas_sedes', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Generic Placeholder for unimplemented reports
     */
    public function placeholder(Request $request, $view = 'Reporte')
    {
        if (!session('admin_session')) return redirect('admin');
        
        $pageTitle = str_replace('-', ' ', ucwords($view, '-'));
        return view('admin/informes/placeholder', compact('pageTitle'));
    }

    /**
     * Ventas Productos + Consumo
     */
    public function ventasProductosConsumo(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        // Ventas
        $sales = SaleItem::with('sale')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sale_items.item_type', 'producto')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('sale_items.*')
            ->get();

        // Consumo (Inventory logs)
        $consumo = InventoryLog::with('product')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where(function($q) {
                $q->where('reason', 'like', '%consumo%')
                  ->orWhere('reason', 'like', '%interno%');
            })
            ->get();

        return view('admin/informes/ventas_productos_consumo', compact('dateFrom', 'dateTo', 'sales', 'consumo'));
    }

    /**
     * Consumo Interno
     */
    public function ventasProductosConsumoSolo(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        $consumo = InventoryLog::with('product')
            ->whereBetween('created_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->where(function($q) {
                $q->where('reason', 'like', '%consumo%')
                  ->orWhere('reason', 'like', '%interno%');
            })
            ->get();

        return view('admin/informes/ventas_productos_consumo_solo', compact('dateFrom', 'dateTo', 'consumo'));
    }

    /**
     * Rotación de Productos
     */
    public function rotacionProductos(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        $dateFrom = $request->input('date_from', date('Y-m-01'));
        $dateTo = $request->input('date_to', date('Y-m-d'));

        // Sales per product in the period
        $sales = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sale_items.item_type', 'producto')
            ->whereBetween('sales.sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select('sale_items.item_id', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->groupBy('sale_items.item_id')
            ->get();

        $salesMap = collect($sales)->keyBy('item_id');

        $products = Product::where('active', 1)->get();
        $data = [];

        foreach ($products as $p) {
            $sold = isset($salesMap[$p->id]) ? (float)$salesMap[$p->id]->total_sold : 0;
            $stock = (float)$p->quantity;
            $totalUnits = $sold + $stock; // Approximation of starting stock
            
            $rotationIndex = $totalUnits > 0 ? ($sold / $totalUnits) * 100 : 0;
            
            $data[] = (object)[
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku,
                'stock' => $stock,
                'sold' => $sold,
                'rotation' => $rotationIndex,
                'cost' => $p->cost
            ];
        }

        // Sort by rotation descending
        usort($data, function($a, $b) {
            return $b->rotation - $a->rotation;
        });

        return view('admin/informes/rotacion_productos', compact('dateFrom', 'dateTo', 'data'));
    }

    /**
     * Conversión de Medidas
     */
    public function conversionMedidas(Request $request)
    {
        if (!session('admin_session')) return redirect('admin');
        
        $products = Product::where('active', 1)->get();
        
        // Enhance products with calculated yields for some categories
        foreach($products as $p) {
            // Mocking some common logic for the report's purpose
            $p->volume_total = 0;
            $p->unit_name = 'Unidades';
            
            if(strpos(strtolower($p->name), 'shampoo') !== false || strpos(strtolower($p->name), 'tinte') !== false) {
                $p->volume_total = 500; // ml
                $p->unit_measure = 'ml';
                $p->service_usage = 15; // ml per service
            } elseif(strpos(strtolower($p->name), 'laca') !== false) {
                $p->volume_total = 300; // ml
                $p->unit_measure = 'ml';
                $p->service_usage = 10;
            } else {
                $p->volume_total = 1;
                $p->unit_measure = 'und';
                $p->service_usage = 1;
            }
            
            $p->yield = $p->service_usage > 0 ? floor($p->volume_total / $p->service_usage) : 0;
            $p->cost_per_service = $p->yield > 0 ? $p->cost / $p->yield : $p->cost;
        }

        return view('admin/informes/conversion_medidas', compact('products'));
    }
}
