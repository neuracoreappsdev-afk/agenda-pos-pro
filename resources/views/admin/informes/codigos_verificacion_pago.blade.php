@extends('admin/dashboard_layout')

@section('content')

<?php
    try {
        $dateFrom = \Request::input('date_from', date('Y-m-01'));
        $dateTo = \Request::input('date_to', date('Y-m-d'));

        $sales = \App\Models\Sale::with('customer')
            ->whereBetween('sale_date', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('sale_date', 'desc')
            ->get();

        $verificaciones = $sales->map(function($sale) {
            return [
                'codigo' => 'VX-' . str_pad($sale->id, 6, '0', STR_PAD_LEFT),
                'fecha' => $sale->sale_date,
                'cliente' => $sale->customer ? ($sale->customer->first_name . ' ' . $sale->customer->last_name) : 'Consumidor Final',
                'total' => $sale->total,
                'metodo_pago' => $sale->payment_method,
                'estado' => 'AUTORIZADO'
            ];
        });
    } catch (\Exception $e) {
        $verificaciones = collect([]);
    }
?>
<div class="p-8 bg-[#fafafb] min-h-screen text-[#1a1a1e] font-sans">
    <!-- Header Premium Minimalista -->
    <div class="flex flex-wrap items-center justify-between mb-12 gap-6">
        <div>
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mb-2 block">Seguridad & Auditoría</span>
            <h1 class="text-4xl font-extrabold tracking-tight text-[#1a1a1e]">Códigos de Verificación</h1>
        </div>
        
        <form method="GET" class="flex items-center gap-2 bg-white p-2 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-[#f0f0f2]">
            <div class="flex items-center gap-4 px-4 text-sm font-bold">
                <input type="date" name="date_from" value="{{ $dateFrom }}" class="bg-transparent border-none focus:ring-0 p-0 w-32">
                <span class="text-gray-300">/</span>
                <input type="date" name="date_to" value="{{ $dateTo }}" class="bg-transparent border-none focus:ring-0 p-0 w-32">
            </div>
            <button type="submit" class="bg-[#1a1a1e] hover:bg-black text-white px-8 py-3 rounded-xl text-xs font-black uppercase transition-all shadow-lg shadow-black/5 active:scale-95 tracking-widest">
                Sincronizar
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-[#f0f0f2] shadow-[0_20px_50px_rgba(0,0,0,0.02)] overflow-hidden">
        <div class="p-10 border-b border-[#fafafa] flex justify-between items-center">
            <h2 class="text-sm font-black text-[#1a1a1e] uppercase tracking-[0.2em]">Registro de Transacciones</h2>
            <div class="flex items-center gap-3">
                <span class="text-[9px] font-black uppercase tracking-widest text-gray-300">Total Validaciones:</span>
                <span class="bg-[#1a1a1e] text-white px-3 py-1 rounded-lg text-[10px] font-black">{{ count($verificaciones) }}</span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-[#fafafb] text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">
                    <tr>
                        <th class="px-10 py-6">Auth Code</th>
                        <th class="px-10 py-6">Timestamp</th>
                        <th class="px-10 py-6">Subject / Client</th>
                        <th class="px-10 py-6">Method</th>
                        <th class="px-10 py-6 text-right">Volume</th>
                        <th class="px-10 py-6 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#fafafa] text-[13px] font-medium">
                    @forelse($verificaciones as $ver)
                    <tr class="hover:bg-[#fafafb] transition-all duration-300 group">
                        <td class="px-10 py-6">
                            <span class="font-mono font-black text-[#1a1a1e] bg-[#f8f9fa] border border-[#f0f0f2] px-3 py-1.5 rounded-lg text-xs">
                                {{ $ver['codigo'] }}
                            </span>
                        </td>
                        <td class="px-10 py-6">
                            <div class="text-[#1a1a1e] font-bold">{{ date('d M, Y', strtotime($ver['fecha'])) }}</div>
                            <div class="text-[10px] text-gray-300 font-bold uppercase tracking-tighter">{{ date('H:i:s', strtotime($ver['fecha'])) }}</div>
                        </td>
                        <td class="px-10 py-6">
                            <div class="font-bold text-[#1a1a1e]">{{ $ver['cliente'] }}</div>
                            <div class="text-[9px] text-gray-300 font-black uppercase tracking-widest mt-1">Ref ID: #{{ substr(md5($ver['codigo']), 0, 6) }}</div>
                        </td>
                        <td class="px-10 py-6">
                            <span class="text-gray-400 font-bold text-[11px] uppercase tracking-widest flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                {{ $ver['metodo_pago'] }}
                            </span>
                        </td>
                        <td class="px-10 py-6 text-right font-black text-[#1a1a1e] text-sm">
                            $ {{ number_format($ver['total']) }}
                        </td>
                        <td class="px-10 py-6 text-center">
                            <span class="inline-flex px-4 py-1.5 text-[9px] font-black rounded-full bg-emerald-500/10 text-emerald-600 uppercase tracking-widest border border-emerald-500/10">
                                {{ $ver['estado'] }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-10 py-32 text-center">
                            <div class="text-4xl mb-6 opacity-5 font-light">∅</div>
                            <p class="text-[10px] text-gray-300 font-black uppercase tracking-[0.3em]">No security logs for this timeframe</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
