@extends('admin/dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1600px] mx-auto" style="font-family: 'Outfit', sans-serif;">
    <!-- Welcome Header: Booksy Style -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tighter">Bienvenido, {{ session('user_name') }}</h1>
            <p class="text-slate-500 font-medium">Panel de control operacional para <span class="text-emerald-600 font-black">AgendaPOS PRO</span></p>
        </div>
        <div class="flex gap-4">
            <a href="{{ url('booking') }}" target="_blank" class="bg-white border border-slate-200 px-6 py-3 rounded-2xl font-black text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:shadow-md transition-all">Ver Perfil Público</a>
            <button class="bg-emerald-600 text-white px-8 py-3 rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-all">+ Nueva Cita</button>
        </div>
    </div>

    <!-- Metrics Strip -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">💰</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Ventas Hoy</span>
                <span class="text-xl font-black text-slate-800">$ {{ number_format($salesToday, 0, ',', '.') }}</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-xl">📅</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Citas Totales</span>
                <span class="text-xl font-black text-slate-800">{{ $appointmentsCount }}</span>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-xl">👤</div>
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Nuevos Clientes</span>
                <span class="text-xl font-black text-slate-800">{{ $newCustomersToday }}</span>
            </div>
        </div>
        <div class="bg-slate-900 p-6 rounded-[32px] text-white flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center text-xl">🤖</div>
            <div>
                <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block">AI Smart Fill</span>
                <span class="text-sm font-bold">Activo: Llenando huecos</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Left: Calendar Slot View (Booksy Core) -->
        <div class="lg:col-span-2 bg-white rounded-[40px] border border-slate-100 p-8 shadow-sm">
            <div class="flex justify-between items-center mb-10">
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Agenda de Hoy</h2>
                <div class="flex items-center gap-2">
                    <span class="text-xs font-bold text-slate-400">Filtrar por Especialista:</span>
                    <div class="flex -space-x-3">
                        @for($i=1; $i<=4; $i++)
                        <div class="w-8 h-8 rounded-full bg-slate-200 border-2 border-white flex items-center justify-center text-[10px] font-bold text-slate-500 cursor-pointer hover:z-10 transition-all hover:scale-110">E{{$i}}</div>
                        @endfor
                    </div>
                </div>
            </div>

            <!-- Time Slots Grid -->
            <div class="space-y-4">
                @forelse($dailySlots as $slotItem)
                <div class="flex items-center gap-6 p-4 rounded-3xl border border-dashed @if($slotItem['status'] == 'Vacío') border-emerald-200 bg-emerald-50/20 @else border-slate-100 @endif group cursor-pointer hover:border-emerald-400 transition-all">
                    <div class="w-20 text-center">
                        <span class="text-xs font-black text-slate-800 block">{{ explode(' ', $slotItem['time'])[0] }}</span>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ explode(' ', $slotItem['time'])[1] }}</span>
                    </div>
                    <div class="flex-1">
                        @if($slotItem['status'] == 'Vacío')
                            <span class="text-xs font-black text-emerald-600 uppercase tracking-widest">✨ Espacio Disponible</span>
                            <p class="text-[10px] text-emerald-400 italic">Clic para agendar cita rápida</p>
                        @else
                            <h4 class="text-sm font-bold text-slate-800">{{ $slotItem['client'] }}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $slotItem['service'] }} • <span class="text-slate-600">{{ $slotItem['staff'] }}</span></p>
                        @endif
                    </div>
                    @if($slotItem['status'] != 'Vacío')
                        <div class="text-right pr-4 border-r border-slate-50">
                            <span class="text-sm font-black text-slate-800">{{ $slotItem['cost'] }}</span>
                            <span class="block text-[10px] font-bold text-emerald-500 uppercase tracking-widest">{{ $slotItem['status'] }}</span>
                        </div>
                    @endif
                    <div class="pl-4">
                        <button class="w-10 h-10 @if($slotItem['status'] == 'Vacío') bg-emerald-600 text-white @else bg-slate-50 text-slate-400 @endif rounded-2xl flex items-center justify-center hover:scale-110 transition-all shadow-sm">
                            @if($slotItem['status'] == 'Vacío') + @else 👁️ @endif
                        </button>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-20 bg-slate-50 rounded-[40px] border-2 border-dashed border-slate-200">
                    <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-3xl mb-4">📅</div>
                    <p class="text-slate-500 font-bold text-sm">No hay citas programadas para hoy</p>
                    <p class="text-slate-400 text-xs">Las nuevas citas aparecerán aquí automáticamente</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Professional Services (Booksy Showcase Style) -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8">Servicios Más Rentables</h3>
                <div class="space-y-6">
                    @forelse($topServices as $ts)
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center text-2xl group-hover:scale-110 transition-all">✨</div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800">{{ $ts['name'] }}</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[10px] font-black text-emerald-600">{{ $ts['price'] }}</span>
                                <span class="text-[8px] font-bold text-white bg-slate-900 px-1.5 py-0.5 rounded-full uppercase tracking-tighter">{{ $ts['leads'] }} vendidos</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-4 text-center">
                        <p class="text-slate-400 text-xs font-bold">Sin datos de ventas aún</p>
                    </div>
                    @endforelse
                </div>
                <a href="{{ url('admin/packages') }}" class="w-full mt-10 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-[10px] font-black text-slate-500 uppercase tracking-widest hover:bg-slate-100 transition-all text-center block">Administrar Catálogo</a>
            </div>

            <!-- Portfolio Quick Look -->
            <div class="bg-slate-900 p-8 rounded-[40px] text-white overflow-hidden relative group">
                <div class="relative z-10">
                    <h3 class="text-lg font-black mb-1">Portfolio IA</h3>
                    <p class="text-xs text-slate-400 font-medium mb-6 leading-relaxed">Sube fotos de tus trabajos para que la IA genere un portafolio profesional para tus clientes.</p>
                    <div class="grid grid-cols-2 gap-2">
                         <div class="aspect-square bg-white/5 rounded-2xl border border-white/10 flex items-center justify-center text-xs opacity-50">Esperando Fotos</div>
                         <div class="aspect-square bg-white/5 rounded-2xl border border-white/10 flex items-center justify-center text-xs opacity-50">Esperando Fotos</div>
                    </div>
                </div>
                <div class="absolute -right-4 -top-4 w-32 h-32 bg-emerald-500/10 rounded-full blur-3xl group-hover:bg-emerald-500/20 transition-all duration-700"></div>
            </div>
        </div>
    </div>
</div>
@endsection
