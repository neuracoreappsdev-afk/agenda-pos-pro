@extends('admin/dashboard_layout')

@section('content')
<div class="page-container" style="padding: 25px; background: #f1f5f9; min-height: 100vh;">
    <!-- Top Nav -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ url('admin/formulas-opticas') }}" class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm hover:bg-slate-50 transition-all border border-slate-200">
            <span class="text-slate-800 font-bold">←</span>
        </a>
        <div>
            <h1 class="text-2xl font-black text-slate-800">Fórmula de Optometría</h1>
            <p class="text-slate-500 font-medium">Paciente: <span class="text-blue-600">{{ $patient->first_name }} {{ $patient->last_name }}</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- New Formula Form -->
        <div class="lg:col-span-8">
            <form action="{{ url('admin/formulas-opticas/save') }}" method="POST" id="formulaForm">
                @csrf
                <input type="hidden" name="customer_id" value="{{ $patient->id }}">
                <input type="hidden" name="formula_data" id="formulaDataInput">

                <div class="bg-white rounded-[40px] shadow-2xl shadow-slate-200/50 border border-white overflow-hidden">
                    <!-- Form Header -->
                    <div class="bg-slate-900 p-8 text-white flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <span class="text-2xl">👁️</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold">Nueva Refracción</h2>
                                <p class="text-slate-400 text-xs uppercase tracking-widest font-bold">Datos de Examen Visual</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-blue-400 font-black text-lg">{{ date('d/m/Y') }}</div>
                            <div class="text-slate-400 text-[10px] font-bold">ESTADO: PENDIENTE</div>
                        </div>
                    </div>

                    <div class="p-8">
                        <!-- OD / OI Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                            <!-- OJO DERECHO (OD) -->
                            <div class="bg-blue-50/50 p-6 rounded-[32px] border border-blue-100">
                                <div class="flex items-center gap-2 mb-6 text-blue-800 font-black uppercase text-xs tracking-tighter">
                                    <span class="w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-[10px]">OD</span>
                                    Ojo Derecho
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Esfera</label>
                                        <input type="text" class="formula-cell od-val" data-field="esfera" placeholder="0.00">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Cilindro</label>
                                        <input type="text" class="formula-cell od-val" data-field="cilindro" placeholder="0.00">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Eje</label>
                                        <input type="text" class="formula-cell od-val" data-field="eje" placeholder="0°">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Adición</label>
                                    <input type="text" class="formula-cell od-val w-full" data-field="adicion" placeholder="+0.00">
                                </div>
                            </div>

                            <!-- OJO IZQUIERDO (OI) -->
                            <div class="bg-purple-50/50 p-6 rounded-[32px] border border-purple-100">
                                <div class="flex items-center gap-2 mb-6 text-purple-800 font-black uppercase text-xs tracking-tighter">
                                    <span class="w-6 h-6 bg-purple-600 text-white rounded-full flex items-center justify-center text-[10px]">OI</span>
                                    Ojo Izquierdo
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Esfera</label>
                                        <input type="text" class="formula-cell oi-val" data-field="esfera" placeholder="0.00">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Cilindro</label>
                                        <input type="text" class="formula-cell oi-val" data-field="cilindro" placeholder="0.00">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Eje</label>
                                        <input type="text" class="formula-cell oi-val" data-field="eje" placeholder="0°">
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Adición</label>
                                    <input type="text" class="formula-cell oi-val w-full" data-field="adicion" placeholder="+0.00">
                                </div>
                            </div>
                        </div>

                        <!-- Lente Suggestion -->
                        <div class="mb-8 p-6 bg-slate-50 rounded-[32px] border border-slate-100">
                            <h3 class="text-sm font-black text-slate-800 uppercase mb-4 tracking-tighter flex items-center gap-2">
                                <span class="text-xl">🛠️</span> Configuración de Lente y Montura
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Tipo de Lente</label>
                                    <select class="formula-select" id="lensType">
                                        <option value="monofocal">Monofocal</option>
                                        <option value="bifocal">Bifocal</option>
                                        <option value="progresivo">Progresivo</option>
                                        <option value="filtro_azul">Filtro Azul / PC</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase ml-2">Distancia Pupilar (DP)</label>
                                    <input type="text" class="formula-cell w-full" id="dpVal" placeholder="62/60">
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-10">
                            <label class="text-sm font-black text-slate-800 uppercase mb-2 block tracking-tighter ml-2">Observaciones Clínicas / Montura Sugerida</label>
                            <textarea name="notes" rows="4" 
                                      class="w-full bg-slate-50 border-none rounded-3xl p-5 focus:ring-4 focus:ring-blue-100 outline-none placeholder-slate-300 font-medium text-slate-700"
                                      placeholder="Escribe aquí las recomendaciones finales o detalles de la montura..."></textarea>
                        </div>

                        <!-- Footer Actions -->
                        <div class="flex gap-4">
                            <button type="button" onclick="saveFormula()" class="flex-1 bg-blue-600 text-white h-16 rounded-2xl font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-600/20 transition-all flex items-center justify-center gap-3">
                                <span>💾</span> Guardar Fórmula
                            </button>
                            <button type="button" class="w-16 h-16 bg-slate-100 text-slate-400 rounded-2xl hover:bg-slate-200 transition-all flex items-center justify-center">
                                <span>🖨️</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- History Sidebar -->
        <div class="lg:col-span-4">
            <div class="bg-white rounded-[40px] p-8 shadow-xl border border-white sticky top-8">
                <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                    <span class="text-blue-500">🕝</span> Historial Visual
                </h3>
                
                <div class="space-y-6">
                    @forelse($records as $rec)
                    @php 
                        $fData = json_decode($rec->formula_data, true);
                    @endphp
                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100 relative group overflow-hidden">
                        <div class="absolute top-0 right-0 w-1 h-full bg-blue-500"></div>
                        <div class="text-[10px] text-slate-400 font-black mb-2">{{ date('d M, Y', strtotime($rec->created_at)) }}</div>
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-slate-800">{{ $fData['lens_type'] ?? 'Desconocido' }}</span>
                            <span class="text-[10px] px-2 py-0.5 bg-blue-100 text-blue-600 rounded-full font-bold">PDF</span>
                        </div>
                        <p class="text-[11px] text-slate-500 line-clamp-2 italic">{{ $rec->notes }}</p>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="text-4xl mb-4">📭</div>
                        <p class="text-slate-400 font-bold text-xs uppercase tracking-widest">Sin antecedentes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .formula-cell {
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px 16px;
        font-weight: 800;
        color: #1e293b;
        font-size: 16px;
        outline: none;
        transition: all 0.2s;
        text-align: center;
    }
    .formula-cell:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .formula-select {
        width: 100%;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 12px 16px;
        font-weight: 800;
        color: #1e293b;
        font-size: 14px;
        outline: none;
        appearance: none;
    }
</style>

<script>
    function saveFormula() {
        const od = {};
        document.querySelectorAll('.od-val').forEach(el => {
            od[el.dataset.field] = el.value;
        });

        const oi = {};
        document.querySelectorAll('.oi-val').forEach(el => {
            oi[el.dataset.field] = el.value;
        });

        const fullData = {
            od: od,
            oi: oi,
            lens_type: document.getElementById('lensType').value,
            distancia_pupilar: document.getElementById('dpVal').value
        };

        document.getElementById('formulaDataInput').value = JSON.stringify(fullData);
        document.getElementById('formulaForm').submit();
    }
</script>
@endsection
