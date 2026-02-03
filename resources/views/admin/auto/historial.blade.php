@extends('admin/dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1600px] mx-auto" style="font-family: 'Outfit', sans-serif;">
    <!-- Header: Tekmetric / ShopMonkey Style -->
    <div class="flex justify-between items-center mb-8 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-slate-900 text-white rounded-xl">🔧</span>
                Gestión de Taller Automotriz
            </h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Control de órdenes de servicio e inspecciones digitales</p>
        </div>
        <div class="flex gap-4">
            <button class="bg-white border border-slate-200 px-6 py-2.5 rounded-xl font-black text-xs text-slate-700 uppercase tracking-widest shadow-sm">Lista de Repuestos</button>
            <button class="bg-emerald-600 text-white px-8 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:bg-emerald-700">Nueva Orden de Trabajo</button>
        </div>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Vehículos en Taller</span>
            <div class="text-3xl font-black text-slate-800">{{ $stats['ordenes_activas'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Items en Inventario</span>
            <div class="text-3xl font-black text-slate-800">{{ $stats['inventario_repuestos'] }}</div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Citas Preventivas</span>
            <div class="text-3xl font-black text-emerald-600">{{ $stats['proximos_mantenimientos'] }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <!-- Center: Inspection & Jobs (Tekmetric UX) -->
        <div class="lg:col-span-8 space-y-8">
            <div class="bg-white rounded-[40px] border border-slate-100 overflow-hidden shadow-sm">
                <div class="px-8 py-6 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-sm font-black text-slate-800 uppercase tracking-[2px]">Inspección Digital del Vehículo</h2>
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[9px] font-black uppercase tracking-widest">Sincronizado con IA</span>
                </div>

                <div class="p-8">
                    <div class="space-y-6">
                        @php
                            $items = [
                                ['name' => 'Sistema de Frenos', 'status' => 'Crítico', 'color' => 'rose', 'desc' => 'Pastillas al 15% de vida útil.'],
                                ['name' => 'Fluido de Motor (Aceite)', 'status' => 'Óptimo', 'color' => 'emerald', 'desc' => 'Cambiado hace 500km.'],
                                ['name' => 'Tren Delantero / Suspensión', 'status' => 'Recomendado', 'color' => 'amber', 'desc' => 'Posible fuga en amortiguador derecho.'],
                                ['name' => 'Neumáticos / Presión', 'status' => 'Óptimo', 'color' => 'emerald', 'desc' => 'Desgaste parejo detectado.'],
                            ];
                        @endphp

                        @foreach($items as $item)
                        <div class="flex items-center gap-6 p-5 bg-slate-50 rounded-3xl border border-slate-100 group hover:border-emerald-200 transition-all">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                <span class="text-lg">@if($item['color'] == 'rose') ⚠️ @elseif($item['color'] == 'emerald') ✅ @else 🔍 @endif</span>
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-800 text-sm">{{ $item['name'] }}</h3>
                                <p class="text-[10px] text-slate-500 mt-1 italic">{{ $item['desc'] }}</p>
                            </div>
                            <div class="text-right">
                                <span class="px-3 py-1 @if($item['color'] == 'rose') bg-rose-50 text-rose-600 @elseif($item['color'] == 'emerald') bg-emerald-50 text-emerald-600 @else bg-amber-50 text-amber-600 @endif rounded-full text-[10px] font-black uppercase tracking-tighter">
                                    {{ $item['status'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="p-8 bg-slate-900 text-white flex justify-between items-center cursor-pointer hover:bg-black transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center">🤖</div>
                        <div>
                            <h4 class="text-sm font-bold">Generar Presupuesto con IA</h4>
                            <p class="text-[10px] text-slate-400">Calcula automáticamente piezas y mano de obra basada en inspección.</p>
                        </div>
                    </div>
                    <span class="text-emerald-400">⚡</span>
                </div>
            </div>
        </div>

        <!-- Right: Active Orders & CRM (ShopMonkey Style) -->
        <div class="lg:col-span-4 space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-8 text-center">Vehículos en Turno</h3>
                <div class="space-y-6">
                    @for($i=1; $i<=4; $i++)
                    <div class="flex items-center gap-4 p-4 border border-slate-50 rounded-3xl hover:border-emerald-200 transition-all cursor-pointer">
                        <div class="w-12 h-12 bg-slate-100 rounded-2xl flex items-center justify-center text-xl">🚗</div>
                        <div class="flex-1">
                            <h4 class="text-xs font-black text-slate-800 uppercase tracking-tighter">BMW X5 - ABC{{$i}}23</h4>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-[8px] font-bold text-slate-400 uppercase">En progreso</span>
                                <div class="flex-1 bg-slate-100 h-1 rounded-full overflow-hidden">
                                    <div class="bg-emerald-500 h-full w-[{{$i * 20}}%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="bg-emerald-600 p-8 rounded-[40px] text-white shadow-xl shadow-emerald-500/20">
                <h3 class="text-lg font-black mb-2">WhatsApp Hub</h3>
                <p class="text-[11px] text-emerald-100 font-medium leading-relaxed mb-6">Envía reportes fotográficos de inspección directamente al cliente para aprobación instantánea.</p>
                <button class="w-full py-2.5 bg-white text-emerald-600 rounded-xl font-black text-[10px] uppercase tracking-widest">Enviar Reporte PDF</button>
            </div>
        </div>
    </div>
</div>
@endsection
