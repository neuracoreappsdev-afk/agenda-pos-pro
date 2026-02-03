@extends('admin/dashboard_layout')


@section('content')
<!-- Full Screen Clinical Workspace (No external padding) -->
<div class="flex flex-col h-[calc(100vh-60px)] bg-slate-100 overflow-hidden">
    
    <!-- Top Action Bar (Compact) -->
    <div class="bg-white border-b border-slate-200 px-4 py-2 flex justify-between items-center z-20 shrink-0 h-14">
        <div class="flex items-center gap-3">
            <!-- Patient Context (Clickable for Anamnesis) -->
             <div onclick="toggleAnamnesis()" class="flex items-center gap-3 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 hover:border-emerald-300 transition-all group">
                <div class="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center font-bold text-xs ring-2 ring-emerald-500 shadow-sm transition-transform group-hover:scale-110" title="Ver Historia Médica">JM</div>
                <div class="leading-tight">

                    <h2 class="text-sm font-black text-slate-800">Juan Martinez</h2>
                    <p class="text-[10px] text-slate-500 font-medium flex gap-2">
                        <span>34 Años</span>
                        <span class="text-red-500 font-bold">⚠️ Alergia</span>
                    </p>
                </div>
             </div>
             
             <!-- Tab Switcher (Integrated in Topbar - High Visibility) -->
             <div class="flex bg-slate-100 p-1 rounded-xl border border-slate-200 hidden md:flex gap-1 items-center">
                 <button onclick="switchTab('odonto')" class="tab-btn active px-4 py-2 text-xs font-bold rounded-lg bg-white text-slate-800 shadow-sm ring-1 ring-slate-200 transition-all flex items-center gap-2" id="btn-tab-odonto">
                    <span class="text-lg">🦷</span> Odontograma
                 </button>
                 <button onclick="switchTab('perio')"  class="tab-btn px-4 py-2 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-all flex items-center gap-2" id="btn-tab-perio">
                    <span class="text-lg">📏</span> Periodonto
                 </button>
                 <button onclick="switchTab('rx')"     class="tab-btn px-4 py-2 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-all flex items-center gap-2" id="btn-tab-rx">
                    <span class="text-lg">☢️</span> Rayos X
                 </button>
                 <button onclick="switchTab('history')" class="tab-btn px-4 py-2 text-xs font-bold rounded-lg text-slate-500 hover:text-slate-700 hover:bg-slate-200/50 transition-all flex items-center gap-2" id="btn-tab-history">
                    <span class="text-lg">📋</span> Historial
                 </button>
                 
                 <!-- Notation Toggle (Moved Here) -->
                 <div class="h-6 w-px bg-slate-300 mx-1"></div>
                 <button onclick="toggleNotation()" class="px-3 py-1.5 text-[10px] uppercase font-black tracking-wider rounded-lg border border-slate-300 text-slate-500 hover:bg-slate-100 transition-all" id="notation-btn" title="Cambiar Sistema de Numeración">
                    FDI (ISO)
                 </button>
            </div>
        </div>

        <div class="flex gap-2">
             <button class="px-3 py-1.5 bg-white border border-slate-300 text-slate-600 rounded-lg text-xs font-bold hover:bg-slate-50 transition-all flex items-center gap-2">
                🔍 <span class="hidden sm:inline">Buscar</span>
            </button>
            <button onclick="openNewPatientModal()" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-blue-600 text-white rounded-xl text-xs font-black hover:scale-105 shadow-lg shadow-emerald-500/20 flex items-center gap-2 transition-all">
                <span>✨</span> <span class="hidden sm:inline">Nuevo Paciente</span>
            </button>
        </div>
    </div>

    <!-- Main Workspace (Flex Row) -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- SIDEBAR 1: Patient Quick Queue (Slim Rail) -->
        <div class="w-16 hover:w-64 bg-slate-900 flex flex-col items-center hover:items-stretch transition-all duration-300 z-30 group shadow-xl">
             <div class="p-3 border-b border-slate-800 flex justify-center group-hover:justify-start group-hover:px-4 items-center h-14 shrink-0">
                <span class="text-emerald-500 text-xl">👥</span>
                <span class="text-white font-bold text-xs ml-3 hidden group-hover:block whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity delay-100">Sala de Espera (4)</span>
             </div>
             
             <!-- Patient List -->
             <div class="flex-1 overflow-y-auto overflow-x-hidden p-2 space-y-2 scrollbar-hide">
                 <!-- Active (JM) -->
                <div class="flex items-center p-2 rounded-xl bg-slate-800 border-l-4 border-emerald-500 cursor-pointer">
                    <div class="w-8 h-8 rounded-full bg-slate-700 text-white flex items-center justify-center font-bold text-xs shrink-0">JM</div>
                    <div class="ml-3 hidden group-hover:block transition-all w-40">
                         <h4 class="text-xs font-bold text-slate-200 truncate">Juan Martinez</h4>
                         <span class="text-[9px] text-emerald-400 font-bold block">En Sillón 2</span>
                    </div>
                </div>
                 <!-- Waiting (AL) -->
                 <div class="flex items-center p-2 rounded-xl hover:bg-slate-800 border-l-4 border-transparent hover:border-slate-600 cursor-pointer opacity-70 hover:opacity-100 transition-all">
                    <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 flex items-center justify-center font-bold text-xs shrink-0">AL</div>
                    <div class="ml-3 hidden group-hover:block transition-all w-40">
                         <h4 class="text-xs font-bold text-slate-400 group-hover:text-slate-300 truncate">Ana Lopez</h4>
                         <span class="text-[9px] text-slate-500 block">15 min espera</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- CENTER: Clinical Canvas (Expands to fill) -->
        <div class="flex-1 bg-slate-200 relative overflow-hidden flex flex-col">
            
            <!-- TAB CONTENT CONTAINER -->
            <div class="flex-1 relative">
                
                <!-- TAB 1: ODONTOGRAMA -->
                <div id="tab-odonto" class="tab-content absolute inset-0 flex flex-col">
                    <!-- Dark Canvas Background -->
                    <div class="flex-1 bg-slate-900 relative shadow-inner overflow-hidden flex items-center justify-center">
                         
                         <!-- Floating Toolbar (Centered Top Horizontal) -->
                         <div class="absolute top-6 left-1/2 -translate-x-1/2 flex flex-row gap-3 p-2 bg-slate-800/90 backdrop-blur-md rounded-2xl border border-slate-700/50 shadow-2xl z-20 items-center">
                            <button onclick="setTool('cursor')" class="tool-btn active w-10 h-10 rounded-xl flex items-center justify-center text-slate-300 hover:bg-slate-700 bg-slate-700 ring-2 ring-emerald-500 transition-all" id="btn-cursor" title="Seleccionar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897"></path></svg>
                            </button>
                            <div class="w-px h-6 bg-slate-600 mx-1"></div>
                            <button onclick="setTool('caries')" class="tool-btn w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:scale-110 transition-transform" id="btn-caries" title="Caries"><div class="w-4 h-4 rounded-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.6)]"></div></button>
                            <button onclick="setTool('resina')" class="tool-btn w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:scale-110 transition-transform" id="btn-resina" title="Resina"><div class="w-4 h-4 rounded-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.6)]"></div></button>
                            <button onclick="setTool('corona')" class="tool-btn w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-700 hover:scale-110 transition-transform" id="btn-corona" title="Corona"><div class="w-4 h-4 rounded-full bg-yellow-400 shadow-[0_0_10px_rgba(250,204,21,0.6)]"></div></button>
                            <button onclick="setTool('ausente')" class="tool-btn w-10 h-10 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-700 hover:text-white font-bold transition-all" id="btn-ausente" title="Ausente">X</button>
                         </div>

                         <!-- Grid & Texture -->
                         <div class="absolute inset-0 opacity-[0.07]" style="background-image: radial-gradient(#64748b 1px, transparent 1px); background-size: 32px 32px;"></div>
                         
                         <!-- Teeth Container (Centered & Large) -->
                         <div id="odontogram-container" class="z-10 w-full max-w-4xl scale-[0.8] md:scale-[0.9] lg:scale-100 transition-transform origin-center">
                            <!-- Upper Jaw -->
                            <div class="flex gap-16 justify-center mb-8">
                                <div class="flex gap-1.5 items-end" id="quadrant-1"></div>
                                <div class="flex gap-1.5 items-end" id="quadrant-2"></div>
                            </div>
                            <!-- Lower Jaw -->
                            <div class="flex gap-16 justify-center mt-8">
                                <div class="flex gap-1.5 items-start" id="quadrant-4"></div>
                                <div class="flex gap-1.5 items-start" id="quadrant-3"></div>
                            </div>
                         </div>
                    </div>
                </div>

                <!-- TAB 2: PERIODONTOGRAMA -->
                <div id="tab-perio" class="tab-content absolute inset-0 hidden bg-white flex-col">
                     <div class="p-6 overflow-auto flex-1 flex justify-center">
                         <div class="max-w-6xl w-full">
                            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6 mb-4">
                                <h3 class="text-sm font-black text-slate-700 mb-4 uppercase tracking-widest">Arcada Superior</h3>
                                <div class="flex gap-2 overflow-x-auto pb-4">
                                     @foreach([18,17,16,15,14,13,12,11,21,22,23,24,25,26,27,28] as $t)
                                     <div class="flex flex-col gap-1 w-12 items-center shrink-0">
                                         <span class="text-[9px] font-bold text-slate-400">VEST</span>
                                         <div class="flex gap-px w-full"><input class="w-1/3 text-center border h-6 text-xs" maxlength="1"><input class="w-1/3 text-center border h-6 text-xs" maxlength="1"><input class="w-1/3 text-center border h-6 text-xs" maxlength="1"></div>
                                         <div class="w-10 h-10 bg-slate-100 rounded border flex items-center justify-center font-bold text-slate-600">{{$t}}</div>
                                         <div class="flex gap-px w-full"><input class="w-1/3 text-center border h-6 text-xs" maxlength="1"><input class="w-1/3 text-center border h-6 text-xs" maxlength="1"><input class="w-1/3 text-center border h-6 text-xs" maxlength="1"></div>
                                         <span class="text-[9px] font-bold text-slate-400">PAL</span>
                                     </div>
                                     @if($t==11) <div class="w-8"></div> @endif
                                     @endforeach
                                </div>
                            </div>
                        </div>
                     </div>
                </div>

                <!-- TAB 3: RX STUDIO -->
                <div id="tab-rx" class="tab-content absolute inset-0 hidden bg-black flex-col">
                    <div class="h-48 bg-black rounded-2xl shadow-sm border border-slate-800 relative group overflow-hidden shrink-0">
                         <div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/f/f6/Dental_Panorama_X-ray.jpg')] bg-cover bg-center opacity-70 filter grayscale contrast-125 transition-all group-hover:opacity-100" id="rx-mini-image"></div>
                         <div class="absolute inset-0 pointer-events-none bg-gradient-to-t from-black/80 to-transparent"></div>
                    </div>
                    <div class="flex-1 bg-[url('https://upload.wikimedia.org/wikipedia/commons/f/f6/Dental_Panorama_X-ray.jpg')] bg-contain bg-center bg-no-repeat" id="rx-main-image"></div>
                    <!-- Controls Bar -->
                    <div class="h-16 bg-gradient-to-t from-black to-black/80 flex items-center justify-center gap-8 px-8 border-t border-white/10 z-20">
                         <div class="flex items-center gap-3 w-64">
                             <span class="text-white text-[10px] font-bold uppercase w-16">Brillo</span>
                             <input type="range" class="flex-1 h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-white" oninput="adjustRx(this.value, 'brightness')">
                             <span class="text-white text-[10px] w-8 text-right" id="val-bright">50%</span>
                         </div>
                         <div class="flex items-center gap-3 w-64">
                             <span class="text-white text-[10px] font-bold uppercase w-16">Contraste</span>
                             <input type="range" class="flex-1 h-1 bg-white/20 rounded-lg appearance-none cursor-pointer accent-white" oninput="adjustRx(this.value, 'contrast')">
                             <span class="text-white text-[10px] w-8 text-right" id="val-contrast">50%</span>
                         </div>
                    </div>
                </div>

                <!-- TAB 4: HISTORIAL (TIMELINE) -->
                <div id="tab-history" class="tab-content absolute inset-0 hidden bg-slate-50 flex-col overflow-y-auto p-8">
                     <div class="max-w-3xl mx-auto w-full">
                        <div class="relative border-l-2 border-slate-200 pl-8 ml-4 space-y-8">
                            
                            <!-- Timeline Item: Today -->
                            <div class="relative group">
                                <div class="absolute -left-[41px] bg-emerald-500 h-5 w-5 rounded-full border-4 border-white shadow-sm group-hover:scale-125 transition-transform"></div>
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:shadow-md transition-shadow">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-slate-800">Tratamiento Realizado</h4>
                                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">HOY</span>
                                    </div>
                                    <p class="text-sm text-slate-600 mb-3">Se realiza restauración con resina compuesta en pieza 46 (Oclusal). Paciente refiere sensibilidad leve.</p>
                                    <div class="flex gap-2">
                                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded">Dr. Juan Pérez</span>
                                        <span class="text-[10px] font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded">Resina A2</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Timeline Item: Yesterday -->
                            <div class="relative group">
                                <div class="absolute -left-[41px] bg-slate-300 h-5 w-5 rounded-full border-4 border-white shadow-sm"></div>
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 opacity-80 hover:opacity-100 transition-opacity">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-slate-800">Consulta de Urgencia</h4>
                                        <span class="text-[10px] font-bold text-slate-400">Hace 1 día</span>
                                    </div>
                                    <p class="text-sm text-slate-600">Paciente acude por dolor agudo en sector inferior derecho. Se solicita radiografía periapical.</p>
                                </div>
                            </div>
                            
                             <!-- Timeline Item: Past -->
                            <div class="relative group">
                                <div class="absolute -left-[41px] bg-slate-300 h-5 w-5 rounded-full border-4 border-white shadow-sm"></div>
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 opacity-60 hover:opacity-100 transition-opacity">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-bold text-slate-800">Higiene y Profilaxis</h4>
                                        <span class="text-[10px] font-bold text-slate-400">14 Oct 2025</span>
                                    </div>
                                    <p class="text-sm text-slate-600">Limpieza semestral rutinaria. Sin hallazgos patológicos.</p>
                                </div>
                            </div>

                        </div>
                     </div>
                </div>

            </div>
        </div>

        <!-- SIDEBAR 2: Treatment Plan (Fixed Right) -->
        <div class="w-80 bg-white border-l border-slate-200 z-20 flex flex-col shadow-[-5px_0_15px_rgba(0,0,0,0.02)]">
            <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center h-14 shrink-0">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Plan & Presupuesto</h3>
                <button class="text-slate-400 hover:text-slate-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg></button>
            </div>

            <div class="flex-1 overflow-y-auto p-0 scrollbar-thin">
                <!-- Group: Fase 1 -->
                <div class="px-4 py-3 bg-slate-50 border-y border-slate-100">
                    <h4 class="text-[10px] font-black text-slate-400 uppercase">Fase 1: Urgencia</h4>
                </div>
                <!-- Item -->
                <div class="flex gap-3 p-4 border-b border-slate-100 hover:bg-slate-50 transition-colors group cursor-pointer relative">
                    <div class="w-1 bg-emerald-500 absolute left-0 top-0 bottom-0 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="pt-1"><input type="checkbox" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"></div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="text-xs font-bold text-slate-800">Resina Compuesta</span>
                            <span class="text-xs font-bold text-emerald-600">$120k</span>
                        </div>
                        <p class="text-[10px] text-slate-500">Pieza 46 (Molar Inf) • Oclusal</p>
                    </div>
                </div>
                <!-- Item -->
                <div class="flex gap-3 p-4 border-b border-slate-100 hover:bg-slate-50 transition-colors group cursor-pointer relative">
                     <div class="w-1 bg-emerald-500 absolute left-0 top-0 bottom-0 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="pt-1"><input type="checkbox" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"></div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-0.5">
                            <span class="text-xs font-bold text-slate-800">Consulta Urgencia</span>
                            <span class="text-xs font-bold text-slate-400 line-through">$50k</span>
                        </div>
                        <p class="text-[10px] text-emerald-600 font-bold">BONIFICADO 100%</p>
                    </div>
                </div>
            </div>

            <!-- Total Footer -->
            <div class="p-5 bg-slate-50 border-t border-slate-200">
                <div class="flex justify-between items-end mb-4">
                    <span class="text-xs font-medium text-slate-500">Total Estimado</span>
                    <span class="text-2xl font-black text-slate-800 tracking-tight">$ 120.000</span>
                </div>
                <button class="w-full py-3.5 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-black shadow-lg hover:shadow-xl transition-all flex justify-center items-center gap-2">
                    <span>💳</span> Procesar Pago
                </button>
            </div>
        </div>

    </div>

    <!-- ANAMNESIS SLIDE-OVER (Medical History) -->
    <div id="anamnesis-panel" class="fixed inset-0 z-50 hidden">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity opacity-0" id="anamnesis-backdrop" onclick="toggleAnamnesis()"></div>
        
        <!-- Panel -->
        <div class="absolute right-0 top-0 bottom-0 w-full max-w-md bg-white shadow-2xl transform translate-x-full transition-transform duration-300 flex flex-col" id="anamnesis-content">
            <!-- Header -->
            <div class="p-5 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <div>
                    <h3 class="font-black text-slate-800 text-lg">Historia Médica</h3>
                    <p class="text-xs text-slate-500">Antecedentes Clínicos y Alergias</p>
                </div>
                <button onclick="toggleAnamnesis()" class="text-slate-400 hover:text-slate-600 bg-white p-2 rounded-full shadow-sm hover:shadow transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Content -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6">
                
                <!-- Alert Box -->
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <div class="shrink-0">
                            <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-800 font-bold">ALERTA MÉDICA</p>
                            <p class="text-xs text-red-700 mt-1">Paciente alérgico a <span class="font-black">PENICILINA</span> y <span class="font-black">LATEX</span>.</p>
                        </div>
                    </div>
                </div>

                <!-- Togglable Sections -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b pb-2">Enfermedades Sistémicas</h4>
                    
                    <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">❤️</span>
                            <span class="text-sm font-bold text-slate-700">Hipertensión Arterial</span>
                        </div>
                        <input type="checkbox" class="w-5 h-5 text-emerald-500 rounded focus:ring-emerald-500 border-slate-300">
                    </label>

                    <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🩸</span>
                            <span class="text-sm font-bold text-slate-700">Diabetes Mellitus</span>
                        </div>
                        <input type="checkbox" class="w-5 h-5 text-emerald-500 rounded focus:ring-emerald-500 border-slate-300">
                    </label>
                    
                    <label class="flex items-center justify-between p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">🌬️</span>
                            <span class="text-sm font-bold text-slate-700">Asma / Respiratorio</span>
                        </div>
                        <input type="checkbox" checked class="w-5 h-5 text-emerald-500 rounded focus:ring-emerald-500 border-slate-300">
                    </label>
                </div>

                <!-- Open Fields -->
                <div class="space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest border-b pb-2">Medicación Actual</h4>
                    <textarea class="w-full text-sm p-3 border border-slate-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-slate-50" rows="3" placeholder="Escriba los medicamentos que toma el paciente..."></textarea>
                </div>

            </div>
            
            <!-- Footer Actions -->
            <div class="p-5 border-t border-slate-100 bg-slate-50 flex gap-3">
                <button onclick="toggleAnamnesis()" class="flex-1 py-3 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl text-xs hover:bg-slate-50 transition-all">Cancelar</button>
                <button onclick="toggleAnamnesis()" class="flex-1 py-3 bg-slate-900 text-white font-bold rounded-xl text-xs hover:bg-black shadow-lg transition-all">💾 Guardar Cambios</button>
            </div>
    </div>

    <!-- TOOTH CONTEXT MENU (Smart Popup) -->
    <div id="tooth-ctx-menu" class="fixed z-40 hidden w-64 bg-white/95 backdrop-blur rounded-xl shadow-2xl border border-slate-200 transform transition-all scale-95 opacity-0 origin-top-left font-sans">
        <!-- Header -->
        <div class="p-3 border-b border-slate-100 bg-slate-50 rounded-t-xl flex justify-between items-center">
            <div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Pieza Dental</span>
                <h4 class="text-lg font-black text-slate-800" id="ctx-tooth-id">18</h4>
            </div>
            <button onclick="closeToothMenu()" class="text-slate-400 hover:text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        
        <!-- Actions Grid -->
        <div class="grid grid-cols-2 gap-1 p-2">
            <button class="flex flex-col items-center gap-1 p-2 hover:bg-blue-50 rounded-lg group transition-colors">
                <span class="text-xl group-hover:scale-110 transition-transform">🕒</span>
                <span class="text-[10px] font-bold text-slate-600 group-hover:text-blue-600">Historial</span>
            </button>
            <button class="flex flex-col items-center gap-1 p-2 hover:bg-purple-50 rounded-lg group transition-colors">
                <span class="text-xl group-hover:scale-110 transition-transform">📷</span>
                <span class="text-[10px] font-bold text-slate-600 group-hover:text-purple-600">Foto</span>
            </button>
            <button class="flex flex-col items-center gap-1 p-2 hover:bg-amber-50 rounded-lg group transition-colors">
                <span class="text-xl group-hover:scale-110 transition-transform">📝</span>
                <span class="text-[10px] font-bold text-slate-600 group-hover:text-amber-600">Nota</span>
            </button>
            <button class="flex flex-col items-center gap-1 p-2 hover:bg-emerald-50 rounded-lg group transition-colors">
                <span class="text-xl group-hover:scale-110 transition-transform">💎</span>
                <span class="text-[10px] font-bold text-slate-600 group-hover:text-emerald-600">Detalles</span>
            </button>
        </div>
        
        <!-- Footer Status -->
        <div class="p-2 border-t border-slate-100 bg-slate-50/50 rounded-b-xl">
             <div class="flex items-center gap-2">
                 <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                 <span class="text-[10px] font-bold text-slate-500">Estado: Sano</span>
             </div>
        </div>
    </div>
<!-- JAVASCRIPT LOGIC -->
    <script>
        // Configuración Dental PRO 4.0 (Hi-Fi Glossy Engine)
        let currentTool = 'cursor';
        let currentSystem = 'FDI'; // 'FDI' (ISO) or 'ADA' (Universal)

        // FDI (ISO 3950) Mapping - Standard for LatAm/Europe
        const fdiMapping = {
            1: [18, 17, 16, 15, 14, 13, 12, 11],
            2: [21, 22, 23, 24, 25, 26, 27, 28],
            4: [48, 47, 46, 45, 44, 43, 42, 41],
            3: [31, 32, 33, 34, 35, 36, 37, 38]
        };

        // ADA (Universal) Mapping - Standard for USA
        // Q1: 1-8 (Right to Left), Q2: 9-16 (Left to Right)
        // Q4: 32-25 (Right to Left), Q3: 24-17 (Left to Right)
        // NOTE: Visual order in quadrants needs to match loop order.
        const adaMapping = {
            1: [1, 2, 3, 4, 5, 6, 7, 8],          // 18->1, 17->2 ... 11->8
            2: [9, 10, 11, 12, 13, 14, 15, 16],   // 21->9 ... 28->16
            4: [32, 31, 30, 29, 28, 27, 26, 25],  // 48->32 ... 41->25
            3: [24, 23, 22, 21, 20, 19, 18, 17]   // 31->24 ... 38->17
        };

        const toothPaths = {
            molar: {
                top: "M20 30 C35 22, 65 22, 80 30 L75 45 L25 45 Z", 
                bottom: "M25 75 L75 75 L80 90 C65 98, 35 98, 20 90 Z",
                left: "M20 30 L25 45 L25 75 L20 90 C10 70, 10 50, 20 30 Z",
                right: "M80 30 C90 50, 90 70, 80 90 L75 75 L75 45 Z",
                center: "M25 45 L75 45 L75 75 L25 75 Z"
            },
            incisor: { 
                top: "M30 30 C40 25, 60 25, 70 30 L65 45 L35 45 Z",
                bottom: "M35 75 L65 75 L70 90 C60 95, 40 95, 30 90 Z",
                left: "M30 30 L35 45 L35 75 L30 90 C25 70, 25 50, 30 30 Z",
                right: "M70 30 C75 50, 75 70, 70 90 L65 75 L65 45 Z",
                center: "M35 45 L65 45 L65 75 L35 75 Z"
            }
        };

        function initOdontogram() {
            // Inject Hi-Fi Definitions if not present
            if(!document.getElementById('dental-defs')) {
                const defs = `
                <svg width="0" height="0" class="absolute">
                    <defs id="dental-defs">
                        <radialGradient id="enamel-grad" cx="30%" cy="30%" r="90%">
                            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:1" />
                            <stop offset="80%" style="stop-color:#e2e8f0;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#cbd5e1;stop-opacity:1" />
                        </radialGradient>
                        <radialGradient id="caries-grad" cx="50%" cy="50%" r="50%">
                            <stop offset="50%" style="stop-color:#ef4444;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#991b1b;stop-opacity:1" />
                        </radialGradient>
                        <linearGradient id="resin-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#60a5fa;stop-opacity:1" />
                            <stop offset="50%" style="stop-color:#3b82f6;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#1d4ed8;stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="gold-grad" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" style="stop-color:#fde047;stop-opacity:1" />
                            <stop offset="40%" style="stop-color:#eab308;stop-opacity:1" />
                            <stop offset="60%" style="stop-color:#a16207;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#fde047;stop-opacity:1" />
                        </linearGradient>
                        <filter id="tooth-shadow" x="-20%" y="-20%" width="140%" height="140%">
                            <feDropShadow dx="1" dy="2" stdDeviation="1" flood-color="#0f172a" flood-opacity="0.3"/>
                        </filter>
                    </defs>
                </svg>`;
                document.body.insertAdjacentHTML('beforeend', defs);
            }

            renderTeeth();
            setTool('cursor');
        }

        function toggleNotation() {
            currentSystem = currentSystem === 'FDI' ? 'ADA' : 'FDI';
            const btn = document.getElementById('notation-btn');
            if(btn) {
                btn.innerText = currentSystem === 'FDI' ? 'FDI (ISO)' : 'ADA (USA)';
                btn.classList.toggle('text-emerald-400');
                btn.classList.toggle('text-blue-400');
            }
            renderTeeth();
        }

        function renderTeeth() {
             const mapping = currentSystem === 'FDI' ? fdiMapping : adaMapping;
             
             for (let q = 1; q <= 4; q++) {
                const container = document.getElementById(`quadrant-${q}`);
                if(container) {
                    container.innerHTML = ''; 
                    mapping[q].forEach(toothId => {
                        // Determine Molar vs Incisor based on position index, not ID, as IDs change
                        // FDI logic: x4, x5, x6, x7, x8 are molars/premolars. x1, x2, x3 are incisors/canines.
                        // We can deduce index from the array.
                        // Index 0-4 are molars (back), 5-7 are incisors (front) in the array order for rendering?
                        // Wait, fdiMapping keys match visual order?
                        // Q1 (18..11): 18,17,16,15,14 are molars/premolars. 13,12,11 incisors.
                        // Let's use specific sets for visual accuracy regardless of ID numbering
                        
                        // Simplest Hack: If ID > 30 (ADA) or ID % 10 > 3 (FDI), treat as broad tooth (molar style)
                        let isMolar = false;
                        if(currentSystem === 'FDI') {
                             isMolar = (toothId % 10) >= 4;
                        } else {
                             // ADA Molars: 1-5, 12-16, 17-21, 28-32. Incisors: 6-11, 22-27
                             isMolar = (toothId <= 5) || (toothId >= 12 && toothId <= 21) || (toothId >= 28);
                        }

                        const type = isMolar ? 'molar' : 'incisor';
                        const toothHtml = createAnatomicTooth(toothId, type);
                        container.innerHTML += toothHtml;
                    });
                }
            }
        }

        function createAnatomicTooth(id, type) {
            const paths = toothPaths[type];
            return `
            <div class="flex flex-col items-center gap-1 group cursor-pointer relative transition-transform duration-200 hover:-translate-y-1" onclick="handleToothClick(${id})">
                <span class="text-[9px] font-bold text-slate-500 group-hover:text-blue-400 transition-colors">${id}</span>
                <svg width="${type === 'molar' ? 44 : 36}" height="52" viewBox="0 0 100 120" class="tooth-svg drop-shadow-xl" id="tooth-${id}" style="overflow: visible;">
                    <g transform="translate(0,10)">
                        <path d="M20 90 C20 115, 30 125, 50 115 C70 125, 80 115, 80 90" fill="none" stroke="#64748b" stroke-width="2" class="opacity-0 group-hover:opacity-40 transition-opacity" />
                        <path d="${paths.top}"    class="tooth-part transition-all duration-300" fill="url(#enamel-grad)" stroke="#94a3b8" stroke-width="0.5" filter="url(#tooth-shadow)" data-face="top"    onclick="markFace(event, ${id}, 'top')"></path>
                        <path d="${paths.bottom}" class="tooth-part transition-all duration-300" fill="url(#enamel-grad)" stroke="#94a3b8" stroke-width="0.5" filter="url(#tooth-shadow)" data-face="bottom" onclick="markFace(event, ${id}, 'bottom')"></path>
                        <path d="${paths.left}"   class="tooth-part transition-all duration-300" fill="url(#enamel-grad)" stroke="#94a3b8" stroke-width="0.5" filter="url(#tooth-shadow)" data-face="left"   onclick="markFace(event, ${id}, 'left')"></path>
                        <path d="${paths.right}"  class="tooth-part transition-all duration-300" fill="url(#enamel-grad)" stroke="#94a3b8" stroke-width="0.5" filter="url(#tooth-shadow)" data-face="right"  onclick="markFace(event, ${id}, 'right')"></path>
                        <path d="${paths.center}" class="tooth-part transition-all duration-300" fill="url(#enamel-grad)" stroke="#94a3b8" stroke-width="0.5" filter="url(#tooth-shadow)" data-face="center" onclick="markFace(event, ${id}, 'center')"></path>
                    </g>
                </svg>
            </div>
            `;
        }
        
        // ... switchTab, adjustRx, checkPerio unchanged ...

        function handleToothClick(id) {
             // Stop if event is passed (it might not be if called manually, but onclick passes raw function)
             // We need the event to position. Hack: use window.event
             const e = window.event;
             if(e) e.stopPropagation();

             if(currentTool !== 'cursor') return;

             // Open Context Menu
             const menu = document.getElementById('tooth-ctx-menu');
             const toothIdLabel = document.getElementById('ctx-tooth-id');
             
             if(menu && toothIdLabel) {
                 toothIdLabel.innerText = id;
                 
                 // Position Logic
                 // We want it slightly offset from the cursor
                 const x = e.clientX + 20;
                 const y = e.clientY - 20;
                 
                 menu.style.left = `${x}px`;
                 menu.style.top = `${y}px`;
                 
                 // Show
                 menu.classList.remove('hidden');
                 // Small delay for transition
                 requestAnimationFrame(() => {
                     menu.classList.remove('scale-95', 'opacity-0');
                     menu.classList.add('scale-100', 'opacity-100');
                 });
             }
        }

        function closeToothMenu() {
            const menu = document.getElementById('tooth-ctx-menu');
            if(menu) {
                menu.classList.add('scale-95', 'opacity-0');
                menu.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => menu.classList.add('hidden'), 150);
            }
        }

        // Close menu on outside click
        document.addEventListener('click', (e) => {
            const menu = document.getElementById('tooth-ctx-menu');
            if(menu && !menu.classList.contains('hidden') && !menu.contains(e.target)) {
                closeToothMenu();
            }
        });

        function setTool(tool) {
            currentTool = tool;
            closeToothMenu(); // Close menu if changing tools
            
            // Visual Update
            document.querySelectorAll('.tool-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-slate-700', 'text-slate-300', 'ring-2', 'ring-emerald-500');
                btn.classList.add('text-slate-400');
            });
            const activeBtn = document.getElementById(`btn-${tool}`);
            if(activeBtn) {
                 activeBtn.classList.add('active', 'bg-slate-700', 'text-slate-300', 'ring-2', 'ring-emerald-500');
                 activeBtn.classList.remove('text-slate-400', 'hover:scale-110');
            }
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(el => {
                el.classList.add('hidden');
                el.classList.remove('flex');
            });
            const activeTab = document.getElementById(`tab-${tab}`);
            if(activeTab) {
                activeTab.classList.remove('hidden');
                activeTab.classList.add('flex');
            }
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-white', 'shadow', 'text-slate-800', 'font-bold');
                btn.classList.add('text-slate-500', 'font-medium');
            });
            const activeBtn = document.getElementById(`btn-tab-${tab}`);
            if(activeBtn) {
                activeBtn.classList.add('active', 'bg-white', 'shadow', 'text-slate-800', 'font-bold');
                activeBtn.classList.remove('text-slate-500', 'font-medium');
            }
        }

        function adjustRx(val, type) {
            const miniImg = document.getElementById('rx-mini-image');
            if(miniImg) {
                if(type === 'brightness') miniImg.style.filter = `grayscale(1) brightness(${val/50})`;
                if(type === 'contrast') miniImg.style.filter = `grayscale(1) contrast(${val/50})`;
            }
            const fullImg = document.getElementById('rx-main-image');
            if(fullImg) {
                const b = document.querySelector('input[oninput*="brightness"]').value;
                const c = document.querySelector('input[oninput*="contrast"]').value;
                fullImg.style.filter = `brightness(${b}%) contrast(${c}%) grayscale(1)`;
                if(type === 'brightness') document.getElementById('val-bright').innerText = val + '%';
                if(type === 'contrast') document.getElementById('val-contrast').innerText = val + '%';
            }
        }
        
        function checkPerio(input) {
            const val = parseInt(input.value);
            if(val > 3) {
                input.classList.add('bg-red-50', 'text-red-600', 'font-black');
                input.classList.remove('bg-white');
            } else {
                input.classList.remove('bg-red-50', 'text-red-600', 'font-black');
                input.classList.add('bg-white');
            }
        }

        // Anamnesis Toggle Logic
        function toggleAnamnesis() {
            const panel = document.getElementById('anamnesis-panel');
            const backdrop = document.getElementById('anamnesis-backdrop');
            const content = document.getElementById('anamnesis-content');
            
            if(panel.classList.contains('hidden')) {
                // Open
                panel.classList.remove('hidden');
                setTimeout(() => {
                    backdrop.classList.remove('opacity-0');
                    content.classList.remove('translate-x-full');
                }, 10);
            } else {
                // Close
                backdrop.classList.add('opacity-0');
                content.classList.add('translate-x-full');
                setTimeout(() => {
                    panel.classList.add('hidden');
                }, 300);
            }
        }

        // --- NEW PATIENT MODAL LOGIC ---
        function openNewPatientModal() {
            document.getElementById('new-patient-modal').classList.remove('hidden');
            document.getElementById('new-patient-modal').classList.add('flex');
        }

        function closeNewPatientModal() {
            document.getElementById('new-patient-modal').classList.add('hidden');
            document.getElementById('new-patient-modal').classList.remove('flex');
        }

        function saveNewPatient() {
            const btn = document.getElementById('save-patient-btn');
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span>⏳</span> Guardando...';

            const data = {
                first_name: document.getElementById('p_first_name').value,
                last_name: document.getElementById('p_last_name').value,
                identification: document.getElementById('p_identification').value,
                id_type: document.getElementById('p_id_type').value,
                contact_number: document.getElementById('p_phone').value,
                email: document.getElementById('p_email').value,
                gender: document.getElementById('p_gender').value,
                birthday: document.getElementById('p_birthday').value,
                type: 'paciente'
            };

            fetch('{{ url("admin/clientes/store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    showToast('Paciente registrado en el ecosistema', 'success');
                    setTimeout(() => location.reload(), 1200);
                } else {
                    showNeuraModal({
                        title: 'Error de Registro',
                        message: 'No pudimos guardar los datos: ' + data.error,
                        type: 'danger'
                    });
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Falla de conexión con el servidor', 'error');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }

        document.addEventListener('DOMContentLoaded', initOdontogram);
    </script>

    <!-- NEW PATIENT MODAL (Professional Style) -->
    <div id="new-patient-modal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-xl" onclick="closeNewPatientModal()"></div>
        <div class="relative bg-white w-full max-w-2xl rounded-[40px] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300 border border-white/20">
            <!-- Header -->
            <div class="bg-slate-50 p-8 border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight leading-none">Alta de Paciente</h3>
                    <p class="text-[10px] text-emerald-500 font-bold uppercase tracking-widest mt-2 italic">Registro en Base de Datos Principal</p>
                </div>
                <button onclick="closeNewPatientModal()" class="w-12 h-12 flex items-center justify-center rounded-2xl hover:bg-slate-200 transition-colors text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Form Body -->
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Name Cluster -->
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-4 block">Nombres <span class="text-emerald-500">*</span></label>
                    <input type="text" id="p_first_name" placeholder="Ej: Juan" class="w-full bg-slate-50 border border-slate-100 rounded-[20px] px-6 py-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none shadow-inner">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-4 block">Apellidos <span class="text-emerald-500">*</span></label>
                    <input type="text" id="p_last_name" placeholder="Ej: Martinez" class="w-full bg-slate-50 border border-slate-100 rounded-[20px] px-6 py-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none shadow-inner">
                </div>

                <!-- Identification -->
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-4 block">Tipo Documento</label>
                    <select id="p_id_type" class="w-full bg-slate-50 border border-slate-100 rounded-[20px] px-6 py-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none shadow-inner appearance-none">
                        <option value="CC">Cédula de Ciudadanía</option>
                        <option value="TI">Tarjeta de Identidad</option>
                        <option value="CE">Cédula de Extranjería</option>
                        <option value="PA">Pasaporte</option>
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-4 block">Número</label>
                    <input type="text" id="p_identification" class="w-full bg-slate-50 border border-slate-100 rounded-[20px] px-6 py-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none shadow-inner">
                </div>

                <!-- Contact -->
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-4 block">Móvil <span class="text-emerald-500">*</span></label>
                    <input type="tel" id="p_phone" placeholder="300 000 0000" class="w-full bg-slate-50 border border-slate-100 rounded-[20px] px-6 py-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none shadow-inner">
                </div>
                <div class="space-y-1">
                    <label class="text-[10px] font-black text-slate-400 uppercase ml-4 block">Email</label>
                    <input type="email" id="p_email" placeholder="paciente@mail.com" class="w-full bg-slate-50 border border-slate-100 rounded-[20px] px-6 py-4 text-sm font-bold focus:bg-white focus:ring-4 focus:ring-emerald-100 transition-all outline-none shadow-inner">
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-slate-50 p-8 flex gap-4">
                <button onclick="closeNewPatientModal()" class="flex-1 py-5 text-slate-400 font-bold text-xs uppercase tracking-widest hover:bg-slate-200 rounded-[24px] transition-all">Cancelar</button>
                <button id="save-patient-btn" onclick="saveNewPatient()" class="flex-[2] py-5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black text-xs uppercase tracking-widest hover:scale-[1.02] active:scale-95 rounded-[24px] shadow-xl shadow-emerald-500/30 transition-all flex items-center justify-center gap-3">
                    <span>💾</span> Guardar Paciente en Sistema
                </button>
            </div>
        </div>
    </div>
@endsection
