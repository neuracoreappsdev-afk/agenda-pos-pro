@extends('admin/dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1600px] mx-auto" style="font-family: 'Outfit', sans-serif;">
    <!-- Header: Mindbody Style -->
    <div class="flex justify-between items-center mb-10 pb-6 border-b border-slate-100">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                <span class="p-2 bg-slate-900 text-white rounded-xl">🏋️</span>
                Centro de Entrenamiento Elite
            </h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Gestión de Clases, Socios y Aforo en tiempo real</p>
        </div>
        <div class="flex gap-4">
            <div class="flex bg-slate-100 p-1 rounded-xl">
                <button class="px-6 py-2.5 bg-white shadow-sm rounded-lg text-xs font-black text-slate-800 uppercase">Hoy</button>
                <button class="px-6 py-2.5 text-xs font-black text-slate-400 uppercase hover:text-slate-600 transition-all">Semana</button>
                <button class="px-6 py-2.5 text-xs font-black text-slate-400 uppercase hover:text-slate-600 transition-all">Reportes</button>
            </div>
            <button class="bg-emerald-600 text-white px-8 py-2.5 rounded-xl font-black text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-500/20">
                + Nueva Venta / Plan
            </button>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Aforo Actual</span>
            <div class="text-4xl font-black text-slate-800">{{ rand(15, 45) }} <span class="text-lg text-slate-300">/ 80</span></div>
            <div class="mt-4 w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                <div class="bg-emerald-500 h-full w-[65%] rounded-full"></div>
            </div>
            <span class="absolute top-6 right-6 text-2xl opacity-10 group-hover:opacity-100 transition-opacity">👥</span>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Socios Activos</span>
            <div class="text-4xl font-black text-slate-800">{{ $stats['socios_activos'] }}</div>
            <div class="text-[10px] font-bold text-emerald-600 mt-2">↑ 5% esta semana</div>
        </div>
        <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Pagos Pendientes</span>
            <div class="text-4xl font-black text-rose-500">12</div>
            <div class="text-[10px] font-bold text-slate-400 mt-2">Requieren gestión</div>
        </div>
        <div class="bg-slate-900 p-6 rounded-[32px] text-white shadow-2xl">
            <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest block mb-1 italic">Inteligencia Artificial</span>
            <div class="text-xl font-bold mb-2">Previsión de Abandono</div>
            <div class="flex items-center gap-2">
                <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-500 border-2 border-slate-900 flex items-center justify-center text-[10px]">A</div>
                    <div class="w-6 h-6 rounded-full bg-slate-700 border-2 border-slate-900"></div>
                </div>
                <span class="text-[10px] font-bold text-slate-400">8 socios en riesgo</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
        <!-- Center Column: Class Schedule (Mindbody Core) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">Horario de Clases - Hoy</h2>
                <div class="flex gap-2">
                    <button class="p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-500 hover:text-emerald-600">Crossfit</button>
                    <button class="p-2 bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-500 hover:text-emerald-600">Yoga</button>
                </div>
            </div>

            <div class="space-y-3">
                @php
                    $classes = [
                        ['time' => '06:00 AM', 'name' => 'Entrenamiento Funcional Hiit', 'coach' => 'Carlos Rodriguez', 'spots' => '12/15', 'status' => 'Full'],
                        ['time' => '08:00 AM', 'name' => 'Yoga Balance & Flow', 'coach' => 'Lina Maria', 'spots' => '05/20', 'status' => 'Disponible'],
                        ['time' => '10:00 AM', 'name' => 'Levantamiento Olímpico', 'coach' => 'Andrés G.', 'spots' => '08/10', 'status' => 'Pocos Cupos'],
                        ['time' => '05:00 PM', 'name' => 'Zumba Party Night', 'coach' => 'Viviana R.', 'spots' => '02/30', 'status' => 'Disponible'],
                    ];
                @endphp

                @foreach($classes as $c)
                <div class="bg-white p-5 rounded-[24px] border border-slate-100 flex items-center gap-6 group hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-500/5 transition-all">
                    <div class="w-24 text-center border-r border-slate-100 pr-4">
                        <span class="text-sm font-black text-slate-800 block">{{ explode(' ', $c['time'])[0] }}</span>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ explode(' ', $c['time'])[1] }}</span>
                    </div>
                    <div class="flex-1">
                        <span class="text-[10px] font-black text-emerald-600 uppercase mb-1 block">Clase Grupal</span>
                        <h3 class="font-bold text-slate-800 mb-1 group-hover:text-emerald-600 transition-colors">{{ $c['name'] }}</h3>
                        <p class="text-xs text-slate-400 font-medium">Coach Principal: <span class="text-slate-600 font-bold">{{ $c['coach'] }}</span></p>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-black text-slate-800 mb-2">{{ $c['spots'] }} <span class="text-[10px] text-slate-300">CUPOS</span></div>
                        <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase @if($c['status'] == 'Full') bg-rose-50 text-rose-600 @else bg-emerald-50 text-emerald-600 @endif">
                            {{ $c['status'] }}
                        </span>
                    </div>
                    <div class="pl-4 border-l border-slate-100">
                        <button class="w-10 h-10 bg-slate-50 text-slate-400 rounded-xl flex items-center justify-center hover:bg-emerald-600 hover:text-white transition-all">➡️</button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Right Column: Membership & CRM (Mindbody CRM Style) -->
        <div class="space-y-8">
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm min-h-[400px]">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-8 text-center mt-2">Ranking de Socios del Mes</h2>
                <div class="space-y-6">
                    @for($i=1; $i<=5; $i++)
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-slate-100 rounded-2xl flex items-center justify-center font-black text-slate-400 text-sm">#{{$i}}</div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-slate-800">Socio Estrella #{{$i}}</h4>
                            <p class="text-[10px] text-slate-400 font-bold uppercase">Plan Anual Platinum</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-black text-emerald-600">{{ rand(8, 22) }}</span>
                            <span class="block text-[8px] font-bold text-slate-300">ASISTENCIAS</span>
                        </div>
                    </div>
                    @endfor
                </div>
                <button class="w-full mt-10 py-3 border-2 border-dashed border-slate-200 rounded-2xl text-xs font-black text-slate-400 hover:border-emerald-400 hover:text-emerald-500 transition-all transition-all">Ver Todos los Socios</button>
            </div>

            <!-- Access Log Activity -->
            <div class="bg-slate-50 p-6 rounded-[32px] border border-slate-100">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Registro de Ingresos (QR)</h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm border border-slate-50">
                        <span class="text-lg">✅</span>
                        <div class="text-[11px]"><strong>Juan Perez</strong> - Ingreso Detectado (07:15 AM)</div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-white rounded-xl shadow-sm border border-slate-50">
                        <span class="text-lg text-rose-500">❌</span>
                        <div class="text-[11px]"><strong>Maria L.</strong> - Error: Plan Vencido</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
