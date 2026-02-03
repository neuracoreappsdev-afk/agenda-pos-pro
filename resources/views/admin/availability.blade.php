@extends('admin/dashboard_layout')

@section('content')

<div style="max-width: 800px; margin: 0 auto;">
    
    <div style="margin-bottom: 20px;">
        <h1 style="font-size: 24px; font-weight: 700; margin: 0;">Disponibilidad</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Define los horarios generales de atención.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #047857; padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 13px;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); overflow: hidden;">
        <form action="{{ url('admin/availability') }}" method="POST">
            {!! csrf_field() !!}
            
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-panel); border-bottom: 1px solid var(--border-subtle);">
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Día</th>
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Estado</th>
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Apertura</th>
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 600; color: var(--text-muted); text-transform: uppercase;">Cierre</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($days as $day)
                    <tr style="border-bottom: 1px solid var(--border-subtle);">
                        <td style="padding: 16px 24px; font-weight: 600; text-transform: capitalize;">
                            {{ trans($day) }} <!-- Traducción simple si existe, sino imprime en inglés -->
                        </td>
                        <td style="padding: 16px 24px;">
                            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                <input type="checkbox" name="{{ $day }}_active" value="1" {{ $settings[$day.'_active'] == '1' ? 'checked' : '' }}>
                                <span style="font-size: 13px;">Abierto</span>
                            </label>
                        </td>
                        <td style="padding: 16px 24px;">
                            <input type="time" name="{{ $day }}_start" value="{{ $settings[$day.'_start'] }}" class="form-control" style="padding: 6px; border: 1px solid var(--border-subtle); border-radius: 4px;">
                        </td>
                        <td style="padding: 16px 24px;">
                            <input type="time" name="{{ $day }}_end" value="{{ $settings[$day.'_end'] }}" class="form-control" style="padding: 6px; border: 1px solid var(--border-subtle); border-radius: 4px;">
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="padding: 20px 24px; background: var(--bg-panel); border-top: 1px solid var(--border-subtle); text-align: right;">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>

</div>

@endsection