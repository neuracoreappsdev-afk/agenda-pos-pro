@extends('admin/dashboard_layout')

@section('content')

<div style="max-width: 600px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px;">
        <a href="{{ url('admin/specialists') }}" style="color: var(--text-muted); text-decoration: none; font-size: 13px;">
            <i class="fas fa-arrow-left"></i> Volver a Colaboradores
        </a>
        <h1 style="font-size: 24px; font-weight: 700; margin: 10px 0 0 0;">Editar Colaborador</h1>
    </div>

    <div style="background: white; border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 24px;">
        <form action="{{ url('admin/specialists/'.$specialist->id.'/update') }}" method="POST">
            {!! csrf_field() !!}

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 8px;">Nombre Completo</label>
                <input type="text" name="name" value="{{ $specialist->name }}" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-subtle); border-radius: 6px;" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 8px;">Cargo / Título</label>
                <input type="text" name="title" value="{{ $specialist->title }}" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-subtle); border-radius: 6px;" required>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 600; font-size: 13px; margin-bottom: 8px;">URL del Avatar</label>
                <input type="text" name="avatar" value="{{ $specialist->avatar }}" class="form-control" style="width: 100%; padding: 10px; border: 1px solid var(--border-subtle); border-radius: 6px;">
            </div>

            <div style="text-align: right; margin-top: 30px;">
                <button type="submit" class="btn btn-primary" style="padding: 10px 24px;">Actualizar Colaborador</button>
            </div>

        </form>
    </div>

</div>

@endsection
