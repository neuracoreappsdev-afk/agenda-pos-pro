@extends('admin.dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1500px] mx-auto" style="font-family: 'Outfit', sans-serif;">
    <!-- QuickBooks / Xero Style Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-emerald-600 text-white rounded-lg">📊</span>
                Centro de Auditoría & Finanzas
            </h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Estado de resultados y concilación bancaria en tiempo real</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl font-bold text-sm text-slate-700 shadow-sm hover:bg-slate-50 transition-all">Consiliar Bancos</button>
            <button class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">+ Nueva Transacción</button>
        </div>
    </div>

    <!-- Main Financial Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Caja & Bancos</span>
            <div class="text-3xl font-black text-slate-800">$ 45.8M</div>
            <div class="text-[9px] font-bold text-emerald-600 mt-2 uppercase">Sincronizado hoy 08:00 AM</div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Por Cobrar (A/R)</span>
            <div class="text-3xl font-black text-amber-500">$ 12.4M</div>
            <div class="text-[9px] font-bold text-slate-400 mt-2 uppercase">8 facturas vencidas</div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Por Pagar (A/P)</span>
            <div class="text-3xl font-black text-rose-500">$ 5.2M</div>
            <div class="text-[9px] font-bold text-slate-400 mt-2 uppercase">Próximo vencimiento: Mañana</div>
        </div>
        <div class="bg-slate-900 p-6 rounded-[32px] text-white shadow-2xl">
            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block mb-1 italic">Proyectado Mensual</span>
            <div class="text-3xl font-black">$ 72.0M</div>
            <div class="mt-2 flex items-center gap-2">
                <div class="h-1 flex-1 bg-white/10 rounded-full overflow-hidden">
                    <div class="bg-emerald-500 h-full w-[65%]"></div>
                </div>
                <span class="text-[9px] font-black">65%</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Cash Flow Visualization (Xero Style Mock) -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="flex justify-between items-center mb-10">
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-[4px]">Gráfico de Flujo de Caja</h2>
                    <select class="text-xs font-bold text-slate-500 border-none bg-slate-50 rounded-lg px-3 py-1 focus:ring-0">
                        <option>Últimos 12 meses</option>
                        <option>Este año</option>
                    </select>
                </div>
                
                <!-- Mock Chart Visualization -->
                <div class="h-64 w-full flex items-end gap-4 px-4 border-b border-slate-100 mb-6">
                    @for($i=1; $i<=12; $i++)
                        @php $h = rand(30, 90); @endphp
                        <div class="flex-1 bg-emerald-500/10 rounded-t-xl hover:bg-emerald-500/30 transition-all cursor-pointer relative group" style="height: {{ $h }}%;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[9px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity">
                                ${{ rand(5, 15) }}M
                            </div>
                            <div class="absolute bottom-[-24px] left-1/2 -translate-x-1/2 text-[9px] font-bold text-slate-300 uppercase">
                                {{ ['E','F','M','A','M','J','J','A','S','O','N','D'][$i-1] }}
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="mt-12 p-6 bg-slate-50 rounded-[32px] border border-slate-100 flex justify-between items-center group cursor-pointer">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm">🤖</div>
                        <div>
                            <h4 class="text-sm font-bold text-slate-800">Analista de Riesgo Fiscal</h4>
                            <p class="text-[10px] text-slate-500">Detecté 2 discrepancias menores en los gastos de representación de este mes.</p>
                        </div>
                    </div>
                    <button class="bg-slate-900 text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all">Auditar Ahora</button>
                </div>
            </div>

            <!-- Transaction Table (QuickBooks Style) -->
            <div class="bg-white rounded-[32px] border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-8 py-6 border-b border-slate-50 flex justify-between items-center">
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Movimientos de Caja & Bancos Recientes</h2>
                    <span class="text-[10px] font-bold text-slate-300">Página 1 de 45</span>
                </div>
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Fecha</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Descripción / Proveedor</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Categoría</th>
                            <th class="px-8 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Monto</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @for($i=1; $i<=4; $i++)
                        <tr class="hover:bg-slate-50 transition-all cursor-pointer">
                            <td class="px-8 py-4 text-xs font-medium text-slate-400">Hoy, 10:25 AM</td>
                            <td class="px-8 py-4">
                                <span class="block text-sm font-bold text-slate-700">Pago Factura #{{rand(100,999)}}</span>
                                <span class="text-[9px] font-medium text-slate-400 uppercase tracking-tighter">Amazon Web Services</span>
                            </td>
                            <td class="px-8 py-4">
                                <span class="px-2 py-1 bg-slate-100 text-slate-500 rounded-md text-[9px] font-bold uppercase">SaaS / Hosting</span>
                            </td>
                            <td class="px-8 py-4 text-sm font-black text-slate-800 text-right">-$ {{ number_format(rand(100000, 500000), 0) }}</td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar: Invoice Aging & Shortcuts (Xero Column) -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm min-h-[300px]">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-8 text-center mt-2">Envejecimiento de Cartera (A/R)</h3>
                
                <div class="space-y-6">
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase tracking-tighter">
                            <span>Corriente (1-30 días)</span>
                            <span class="text-emerald-600">$ 8.5M</span>
                        </div>
                        <div class="h-2 bg-slate-50 rounded-full overflow-hidden">
                            <div class="bg-emerald-500 h-full w-[70%]"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase tracking-tighter">
                            <span>Vencido (31-60 días)</span>
                            <span class="text-amber-500">$ 2.1M</span>
                        </div>
                        <div class="h-2 bg-slate-50 rounded-full overflow-hidden">
                            <div class="bg-amber-400 h-full w-[25%]"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-black uppercase tracking-tighter text-rose-500">
                            <span>Crítico (+90 días)</span>
                            <span>$ 1.8M</span>
                        </div>
                        <div class="h-2 bg-slate-50 rounded-full overflow-hidden">
                            <div class="bg-rose-500 h-full w-[15%]"></div>
                        </div>
                    </div>
                </div>

                <button class="w-full mt-10 py-3 border-2 border-dashed border-slate-200 rounded-2xl text-[10px] font-black text-slate-400 hover:border-emerald-400 hover:text-emerald-500 transition-all uppercase tracking-widest">Enviar Recordatorios de Cobro</button>
            </div>

            <!-- Tax Summary AI (QuickBooks Premium) -->
            <div class="bg-slate-900 p-8 rounded-[40px] text-white overflow-hidden relative group">
                <div class="relative z-10 transition-transform group-hover:translate-x-1 duration-500">
                    <h3 class="text-lg font-black mb-1">Cierre Contable Mes</h3>
                    <p class="text-xs text-slate-400 font-medium mb-6 leading-relaxed">He verificado tus ingresos y deducciones. Estás 100% al día con tus obligaciones de IVA e ICA.</p>
                    <div class="flex items-center gap-3">
                        <span class="text-emerald-400 font-black text-2xl">✓</span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Status: Auditoría Superada</span>
                    </div>
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
            </div>
        </div>
    </div>
</div>
@endsection
