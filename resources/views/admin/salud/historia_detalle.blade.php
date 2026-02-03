@extends('admin/dashboard_layout')

@section('content')
<style>
    :root {
        --neura-indigo: #4f46e5;
        --neura-purple: #9333ea;
        --neura-emerald: #10b981;
        --neura-rose: #f43f5e;
        --glass: rgba(255, 255, 255, 0.7);
    }

    .clinical-container {
        background: radial-gradient(circle at top right, #f8fafc, #f1f5f9);
        min-height: 100vh;
    }

    .glass-card { 
        background: var(--glass);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 32px;
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    .status-tooth { cursor: pointer; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); filter: drop-shadow(0 4px 6px rgba(0,0,0,0.05)); }
    .status-tooth:hover { transform: scale(1.15) translateY(-5px); z-index: 50; }
    .tooth-num { font-size: 10px; font-weight: 900; color: #64748b; margin-top: 4px; }
    
    /* Interactive Odontogram Colors */
    .surface-healthy { fill: #ffffff; stroke: #e2e8f0; }
    .surface-cavity { fill: #f43f5e; stroke: #be123c; }
    .surface-filled { fill: #3b82f6; stroke: #1d4ed8; }
    .surface-missing { fill: #94a3b8; stroke: #475569; }
    .surface-selected { stroke: #4f46e5; stroke-width: 3px; filter: drop-shadow(0 0 8px rgba(79, 70, 229, 0.3)); }

    .ai-gradient-btn {
        background: linear-gradient(135deg, #10b981 0%, #3b82f6 100%);
        color: white;
        border: none;
        box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.4);
        transition: all 0.3s;
    }
    .ai-gradient-btn:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 15px 30px -5px rgba(16, 185, 129, 0.6);
        filter: brightness(1.1);
    }

    .action-btn {
        padding: 14px 28px;
        border-radius: 20px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-size: 11px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .evolution-timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, transparent, #e2e8f0 10%, #e2e8f0 90%, transparent);
    }

    .tooth-part { transition: fill 0.3s; }
</style>

<div class="clinical-container p-4 md:p-8">
    <!-- Main Header -->
    <div class="max-w-7xl mx-auto mb-10">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <a href="{{ url('admin/historias-clinicas') }}" class="w-14 h-14 rounded-2xl bg-white shadow-sm flex items-center justify-center hover:bg-slate-50 transition-all text-slate-600 border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight leading-none mb-2">Expediente Clínico PRO</h1>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-indigo-50 text-indigo-600 text-[10px] font-black rounded-full uppercase tracking-tighter border border-indigo-100 italic">Estatus: Activo</span>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Paciente: <span class="text-indigo-600 ml-1">{{ $patient->name }}</span></p>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap gap-3">
                <button onclick="window.open('{{ url('admin/historias-clinicas/reporte/' . (count($records) > 0 ? $records[0]->id : '0')) }}', '_blank')" class="action-btn bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 shadow-sm">
                    <span>🖨️</span> Reporte
                </button>
                <button onclick="openConsentModal()" class="action-btn bg-slate-900 text-white hover:bg-black shadow-xl">
                    <span>✍️</span> Consentimiento
                </button>
                <button onclick="document.getElementById('formEvolution').scrollIntoView({behavior: 'smooth'})" class="action-btn ai-gradient-btn">
                    <span>⚡</span> Nueva Evolución
                </button>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- SIDEBAR (Col 4) -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Patient Profile Card -->
            <div class="glass-card p-8">
                <div class="flex flex-col items-center text-center mb-8">
                    <div class="w-24 h-24 bg-gradient-to-br from-indigo-500 to-fuchsia-600 rounded-3xl flex items-center justify-center text-4xl shadow-2xl mb-4 text-white">
                        {{ strtoupper(substr($patient->name, 0, 1)) }}
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 leading-tight">{{ $patient->name }}</h2>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest italic">ID: {{ $patient->document_id }}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-4 bg-white/50 rounded-2xl border border-white/40 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Sangre</span>
                        <span class="text-sm font-black text-rose-600">O POS (+)</span>
                    </div>
                    <div class="p-4 bg-white/50 rounded-2xl border border-white/40 shadow-sm">
                        <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Peso</span>
                        <span class="text-sm font-black text-indigo-600">72.4 KG</span>
                    </div>
                    <div class="p-4 bg-white/50 rounded-2xl border border-white/40 shadow-sm col-span-2">
                        <span class="text-[9px] font-black text-slate-400 uppercase block mb-1">Alergias Detectadas</span>
                        <span class="text-xs font-bold text-rose-500">PENICILINA, LATEX</span>
                    </div>
                </div>
            </div>

            <!-- Enhanced Timeline -->
            <div class="glass-card p-8">
                <h3 class="text-xs font-black text-slate-900 uppercase tracking-widest mb-8 flex items-center gap-2">
                    <span class="w-3 h-3 rounded-full bg-emerald-500 animate-pulse"></span> Historial de Evoluciones
                </h3>
                <div class="space-y-8 relative evolution-timeline">
                    @forelse($records as $record)
                    <div class="relative pl-12 group">
                        <div class="absolute left-0 top-0 w-10 h-10 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center justify-center z-10 group-hover:scale-110 transition-all">
                             <span class="text-xs">📋</span>
                        </div>
                        <div class="bg-white/40 backdrop-blur-sm p-5 rounded-2xl border border-white/60 hover:border-indigo-200 hover:bg-white transition-all cursor-pointer shadow-sm">
                            <p class="text-[9px] font-black text-indigo-400 uppercase mb-1">{{ date('d M, Y', strtotime($record->created_at)) }}</p>
                            <h4 class="text-xs font-black text-slate-900 mb-2">MOTIVO: {{ $record->reason ?? 'No especificado' }}</h4>
                            <p class="text-xs text-slate-700 font-bold leading-relaxed mb-2">{{ $record->notes }}</p>
                            @if($record->formula)
                            <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                                <span class="bg-emerald-50 text-emerald-600 text-[8px] font-black px-2 py-0.5 rounded-full">RECETA EMITIDA</span>
                                <a href="{{ url('admin/historias-clinicas/reporte/' . $record->id) }}" target="_blank" class="text-[9px] font-black text-slate-400 hover:text-indigo-600 flex items-center gap-1">🖨️ Imprimir</a>
                            </div>
                            @else
                            <div class="mt-2 pt-2 border-t border-slate-100 flex justify-end">
                                <a href="{{ url('admin/historias-clinicas/reporte/' . $record->id) }}" target="_blank" class="text-[9px] font-black text-slate-400 hover:text-indigo-600 flex items-center gap-1">🖨️ Imprimir</a>
                            </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                         <div class="text-4xl mb-3 opacity-20">📂</div>
                         <p class="text-xs text-slate-400 font-bold italic">No hay registros aún</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- MAIN AREA (Col 8) -->
        <div class="lg:col-span-8 space-y-8">
            
            @if($businessVertical == 'salud')
            <!-- Odontograma Next-Gen -->
            <div class="glass-card p-8 overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Estatus Odontológico</h2>
                        <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest">Mapa Anatómico Digital</p>
                    </div>
                    <div class="flex gap-4">
                        <div class="flex items-center gap-2 group cursor-help" title="Caries detectada">
                            <div class="w-4 h-4 rounded-full bg-rose-500 border-4 border-white shadow-sm transition-transform group-hover:scale-125"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase">Caries</span>
                        </div>
                        <div class="flex items-center gap-2 group cursor-help" title="Restauración previa">
                            <div class="w-4 h-4 rounded-full bg-blue-500 border-4 border-white shadow-sm transition-transform group-hover:scale-125"></div>
                            <span class="text-[10px] font-black text-slate-500 uppercase">Obturado</span>
                        </div>
                    </div>
                </div>

                <div class="relative overflow-x-auto pb-10 scrollbar-thin">
                    <div class="flex flex-col gap-12 min-w-[850px] items-center py-4">
                        <!-- Upper Jaw -->
                        <div class="flex gap-4 p-4 rounded-[40px] bg-slate-50/50 border border-slate-100">
                            @foreach([18,17,16,15,14,13,12,11, 21,22,23,24,25,26,27,28] as $num)
                            <div class="flex flex-col items-center group/tooth">
                                <svg width="42" height="42" viewBox="0 0 40 40" class="status-tooth" onclick="selectTooth('{{ $num }}', this)">
                                    <path d="M5 5 L35 5 L30 15 L10 15 Z" class="surface-healthy tooth-part" data-surface="V" />
                                    <path d="M5 35 L35 35 L30 25 L10 25 Z" class="surface-healthy tooth-part" data-surface="L" />
                                    <path d="M5 5 L5 35 L10 25 L10 15 Z" class="surface-healthy tooth-part" data-surface="M" />
                                    <path d="M35 5 L35 35 L30 25 L30 15 Z" class="surface-healthy tooth-part" data-surface="D" />
                                    <rect x="10" y="15" width="20" height="10" class="surface-healthy tooth-part" data-surface="O" />
                                </svg>
                                <span class="tooth-num group-hover/tooth:text-indigo-600 transition-colors">{{ $num }}</span>
                            </div>
                            @if($num == 11) <div class="w-8 border-r-2 border-slate-200 border-dashed mx-2 h-10"></div> @endif
                            @endforeach
                        </div>

                        <!-- Lower Jaw -->
                        <div class="flex gap-4 p-4 rounded-[40px] bg-slate-50/50 border border-slate-100">
                            @foreach([48,47,46,45,44,43,42,41, 31,32,33,34,35,36,37,38] as $num)
                            <div class="flex flex-col items-center group/tooth">
                                <span class="tooth-num mb-2 group-hover/tooth:text-indigo-600 transition-colors">{{ $num }}</span>
                                <svg width="42" height="42" viewBox="0 0 40 40" class="status-tooth" onclick="selectTooth('{{ $num }}', this)">
                                    <path d="M5 5 L35 5 L30 15 L10 15 Z" class="surface-healthy tooth-part" data-surface="V" />
                                    <path d="M5 35 L35 35 L30 25 L10 25 Z" class="surface-healthy tooth-part" data-surface="L" />
                                    <path d="M5 5 L5 35 L10 25 L10 15 Z" class="surface-healthy tooth-part" data-surface="M" />
                                    <path d="M35 5 L35 35 L30 25 L30 15 Z" class="surface-healthy tooth-part" data-surface="D" />
                                    <rect x="10" y="15" width="20" height="10" class="surface-healthy tooth-part" data-surface="O" />
                                </svg>
                            </div>
                            @if($num == 41) <div class="w-8 border-r-2 border-slate-200 border-dashed mx-2 h-10"></div> @endif
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Floating Toolbar -->
                <div id="odontogramTools" class="mt-4 p-2 bg-slate-900 rounded-3xl hidden animate-in slide-in-from-bottom duration-300">
                    <div class="flex items-center gap-6 px-6 py-4">
                        <div class="flex items-center gap-3 pr-6 border-r border-slate-700">
                            <div class="w-10 h-10 rounded-2xl bg-indigo-600 flex items-center justify-center text-white text-xs font-black" id="selectedToothLabel">--</div>
                            <div>
                                <span class="text-[9px] font-black text-slate-500 uppercase">Diente</span>
                                <h4 class="text-xs font-bold text-white leading-none">Seleccionado</h4>
                            </div>
                        </div>
                        <div class="flex flex-1 justify-around">
                            <button onclick="applyToothStatus('cavity')" class="flex flex-col items-center gap-1 group">
                                <div class="w-10 h-10 rounded-xl bg-rose-500/20 border border-rose-500/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">🍎</div>
                                <span class="text-[9px] font-bold text-slate-400">Carie</span>
                            </button>
                            <button onclick="applyToothStatus('filled')" class="flex flex-col items-center gap-1 group">
                                <div class="w-10 h-10 rounded-xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">💠</div>
                                <span class="text-[9px] font-bold text-slate-400">Obturado</span>
                            </button>
                            <button onclick="applyToothStatus('missing')" class="flex flex-col items-center gap-1 group">
                                <div class="w-10 h-10 rounded-xl bg-slate-500/20 border border-slate-500/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">❌</div>
                                <span class="text-[9px] font-bold text-slate-400">Ausente</span>
                            </button>
                            <button onclick="applyToothStatus('healthy')" class="flex flex-col items-center gap-1 group">
                                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-xl group-hover:scale-110 transition-transform">✨</div>
                                <span class="text-[9px] font-bold text-slate-400">Sano</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Evolution & AI Form -->
            <div id="formEvolution" class="glass-card overflow-hidden">
                <div class="p-8 border-b border-slate-100/50">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Registro Clínico</h2>
                            <p class="text-slate-400 font-bold text-[10px] uppercase tracking-widest italic">Evolución y Diagnóstico IA</p>
                        </div>
                        <button type="button" onclick="getAISuggestion()" id="btnAISuggest" class="action-btn ai-gradient-btn px-8">
                            <span id="aiIcon">🤖</span> <span id="aiText">Consultar IA</span>
                        </button>
                    </div>
                </div>

                <div class="p-8">
                    <!-- AI Suggestion Canvas -->
                    <div id="aiResult" class="hidden mb-10 p-1 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-[32px] shadow-2xl animate-in zoom-in duration-500">
                        <div class="bg-white/95 backdrop-blur-md rounded-[31px] p-8 relative overflow-hidden">
                             <div class="absolute -right-10 -top-10 text-[180px] opacity-5 pointer-events-none">🪄</div>
                             <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-3">
                                        <span class="px-4 py-1.5 bg-emerald-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest shadow-lg shadow-emerald-200">NeuraCore Analysis</span>
                                        <span id="aiConfidence" class="text-xs font-black text-emerald-400">Confianza: 98.4%</span>
                                    </div>
                                    <button onclick="document.getElementById('aiResult').classList.add('hidden')" class="text-slate-300 hover:text-slate-500 transition-colors">✕</button>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div>
                                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Diagnóstico Sugerido</h4>
                                        <p id="aiDiagnosis" class="text-lg font-black text-slate-800 leading-tight">CIE-10: Analizando...</p>
                                    </div>
                                    <div>
                                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Tratamiento Recomendado</h4>
                                        <p id="aiPrescription" class="text-sm text-slate-600 font-medium italic"></p>
                                    </div>
                                </div>
                                <div class="mt-8 flex gap-3">
                                    <button onclick="applyAISuggestion()" class="flex-1 py-4 bg-slate-900 text-white font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-black transition-all shadow-lg">Aplicar y Continuar</button>
                                    <button onclick="document.getElementById('aiResult').classList.add('hidden')" class="px-6 py-4 text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-600">Ignorar</button>
                                </div>
                             </div>
                        </div>
                    </div>

                    <form action="{{ url('admin/historias-clinicas/save') }}" method="POST">
                        {!! csrf_field() !!}
                        <input type="hidden" name="customer_id" value="{{ $patient->id }}">
                        <input type="hidden" id="clinicalDataInput" name="clinical_data">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="md:col-span-2">
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2 block">Motivo de Consulta <span class="text-rose-500">*</span></label>
                                <input type="text" name="reason" required class="w-full px-6 py-5 rounded-[24px] border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm font-bold placeholder:text-slate-300 shadow-inner" 
                                    placeholder="Ej: Dolor agudo en molar superior, Limpieza general, etc">
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2 block">Resumen de Evolución <span class="text-indigo-500">*</span></label>
                                <textarea id="notesArea" name="notes" rows="6" required class="w-full px-6 py-5 rounded-[28px] border border-slate-100 bg-slate-50 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm font-bold placeholder:text-slate-300 shadow-inner" 
                                    placeholder="Detalla el tratamiento realizado hoy..."></textarea>
                            </div>
                            <div>
                                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-4 mb-2 block">Recetario / Indicaciones (AI-Optimized)</label>
                                <textarea id="formulaArea" name="formula" rows="5" class="w-full px-6 py-5 rounded-[28px] border border-indigo-50 bg-indigo-50/20 focus:bg-white focus:ring-4 focus:ring-indigo-100 transition-all outline-none text-sm font-bold text-indigo-900 placeholder:text-indigo-200 shadow-inner" 
                                    placeholder="¿Manejo farmacológico?"></textarea>
                            </div>
                        </div>

                        <div class="mt-10 pt-8 border-t border-slate-100 flex items-center justify-between">
                             <div class="flex items-center gap-3 text-[10px] font-bold text-slate-400 uppercase">
                                 <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Firma Digital Requerida
                             </div>
                            <button type="submit" class="action-btn ai-gradient-btn px-16 py-5">
                                💾 Registrar Firma y Evolución
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- PROFESSIONAL CONSENT MODAL (GLASSMORPHISM) -->
<div id="consentModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-xl" onclick="closeConsentModal()"></div>
    <div class="relative bg-white w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
        <!-- Header -->
        <div class="p-8 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
            <div>
                <h3 class="text-xl font-black text-slate-800 tracking-tight">Consentimiento Informado</h3>
                <p class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest mt-1 italic" id="consentTitle">Validez Legal Digital</p>
            </div>
            <button onclick="closeConsentModal()" class="w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-slate-200 transition-colors text-slate-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <div class="p-10">
            <div class="max-h-[40vh] overflow-y-auto mb-8 pr-4 text-sm text-slate-600 font-medium leading-relaxed" id="consentContent">
                <p class="mb-4">Yo, <strong>{{ $patient->name }}</strong>, identificado con documento <strong>{{ $patient->document_id }}</strong>, declaro bajo juramento que he sido informado de manera comprensible sobre el procedimiento dental/médico propuesto.</p>
                <p class="mb-4">He comprendido los beneficios, riesgos inherentes y las alternativas terapéuticas. Autorizo expresamente al profesional a cargo para proceder con el tratamiento registrado en este expediente.</p>
                <p>Esta firma digital tiene plena validez jurídica bajo la ley de comercio electrónico vigente.</p>
            </div>
            
            <div class="relative">
                <div class="absolute top-4 left-6 z-10">
                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Firma Autógrafa del Paciente</span>
                </div>
                <div class="bg-indigo-50/50 rounded-[32px] border-2 border-dashed border-indigo-200 overflow-hidden">
                    <canvas id="signatureCanvas" class="w-full h-48 cursor-crosshair touch-none"></canvas>
                </div>
                <div class="flex justify-between mt-4">
                    <button onclick="clearSignature()" class="text-[10px] font-black text-rose-500 uppercase tracking-widest hover:underline">Reiniciar Trazo</button>
                    <p class="text-[10px] text-slate-400 font-bold uppercase italic">Usa el mouse o pantalla táctil</p>
                </div>
            </div>
        </div>

        <div class="p-8 bg-slate-50 flex gap-4">
            <button onclick="closeConsentModal()" class="flex-1 py-4 text-slate-400 font-bold text-xs uppercase tracking-widest hover:bg-slate-200 rounded-2xl transition-all">Cancelar</button>
            <button onclick="saveConsent()" class="flex-[2] py-4 ai-gradient-btn font-black text-xs uppercase tracking-widest rounded-2xl shadow-xl transition-all">✅ Confirmar Identidad y Firma</button>
        </div>
    </div>
</div>

<script>
    let currentTooth = null;
    let toothStates = {}; 
    let lastAiSuggestion = null;

    function selectTooth(num, el) {
        currentTooth = num;
        document.getElementById('odontogramTools').classList.remove('hidden');
        document.getElementById('selectedToothLabel').innerText = num;
        document.querySelectorAll('.status-tooth').forEach(svg => svg.classList.remove('surface-selected'));
        el.classList.add('surface-selected');
    }

    function applyToothStatus(status) {
        if(!currentTooth) return;
        const svg = document.querySelector(`.surface-selected`);
        const surfaces = svg.querySelectorAll('.tooth-part');
        surfaces.forEach(s => { 
            s.className.baseVal = `surface-${status} tooth-part`; 
        });
        toothStates[currentTooth] = status;
        document.getElementById('clinicalDataInput').value = JSON.stringify(toothStates);
        showToast(`Diente ${currentTooth} marcado como ${status}`, 'info');
    }

    // --- AI Logic Integration ---
    function getAISuggestion() {
        const notes = document.getElementById('notesArea').value;
        if(notes.length < 15) {
            showNeuraModal({
                title: 'Información Insuficiente',
                message: 'Para que el análisis sea preciso, describe un poco más los hallazgos clínicos o síntomas del paciente.',
                type: 'warning',
                confirmText: 'Entendido'
            });
            return;
        }

        const btn = document.getElementById('btnAISuggest');
        const text = document.getElementById('aiText');
        const icon = document.getElementById('aiIcon');

        btn.disabled = true;
        text.innerText = 'PROCESANDO...';
        icon.innerText = '🌀';
        icon.classList.add('animate-spin');

        fetch('{{ url("admin/historias-clinicas/ai-suggest") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ 
                notes: notes,
                customer_id: '{{ $patient->id }}'
            })
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            text.innerText = 'CONSULTAR IA';
            icon.innerText = '🤖';
            icon.classList.remove('animate-spin');

            if(data.success) {
                lastAiSuggestion = data.suggestion;
                const resultPanel = document.getElementById('aiResult');
                resultPanel.classList.remove('hidden');
                document.getElementById('aiDiagnosis').innerText = data.suggestion.diagnosis;
                document.getElementById('aiPrescription').innerText = data.suggestion.prescription;
                document.getElementById('aiConfidence').innerText = 'Confianza: ' + data.suggestion.confidence;
                resultPanel.scrollIntoView({behavior: 'smooth', block: 'center'});
                showToast('Análisis clínico completado', 'success');
            } else {
                showNeuraModal({
                    title: 'Agente Ocupado',
                    message: data.error || 'El asistente inteligente está experimentando alta demanda. Por favor, reintenta en un momento.',
                    type: 'danger'
                });
                if(data.upgrade_url) {
                    showNeuraModal({
                        title: 'Plan Premium Requerido',
                        message: 'Esta función avanzada de IA solo está disponible para usuarios en el plan Premium.',
                        type: 'warning',
                        confirmText: 'Ver Planes',
                        cancelText: 'Cerrar',
                        onConfirm: () => window.location.href = data.upgrade_url
                    });
                }
            }
        })
        .catch(err => {
            btn.disabled = false;
            text.innerText = 'CONSULTAR IA';
            icon.innerText = '🤖';
            icon.classList.remove('animate-spin');
            console.error(err);
            showToast('Error de conexión con NeuraCore', 'error');
        });
    }

    function applyAISuggestion() {
        if(!lastAiSuggestion) return;
        const notesArea = document.getElementById('notesArea');
        const formulaArea = document.getElementById('formulaArea');
        notesArea.value += "\n\n--- DIAGNÓSTICO IA ---\n" + lastAiSuggestion.diagnosis;
        formulaArea.value = lastAiSuggestion.prescription;
        document.getElementById('aiResult').classList.add('hidden');
        showToast('Sugerencia aplicada al historial', 'success');
    }

    // --- Signature Logic (Hi-Fi) ---
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;
    const canvas = document.getElementById('signatureCanvas');
    const ctx = canvas.getContext('2d');

    function openConsentModal() {
        document.getElementById('consentModal').classList.remove('hidden');
        document.getElementById('consentModal').classList.add('flex');
        setTimeout(resizeCanvas, 100);
    }

    function closeConsentModal() {
        document.getElementById('consentModal').classList.add('hidden');
        document.getElementById('consentModal').classList.remove('flex');
    }

    function resizeCanvas() {
        const container = canvas.parentElement;
        canvas.width = container.offsetWidth;
        canvas.height = 200;
        ctx.strokeStyle = "#1e293b";
        ctx.lineWidth = 3;
        ctx.lineCap = "round";
        ctx.lineJoin = "round";
    }

    canvas.addEventListener('mousedown', (e) => {
        isDrawing = true;
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    canvas.addEventListener('mousemove', (e) => {
        if (!isDrawing) return;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(e.offsetX, e.offsetY);
        ctx.stroke();
        [lastX, lastY] = [e.offsetX, e.offsetY];
    });

    canvas.addEventListener('mouseup', () => isDrawing = false);
    
    // Touch Support
    canvas.addEventListener('touchstart', (e) => {
        e.preventDefault();
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches[0];
        isDrawing = true;
        lastX = touch.clientX - rect.left;
        lastY = touch.clientY - rect.top;
    }, { passive: false });

    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (!isDrawing) return;
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches[0];
        const x = touch.clientX - rect.left;
        const y = touch.clientY - rect.top;
        ctx.beginPath();
        ctx.moveTo(lastX, lastY);
        ctx.lineTo(x, y);
        ctx.stroke();
        lastX = x;
        lastY = y;
    }, { passive: false });

    canvas.addEventListener('touchend', () => isDrawing = false);

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function saveConsent() {
        const signatureData = canvas.toDataURL();
        if(signatureData.length < 2000) { // Simple check for empty canvas
             showToast('Por favor, ingresa tu firma para continuar', 'warning');
             return;
        }

        fetch('{{ url("consentimientos/guardar") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                customer_id: '{{ $patient->id }}',
                title: document.getElementById('consentTitle').innerText,
                content: document.getElementById('consentContent').innerText,
                signature_data: signatureData
            })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                showNeuraModal({
                    title: '¡Firma Guardada!',
                    message: 'El consentimiento informado ha sido registrado y vinculado a la historia clínica del paciente.',
                    type: 'success'
                });
                closeConsentModal();
            } else {
                showToast('Error al procesar la firma', 'error');
            }
        })
        .catch(err => showToast('Error de comunicación', 'error'));
    }
</script>
@endsection
