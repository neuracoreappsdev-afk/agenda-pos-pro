@extends('admin.dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1400px] mx-auto" style="font-family: 'Outfit', sans-serif;">
    <!-- SimplePractice Style Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-black text-slate-800 tracking-tight">Consola Clínica Terapéutica</h1>
            <p class="text-slate-400 text-sm font-medium mt-1">Cumplimiento de HIPAA y protocolos de seguridad de datos</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-white border border-slate-200 px-5 py-2.5 rounded-xl font-bold text-sm text-slate-700 shadow-sm hover:bg-slate-50 transition-all">Portal del Paciente</button>
            <button class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-black text-sm shadow-lg shadow-emerald-500/20 hover:bg-emerald-700 transition-all">+ Nueva Sesión</button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation: Clinic Folders -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-6">Archivo Clínico</h3>
                <nav class="space-y-4">
                    <a href="#" class="flex items-center justify-between text-sm font-bold text-emerald-600">
                        <span>🧠 Sesiones Hoy</span>
                        <span class="bg-emerald-50 px-2 py-0.5 rounded text-[10px]">{{ $stats['total_sesiones'] }}</span>
                    </a>
                    <a href="#" class="flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                        <span>👥 Pacientes Activos</span>
                    </a>
                    <a href="#" class="flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                        <span>📑 Notas de Consentimiento</span>
                    </a>
                    <a href="#" class="flex items-center text-sm font-bold text-slate-500 hover:text-emerald-600 transition-colors">
                        <span>🧾 Facturación Médica</span>
                    </a>
                </nav>
            </div>

            <div class="bg-slate-900 p-6 rounded-[32px] text-white overflow-hidden relative">
                <div class="relative z-10">
                    <span class="text-[9px] font-black text-emerald-400 uppercase tracking-[3px] mb-2 block">AI Sentiment</span>
                    <h4 class="text-sm font-bold mb-4">Análisis de Progreso</h4>
                    <p class="text-[11px] text-slate-400 leading-relaxed italic border-l-2 border-emerald-500 pl-3">
                        "El paciente muestra una reducción del 15% en marcadores de ansiedad lingüística comparado con la sesión #3."
                    </p>
                </div>
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-2xl"></div>
            </div>
        </div>

        <!-- Main Workspace: Focused Notes & Timeline -->
        <div class="lg:col-span-3 space-y-8">
            <!-- Progress Timeline (SimplePractice UX) -->
            <div class="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-10 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    Evolución Reciente de Pacientes
                </h2>
                
                <div class="relative border-l-2 border-slate-100 ml-3 space-y-12">
                    @php
                        $evolutions = [
                            ['paciente' => 'Laura Mendez', 'tipo' => 'Sesión Individual', 'date' => 'Hoy, 09:00 AM', 'summary' => 'Continuación de terapia cognitivo-conductual. Se observa mejora en los patrones de sueño.', 'icon' => '🧘‍♀️'],
                            ['paciente' => 'Roberto Diaz', 'tipo' => 'Evaluación Inicial', 'date' => 'Ayer, 04:30 PM', 'summary' => 'Primera toma de contacto. Detección de posibles cuadros depresivos leves. Escala PHQ-9: 12 puntos.', 'icon' => '👤'],
                            ['paciente' => 'Sofia Castro', 'tipo' => 'Terapia de Pareja', 'date' => 'Ene 30, 11:15 AM', 'summary' => 'Fomento de la comunicación asertiva. Ejercicios presenciales satisfactorios.', 'icon' => '👥'],
                        ];
                    @endphp

                    @foreach($evolutions as $e)
                    <div class="relative pl-10">
                        <div class="absolute -left-[11px] top-1 w-5 h-5 bg-white border-2 border-emerald-500 rounded-full"></div>
                        <div class="group cursor-pointer">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-sm font-black text-slate-800 group-hover:text-emerald-600 transition-colors">{{ $e['paciente'] }}</h3>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $e['tipo'] }}</span>
                                </div>
                                <span class="text-[10px] font-black text-slate-300 uppercase">{{ $e['date'] }}</span>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 text-xs text-slate-600 leading-relaxed italic shadow-sm group-hover:shadow-md transition-all">
                                "{{ $e['summary'] }}"
                            </div>
                            <div class="mt-3 flex gap-4">
                                <button class="text-[10px] font-black text-emerald-600 hover:underline uppercase tracking-widest">Ver Nota Completa</button>
                                <button class="text-[10px] font-black text-slate-400 hover:text-slate-600 uppercase tracking-widest">Enviar Evolución</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Zen Mode Prompt -->
            <div class="bg-emerald-600 p-8 rounded-[40px] text-white flex justify-between items-center shadow-xl shadow-emerald-500/20 group cursor-pointer overflow-hidden relative">
                <div class="relative z-10 transition-transform group-hover:translate-x-2 duration-500">
                    <h4 class="text-lg font-black mb-1">Módulo de Escritura Zen</h4>
                    <p class="text-sm text-emerald-100 font-medium">Inicia una sesión en blanco y deja que la IA organice tus notas automáticamente.</p>
                </div>
                <div class="text-4xl opacity-20 group-hover:opacity-100 transition-opacity translate-x-4 group-hover:translate-x-0 duration-500">✍️</div>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>
            </div>
        </div>
    </div>
</div>
@endsection
