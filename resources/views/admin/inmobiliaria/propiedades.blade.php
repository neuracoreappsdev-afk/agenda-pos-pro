@extends('admin/dashboard_layout')

@section('content')
<div class="px-8 py-6 max-w-[1600px] mx-auto">
    <!-- Header: Zillow Style -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4 border-b border-slate-100 pb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 flex items-center gap-3">
                <span class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">🏠</span>
                Panel de Propiedades
            </h1>
            <p class="text-slate-500 text-sm mt-1">Sincronizado con portales inmobiliarios externos</p>
        </div>
        <div class="flex gap-3">
            <div class="flex bg-slate-100 p-1 rounded-xl">
                <button class="px-4 py-2 bg-white shadow-sm rounded-lg text-xs font-bold text-slate-700">Explorador</button>
                <button class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Mapa</button>
                <button class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700">Análisis</button>
            </div>
            <button class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all flex items-center gap-2">
                <span>+</span> Nueva Propiedad
            </button>
        </div>
    </div>

    <!-- Filter Bar: Wasi Style (Clean, horizontal) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 mb-8 flex flex-wrap gap-4 items-center">
        <div class="flex-1 min-w-[200px] relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 opacity-30">🔍</span>
            <input type="text" placeholder="Buscar por dirección, ciudad o ID..." class="w-full pl-12 pr-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20">
        </div>
        <select class="px-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-medium text-slate-600">
            <option>Tipo: Todos</option>
            <option>Apartamentos</option>
            <option>Casas</option>
            <option>Oficinas</option>
        </select>
        <select class="px-4 py-2.5 bg-slate-50 border-none rounded-xl text-sm font-medium text-slate-600">
            <option>Estado: Activo</option>
            <option>Vendido</option>
            <option>Alquilado</option>
        </select>
        <button class="p-2.5 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">⚙️</button>
    </div>

    <!-- Properties Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        @php
            $properties = [
                ['name' => 'Apartamento Penthouse', 'loc' => 'Zona Norte, Bogotá', 'price' => '$1.200M', 'beds' => 3, 'baths' => 4, 'size' => '180m²', 'status' => 'Activo', 'color' => 'bg-emerald-500'],
                ['name' => 'Casa Campestre', 'loc' => 'Vía El Retiro, Medellín', 'price' => '$2.500M', 'beds' => 5, 'baths' => 6, 'size' => '450m²', 'status' => 'Vendido', 'color' => 'bg-slate-400'],
                ['name' => 'Oficina Prime', 'loc' => 'Edf. Empresarial Cali', 'price' => '$850M', 'beds' => 0, 'baths' => 2, 'size' => '90m²', 'status' => 'En Negociación', 'color' => 'bg-amber-500'],
                ['name' => 'Loft Industrial', 'loc' => 'Barrio Prado, Barranquilla', 'price' => '$450M', 'beds' => 1, 'baths' => 1, 'size' => '65m²', 'status' => 'Activo', 'color' => 'bg-emerald-500'],
            ];
        @endphp

        @foreach($properties as $p)
        <div class="bg-white rounded-[24px] border border-slate-100 overflow-hidden group hover:shadow-2xl hover:shadow-emerald-500/10 transition-all duration-500 hover:-translate-y-1">
            <div class="relative h-48 bg-slate-200 overflow-hidden">
                <div class="absolute top-4 right-4 z-10 px-3 py-1 rounded-full bg-white/90 backdrop-blur-md text-[10px] font-black uppercase text-slate-800"> {{ $p['status'] }} </div>
                <div class="absolute bottom-4 left-4 z-10 text-white font-black text-xl drop-shadow-md"> {{ $p['price'] }} </div>
                <!-- Placeholder for actual image -->
                <div class="w-full h-full bg-slate-900 group-hover:scale-110 transition-transform duration-700 opacity-80"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
            </div>
            <div class="p-6">
                <h3 class="font-bold text-slate-800 mb-1 line-clamp-1"> {{ $p['name'] }} </h3>
                <p class="text-slate-400 text-xs mb-4 flex items-center gap-1">📍 {{ $p['loc'] }} </p>
                <div class="grid grid-cols-3 gap-2 border-t border-slate-50 pt-4">
                    <div class="text-center">
                        <span class="block text-[10px] font-bold text-slate-300 uppercase">Hab</span>
                        <span class="text-sm font-bold text-slate-700"> {{ $p['beds'] }} </span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[10px] font-bold text-slate-300 uppercase">Baños</span>
                        <span class="text-sm font-bold text-slate-700"> {{ $p['baths'] }} </span>
                    </div>
                    <div class="text-center">
                        <span class="block text-[10px] font-bold text-slate-300 uppercase">Area</span>
                        <span class="text-sm font-bold text-slate-700"> {{ $p['size'] }} </span>
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex gap-2">
                <button class="flex-1 py-2 text-xs font-bold text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors">Editar</button>
                <button class="p-2 text-slate-400 hover:text-slate-600 transition-colors">🔗</button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
