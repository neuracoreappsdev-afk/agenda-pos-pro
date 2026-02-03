@extends('admin/dashboard_layout')

@section('content')
<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Historias Clínicas</h1>
            <p class="text-slate-500 font-medium">Gestión profesional de pacientes y registros médicos.</p>
        </div>
        
        <form action="{{ url('admin/historias-clinicas') }}" method="GET" class="flex gap-2">
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o documento..." 
                class="px-4 py-2 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 focus:outline-none w-64 md:w-80">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold hover:bg-indigo-700 transition-all">
                Buscar
            </button>
        </form>
    </div>

    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Paciente / Documento</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Contacto</th>
                    <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($customers as $customer)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-indigo-50 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                                {{ substr($customer->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-slate-800">{{ $customer->name }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium">{{ $customer->document_id ?: 'Sin ID' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <p class="text-xs font-bold text-slate-600">{{ $customer->phone ?: 'N/A' }}</p>
                        <p class="text-[10px] text-slate-400">{{ $customer->email ?: 'N/A' }}</p>
                    </td>
                    <td class="px-6 py-5 text-right">
                        <a href="{{ url('admin/historias-clinicas/'.$customer->id) }}" 
                           class="inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 px-4 py-2 rounded-xl font-black text-xs uppercase tracking-wider hover:bg-indigo-600 hover:text-white transition-all shadow-sm">
                           📂 Abrir Historia
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($customers->isEmpty())
        <div class="p-20 text-center">
            <div class="text-5xl mb-4">🔍</div>
            <h3 class="text-lg font-bold text-slate-800">No se encontraron pacientes</h3>
            <p class="text-slate-400">Intenta con otro nombre o número de documento.</p>
        </div>
        @endif
    </div>

    <div class="mt-6">
        {!! $customers->appends(['search' => $search])->render() !!}
    </div>
</div>

<style>
    .pagination { display: flex; gap: 5px; justify-content: center; }
    .pagination li { list-style: none; }
    .pagination li a, .pagination li span { 
        padding: 8px 16px; border-radius: 12px; background: white; border: 1px solid #e2e8f0;
        font-weight: 700; color: #64748b; font-size: 13px;
    }
    .pagination li.active span { background: #4f46e5; color: white; border-color: #4f46e5; }
</style>
@endsection
