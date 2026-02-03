@extends('admin.dashboard_layout')

@section('content')
<div class="flex h-screen bg-[#fcfcfc]" style="font-family: 'Outfit', sans-serif;">
    <!-- Sub-Sidebar: Clio Style Workspace Navigation -->
    <div class="w-64 border-r border-slate-200 bg-white p-6 hidden xl:block">
        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Mis Espacios</h3>
        <nav class="space-y-1">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-xl font-bold text-sm">
                <span>📁</span> Procesos Activos
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-xl font-medium text-sm transition-all">
                <span>📑</span> Plantillas de Contratos
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-xl font-medium text-sm transition-all">
                <span>⚖️</span> Jurisprudencia Guardada
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-slate-500 hover:bg-slate-50 rounded-xl font-medium text-sm transition-all">
                <span>🏛️</span> Despachos Judiciales
            </a>
        </nav>

        <div class="mt-12 p-5 bg-slate-900 rounded-[24px] text-white">
            <div class="flex justify-between items-center mb-4">
                <span class="text-[10px] font-bold text-emerald-400 uppercase">Timer Activo</span>
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
            </div>
            <div class="text-3xl font-black mb-1">01:45:22</div>
            <p class="text-[10px] text-slate-400 mb-4">Caso: #2024-0012 Divorcio</p>
            <button class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 rounded-xl text-xs font-black transition-all">Pausar e Imputar</button>
        </div>
    </div>

    <!-- Main Workspace -->
    <div class="flex-1 overflow-auto p-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Expedientes Digitales</h1>
                <p class="text-sm text-slate-500 mt-1">Gestión integral del despacho en tiempo real</p>
            </div>
            <div class="flex gap-3">
                <button class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl font-bold text-sm text-slate-700 shadow-sm hover:bg-slate-50 transition-all">Importar Caso</button>
                <button class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">+ Nuevo Expediente</button>
            </div>
        </div>

        <!-- Metric Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm">
                <div class="text-emerald-600 font-black text-2xl mb-1">{{ $stats['casos_activos'] }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Casos en Litigio</div>
            </div>
            <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm">
                <div class="text-slate-800 font-black text-2xl mb-1">{{ $stats['audiencias_hoy'] }}</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Audiencias Próximas</div>
            </div>
            <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm">
                <div class="text-slate-800 font-black text-2xl mb-1">${{ number_format($stats['honorarios_pendientes'] / 1000000, 1) }}M</div>
                <div class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Cartera Global</div>
            </div>
        </div>

        <!-- Table: Clio Style (Data Dense & Clean) -->
        <div class="bg-white rounded-[32px] border border-slate-200 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-black text-slate-800 uppercase tracking-widest text-xs">Historial de Turnos y Procesos</h2>
                <div class="flex gap-4">
                    <span class="text-xs font-bold text-slate-400 cursor-pointer hover:text-emerald-600">Filtrar por Etapa</span>
                    <span class="text-xs font-bold text-slate-400 cursor-pointer hover:text-emerald-600">Exportar PDF</span>
                </div>
            </div>
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Referencia</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Cliente</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Etapa Procesal</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Última Actuación</th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @for($i=1; $i<=5; $i++)
                    <tr class="hover:bg-slate-50 transition-colors cursor-pointer group">
                        <td class="px-8 py-5">
                            <span class="block font-bold text-sm text-slate-800">EXP-2024-00{{$i}}</span>
                            <span class="text-[10px] text-slate-400">Civil • Responsabilidad</span>
                        </td>
                        <td class="px-8 py-5 text-sm font-medium text-slate-600">Corporación Técnica SAS</td>
                        <td class="px-8 py-5">
                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold">Contestación</span>
                        </td>
                        <td class="px-8 py-5 text-xs text-slate-500">Hace 2 horas por IA Agent</td>
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span class="text-xs font-bold text-emerald-600">Al Día</span>
                            </div>
                        </td>
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
