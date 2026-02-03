@extends('layout')

@section('title', 'Nuestro equipo')

@section('content')
<div class="space-y-8">
    {{-- Encabezado --}}
    <div class="flex flex-col justify-between gap-4 md:flex-row md:items-end">
        <div>
            <p class="text-[11px] uppercase tracking-[0.2em] text-amber-700/70">
                Equipo Lina Lucio
            </p>
            <h1 class="mt-2 text-3xl font-semibold text-brand-ink md:text-4xl">
                Colaboradoras & fichas técnicas
            </h1>
            <p class="mt-2 max-w-xl text-sm text-slate-600">
                Elige quién quieres que te atienda. Mira su experiencia, especialidades
                y agenda un servicio con la persona que más conecte contigo.
            </p>
        </div>

        <div class="rounded-bento border border-slate-200 bg-white/80 px-4 py-3 text-xs text-slate-600 shadow-soft">
            <p class="font-medium text-brand-ink">¿Cómo funciona?</p>
            <p class="mt-1">
                1) Revisa la ficha · 2) Toca “Ver agenda” · 3) Escoge día y hora · 4) Confirmas tu cita.
            </p>
        </div>
    </div>

    {{-- Grid de colaboradoras --}}
    <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @foreach($collaborators as $collab)
            <article class="group flex flex-col rounded-bento border border-slate-100 bg-white/90 p-5 shadow-soft transition hover:-translate-y-1 hover:shadow-xl">
                {{-- Header: avatar + nombre --}}
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-lg">
                        {{ $collab['initials'] }}
                    </div>
                    <div class="flex-1">
                        <h2 class="text-sm font-semibold text-brand-ink">
                            {{ $collab['name'] }}
                        </h2>
                        <p class="text-xs text-slate-500">
                            {{ $collab['role'] }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">
                            {{ $collab['experience'] }}
                        </p>
                    </div>
                    <div class="text-right text-xs">
                        <p class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-1 text-amber-700">
                            ⭐ {{ $collab['rating'] }}
                        </p>
                        <p class="mt-1 text-[11px] text-slate-400">
                            {{ $collab['clients'] }} clientes felices
                        </p>
                    </div>
                </div>

                {{-- Servicios principales --}}
                <div class="mt-4 space-y-1.5 text-xs">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-slate-400">
                        Especialidades
                    </p>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach($collab['services'] as $service)
                            <span class="rounded-full bg-slate-50 px-2.5 py-1 text-[11px] text-slate-600">
                                {{ $service }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- Próxima disponibilidad --}}
                <div class="mt-4 flex items-center justify-between rounded-2xl bg-slate-50 px-3 py-2 text-[11px] text-slate-600">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-[0.16em] text-slate-400">
                            Próxima disponibilidad
                        </span>
                        <span class="text-xs font-medium text-brand-ink">
                            {{ $collab['next_slot'] }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-500">Duración media</span>
                        <p class="text-xs font-medium text-slate-700">
                            {{ $collab['avg_duration'] }} min
                        </p>
                    </div>
                </div>

                {{-- Botón agenda --}}
                <div class="mt-4 flex items-center justify-between">
                    <p class="text-[11px] text-slate-500">
                        {{ $collab['note'] }}
                    </p>
                    <a href="{{ url('booking/calendar/' . $collab['default_package_id']) }}"
                       class="inline-flex items-center gap-1 rounded-full bg-brand-ink px-3.5 py-1.5 text-xs font-medium text-white shadow-sm transition hover:bg-black">
                        Ver agenda
                        <span aria-hidden="true">→</span>
                    </a>
                </div>
            </article>
        @endforeach
    </div>
</div>
@endsection
