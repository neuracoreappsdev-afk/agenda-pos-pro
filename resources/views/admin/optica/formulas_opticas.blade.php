@extends('admin.dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1500px] mx-auto" style="font-family: 'Outfit', sans-serif;">
    <!-- Optica Pro Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">👓</span>
                Consola de Optometría & Óptica
            </h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Gestión de récords visuales y fórmulas oftálmicas</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl font-bold text-sm text-slate-700 hover:bg-slate-50 transition-all">Inventario Monturas</button>
            <button class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">+ Nueva Consulta</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Main: Clinical Record & RX (Compulink Style) -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-10 pb-4 border-b border-slate-50">
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-[4px]">Fórmula del Paciente (RX)</h2>
                    <span class="text-[10px] font-bold text-slate-300">FECHA: {{ date('d/m/Y') }}</span>
                </div>

                <!-- RX Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Ojo Derecho -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-emerald-600 text-white rounded-lg flex items-center justify-center font-black text-xs">OD</span>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Ojo Derecho</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-black text-slate-300 uppercase">Esfera</span>
                                <input type="text" value="-2.50" class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-700 focus:ring-0">
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-black text-slate-300 uppercase">Cilindro</span>
                                <input type="text" value="-0.75" class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-700 focus:ring-0">
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-black text-slate-300 uppercase">Eje</span>
                                <input type="text" value="180°" class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-700 focus:ring-0">
                            </div>
                        </div>
                    </div>
                    <!-- Ojo Izquierdo -->
                    <div class="space-y-6">
                        <div class="flex items-center gap-3">
                            <span class="w-8 h-8 bg-slate-900 text-white rounded-lg flex items-center justify-center font-black text-xs">OI</span>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Ojo Izquierdo</h3>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-black text-slate-300 uppercase">Esfera</span>
                                <input type="text" value="-2.25" class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-700 focus:ring-0">
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-black text-slate-300 uppercase">Cilindro</span>
                                <input type="text" value="-1.00" class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-700 focus:ring-0">
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-black text-slate-300 uppercase">Eje</span>
                                <input type="text" value="165°" class="w-full bg-transparent border-none p-0 text-sm font-black text-slate-700 focus:ring-0">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 p-6 bg-slate-900 rounded-[32px] text-white flex justify-between items-center group cursor-pointer hover:bg-black transition-all">
                    <div>
                        <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest block mb-1">AI Assistant: Lens Recommendation</span>
                        <h4 class="text-sm font-bold">Sugerencia: Lentes Digitales Blue-Block</h4>
                        <p class="text-[10px] text-slate-400">Basado en la fórmula y el perfil de trabajo en oficina del paciente.</p>
                    </div>
                    <button class="bg-emerald-600 px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition-all">Generar Cotización</button>
                </div>
            </div>

            <!-- Frame Selection (Uprise Style) -->
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-xs font-black text-slate-800 uppercase tracking-widest">Selección de Monturas Sugeridas</h2>
                    <span class="text-xs font-bold text-emerald-600 cursor-pointer">Ver Catálogo Completo</span>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @for($i=1; $i<=4; $i++)
                    <div class="bg-slate-50 p-4 rounded-3xl border border-slate-100 text-center hover:border-emerald-200 transition-all cursor-pointer group">
                        <div class="w-full aspect-video bg-white rounded-2xl mb-4 flex items-center justify-center text-3xl group-hover:scale-110 transition-all">👓</div>
                        <h4 class="text-[10px] font-black text-slate-800 tracking-tighter uppercase">Modelo Premium V{{$i}}</h4>
                        <span class="text-[9px] font-bold text-slate-400 block mt-1">Marca LuxOTT</span>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- Sidebar: Patient History & Activity -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm min-h-[300px]">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 text-center">Actividad Reciente</h3>
                <div class="space-y-6">
                    @for($i=1; $i<=3; $i++)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-black text-xs">A{{$i}}</div>
                        <div class="flex-1">
                            <h4 class="text-[11px] font-black text-slate-800 uppercase">Examen Diagnóstico</h4>
                            <span class="text-[9px] text-slate-400 font-bold tracking-tighter uppercase italic">Paciente: Maria Garcia</span>
                        </div>
                        <div class="text-right">
                             <span class="text-[9px] font-bold text-slate-300">HACE 2H</span>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="bg-slate-900 p-8 rounded-[40px] text-white shadow-2xl relative overflow-hidden group">
                <div class="relative z-10 transition-transform group-hover:translate-y-[-5px] duration-500">
                    <h3 class="text-lg font-black mb-2 flex items-center gap-2">
                        <span class="text-emerald-400">📋</span> Gestión PACS
                    </h3>
                    <p class="text-[11px] text-slate-400 font-medium leading-relaxed mb-6 italic">Sincroniza y visualiza topografías corneales y fotos de fondo de ojo en alta resolución.</p>
                    <button class="w-full py-2.5 bg-emerald-600 text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-700 transition-all">Sincronizar Equipo</button>
                </div>
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-emerald-500/5 rounded-full blur-3xl transition-all group-hover:bg-emerald-500/10"></div>
            </div>
        </div>
    </div>
</div>
@endsection
