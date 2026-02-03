@extends('admin/dashboard_layout')

@section('content')
<div class="page-container" style="padding: 30px; background: #fafafa; min-height: 100vh;">
    <!-- Top Header -->
    <div class="flex justify-between items-center mb-10">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[10px] font-black rounded-md uppercase tracking-wider">Módulo de Auditoría</span>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Consola del Contador <span class="text-indigo-600">IA</span></h1>
            <p class="text-slate-500 font-medium">Análisis financiero y cumplimiento normativo en <span class="font-bold text-slate-800">{{ $stats['pais'] }}</span></p>
        </div>
        <div class="flex gap-4">
            <button class="bg-white border border-slate-200 px-6 py-3 rounded-2xl font-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                📥 Exportar Libros
            </button>
            <button class="bg-indigo-600 text-white px-8 py-3 rounded-2xl font-black hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 uppercase tracking-widest text-xs">
                Auditar Periodo 🔍
            </button>
        </div>
    </div>

    <!-- Main Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-8 rounded-[38px] border border-slate-100 shadow-sm hover:shadow-xl transition-all">
            <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-4">Ingresos Brutos</div>
            <div class="text-3xl font-black text-slate-900">${{ number_format($stats['ingresos'], 0) }}</div>
            <div class="mt-4 flex items-center gap-2 text-emerald-500 font-bold text-xs">
                <span>↑ 12.5%</span> <span class="text-slate-300 font-normal">vs mes anterior</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[38px] border border-slate-100 shadow-sm hover:shadow-xl transition-all">
            <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-4">Egresos / Gastos</div>
            <div class="text-3xl font-black text-slate-900">${{ number_format($stats['gastos'], 0) }}</div>
            <div class="mt-4 flex items-center gap-2 text-rose-500 font-bold text-xs">
                <span>↑ 4.2%</span> <span class="text-slate-300 font-normal">operativos</span>
            </div>
        </div>
        <div class="bg-white p-8 rounded-[38px] border border-slate-100 shadow-sm hover:shadow-xl transition-all">
            <div class="text-slate-400 text-xs font-black uppercase tracking-widest mb-4">Impuestos Est.</div>
            <div class="text-3xl font-black text-indigo-600">${{ number_format($stats['impuestos'], 0) }}</div>
            <div class="mt-4 text-[10px] text-slate-400 font-bold">Base: IVA {{ $stats['pais'] == 'Colombia' ? '19%' : '16%' }}</div>
        </div>
        <div class="bg-slate-900 p-8 rounded-[38px] shadow-2xl shadow-indigo-200">
            <div class="text-indigo-300 text-xs font-black uppercase tracking-widest mb-4">Utilidad Neta</div>
            <div class="text-3xl font-black text-white">${{ number_format($stats['utilidad'], 0) }}</div>
            <div class="mt-4 text-emerald-400 font-bold text-xs">Margen: {{ $stats['ingresos'] > 0 ? round(($stats['utilidad'] / $stats['ingresos']) * 100, 1) : 0 }}%</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- AI Auditor Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-[45px] border border-slate-100 overflow-hidden shadow-sm">
                <div class="p-8 bg-gradient-to-r from-indigo-600 to-blue-700 text-white flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center text-3xl">
                            🤖
                        </div>
                        <div>
                            <h2 class="text-xl font-black tracking-tight">Análisis Normativo IA</h2>
                            <p class="text-indigo-100 text-xs font-medium">Validado con normatividad de {{ $stats['pais'] }} 2026</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-10">
                    <!-- Recommendation 1 -->
                    <div class="flex gap-6 mb-10 pb-10 border-b border-slate-50">
                        <div class="flex-shrink-0 w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 mb-2 uppercase tracking-wide text-xs">Alerta de Cumplimiento Fiscal</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                Hemos detectado que el 15% de tus egresos este mes no tienen una factura electrónica asociada. Según la normatividad vigente en **{{ $stats['pais'] }}**, esto podría generar una sanción en la próxima auditoría trimestral.
                            </p>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black uppercase">Riesgo Alto</span>
                                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-black uppercase tracking-tight">Requiere Acción</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recommendation 2 -->
                    <div class="flex gap-6 mb-10 pb-10 border-b border-slate-50">
                        <div class="flex-shrink-0 w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                            💡
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 mb-2 uppercase tracking-wide text-xs">Oportunidad de Optimización Tributaria</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                La utilidad actual permite aplicar a un beneficio de exención por inversión en tecnología (Art. 158-1). Podrías deducir hasta el 25% de la inversión en este software del impuesto de renta de este año.
                            </p>
                            <button class="text-indigo-600 font-bold text-xs hover:underline decoration-2">Ver beneficios aplicables →</button>
                        </div>
                    </div>

                    <!-- Recommendation 3 -->
                    <div class="flex gap-6">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl font-bold">
                            📊
                        </div>
                        <div>
                            <h3 class="font-black text-slate-800 mb-2 uppercase tracking-wide text-xs">Proyección de Flujo de Caja</h3>
                            <p class="text-slate-500 text-sm leading-relaxed mb-4">
                                Con la tendencia actual, se estima un remanente operativo de ${{ number_format($stats['utilidad'] * 1.1, 0) }} para el próximo mes. Se recomienda provisionar el pago de impuestos bimestrales antes del día 15.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div>
            <div class="bg-indigo-900 rounded-[45px] p-8 text-white mb-8 shadow-2xl shadow-indigo-200">
                <h3 class="text-lg font-black mb-6 flex items-center gap-2">
                    <span class="text-2xl">🔌</span> Conector Externo
                </h3>
                <p class="text-indigo-200 text-xs mb-8 leading-relaxed">Sincroniza los datos con tu software contable oficial (Siigo, Alegra, QuickBooks, etc.)</p>
                
                <div class="space-y-4">
                    <div class="p-5 bg-white/5 border border-white/10 rounded-3xl flex justify-between items-center group hover:bg-white/10 cursor-pointer transition-all">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-blue-300">S</div>
                            <span class="text-sm font-black">Siigo Nube</span>
                        </div>
                        <span class="text-[10px] bg-emerald-400/20 text-emerald-400 px-2 py-0.5 rounded-full">Activo</span>
                    </div>
                    <div class="p-5 bg-white/5 border border-white/10 rounded-3xl flex justify-between items-center group hover:bg-white/10 cursor-pointer transition-all opacity-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center text-orange-300">A</div>
                            <span class="text-sm font-black">Alegra</span>
                        </div>
                        <span class="text-[10px] text-white/40">Vincular</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[45px] p-8 border border-slate-100 shadow-sm">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6">Próximos Vencimientos</h3>
                <div class="space-y-6">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex flex-col items-center justify-center font-bold">
                            <span class="text-[10px] uppercase">Feb</span>
                            <span>15</span>
                        </div>
                        <div>
                            <div class="text-xs font-black text-slate-800">IVA Bimestral</div>
                            <div class="text-[10px] text-slate-400 uppercase">Declaración y Pago</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-50 text-slate-400 rounded-2xl flex flex-col items-center justify-center font-bold">
                            <span class="text-[10px] uppercase">Feb</span>
                            <span>28</span>
                        </div>
                        <div>
                            <div class="text-xs font-black text-slate-800">ICA Municipal</div>
                            <div class="text-[10px] text-slate-400 uppercase">Tarifa Retenciones</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
