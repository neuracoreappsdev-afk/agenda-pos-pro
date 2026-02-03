@extends('admin/dashboard_layout')

@section('content')
<div class="page-container" style="padding: 25px; background: #f8fafc; min-height: 100vh;">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">🔎 Gestión de Pacientes Óptica</h1>
            <p class="text-slate-500 mt-1">Selecciona un paciente para gestionar su fórmula visual</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ url('admin/clientes') }}" class="btn-secondary flex items-center gap-2">
                <span>➕</span> Nuevo Paciente
            </a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-100 mb-6">
        <form action="{{ url('admin/formulas-opticas') }}" method="GET" class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}" 
                   class="flex-1 px-5 py-3 rounded-2xl border border-slate-200 focus:ring-4 focus:ring-blue-100 outline-none transition-all"
                   placeholder="Buscar por nombre, apellido o identificación...">
            <button type="submit" class="bg-slate-800 text-white px-8 py-3 rounded-2xl font-bold hover:bg-slate-700 transition-all">
                Buscar
            </button>
        </form>
    </div>

    <!-- Client Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($clients as $client)
        <div class="bg-white rounded-[32px] p-6 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex items-center gap-4 mb-5">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-blue-100">
                    {{ substr($client->first_name, 0, 1) }}{{ substr($client->last_name, 0, 1) }}
                </div>
                <div>
                    <h3 class="font-bold text-slate-800 text-lg group-hover:text-blue-600 transition-colors">{{ $client->first_name }} {{ $client->last_name }}</h3>
                    <p class="text-xs text-slate-400 font-medium uppercase tracking-wider">{{ $client->identification ?: 'Sin ID' }}</p>
                </div>
            </div>

            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400">Teléfono:</span>
                    <span class="text-slate-700 font-semibold">{{ $client->phone ?: 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-400">Último Examen:</span>
                    <span class="text-blue-600 font-bold px-2 py-1 bg-blue-50 rounded-lg">Ver Historial</span>
                </div>
            </div>

            <a href="{{ url('admin/formulas-opticas/'.$client->id) }}" 
               class="block w-full text-center bg-slate-50 text-slate-800 py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all">
                Abrir Fórmula 👓
            </a>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $clients->appends(request()->query())->links() }}
    </div>
</div>

<style>
    .btn-secondary {
        background: white;
        color: #1e293b;
        padding: 12px 24px;
        border-radius: 16px;
        font-weight: 800;
        font-size: 14px;
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }
</style>
@endsection
