@extends('creator.layout')

@section('content')
<div class="animate-fade">
    <div style="display:flex; justify-content:space-between; align-items:flex-end; margin-bottom:2rem;">
        <div>
            <div class="badge-premium" style="margin-bottom:0.5rem;">Explorador SaaS Master</div>
            <h1 style="font-size:2.5rem; font-weight:800; margin:0; letter-spacing:-1px;">Archivos de {{ $business_name }}</h1>
            <p style="color:#94a3b8; margin-top:5px;">ID de Negocio: #{{ $business_id }} · Ubicación: Centro de Datos Principal</p>
        </div>
        <button class="btn-creator" onclick="window.location.href='{{ url('creator/dashboard') }}'" style="background:rgba(255,255,255,0.1); color:white; border:1px solid var(--glass-border);">
            <i data-lucide="arrow-left" style="vertical-align:middle; margin-right:8px;"></i> Volver al Core
        </button>
    </div>

    <!-- Storage Info -->
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:1.5rem; margin-bottom:2rem;">
        <div class="glass-card">
            <h3 style="margin-top:0; display:flex; align-items:center; gap:10px;">
                <i data-lucide="hard-drive"></i> Infraestructura de Almacenamiento
            </h3>
            <div style="display:flex; gap:20px; margin-top:1.5rem;">
                <div style="flex:1; background:rgba(0,0,0,0.2); padding:1rem; border-radius:16px;">
                    <div style="font-size:0.75rem; color:#64748b;">TOTAL OCUPADO</div>
                    <div style="font-size:1.5rem; font-weight:700;">12.4 GB</div>
                    <div style="width:100%; height:8px; background:#1e293b; border-radius:10px; margin-top:10px; overflow:hidden;">
                        <div style="width:65%; height:100%; background:var(--primary-gradient);"></div>
                    </div>
                </div>
                <div style="flex:1; background:rgba(0,0,0,0.2); padding:1rem; border-radius:16px;">
                    <div style="font-size:0.75rem; color:#64748b;">ARCHIVOS TOTALES</div>
                    <div style="font-size:1.5rem; font-weight:700;">4,820</div>
                    <div style="color:#22c55e; font-size:0.8rem; font-weight:600; margin-top:5px;">Salud del Disco: 100%</div>
                </div>
            </div>
        </div>
        <div class="glass-card" style="display:flex; flex-direction:column; justify-content:center;">
            <button class="btn-creator" style="margin-bottom:0.75rem; width:100%;">
                <i data-lucide="download-cloud"></i> Descargar Backup Full
            </button>
            <button class="btn-creator" style="width:100%; background:#f43f5e;">
                <i data-lucide="trash-2"></i> Purgar Temporales
            </button>
        </div>
    </div>

    <!-- Explorer Grid -->
    <div class="glass-card">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
            <h3 style="margin:0;">Explorador de Archivos de Clientes</h3>
            <div style="display:flex; gap:10px;">
                <input type="text" placeholder="Buscar cliente o archivo..." class="glass-card" style="padding:8px 15px; width:250px; font-size:0.9rem; border-radius:10px;">
                <button class="btn-creator" style="padding:8px 15px;"><i data-lucide="search"></i></button>
            </div>
        </div>

        <style>
            .file-table { width: 100%; border-collapse: collapse; }
            .file-table th { text-align: left; padding: 12px; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 1px solid var(--glass-border); }
            .file-table td { padding: 15px 12px; border-bottom: 1px solid rgba(255,255,255,0.05); }
            .file-row:hover { background: rgba(255,255,255,0.02); }
            .file-icon { width: 35px; height: 35px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: rgba(255,255,255,0.05); color: #94a3b8; }
        </style>

        <table class="file-table">
            <thead>
                <tr>
                    <th>Nombre / Cliente</th>
                    <th>Tipo</th>
                    <th>Tamaño</th>
                    <th>Fecha de Carga</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- File 1: Image -->
                <tr class="file-row">
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="file-icon" style="color:#a855f7;"><i data-lucide="image"></i></div>
                            <div>
                                <div style="font-weight:700;">antes_microblading_001.jpg</div>
                                <div style="font-size:0.75rem; color:#64748b;">Cliente: Maria Paula Velez</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-premium" style="background:rgba(168, 85, 247, 0.2); color:#a855f7;">IMAGEN</span></td>
                    <td>2.4 MB</td>
                    <td>12 Ene 2026, 14:30</td>
                    <td>
                        <div style="display:flex; gap:10px;">
                            <button title="Ver" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i data-lucide="eye"></i></button>
                            <button title="Descargar" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i data-lucide="download"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- File 2: Audio/Voice -->
                <tr class="file-row">
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="file-icon" style="color:#22c55e;"><i data-lucide="mic"></i></div>
                            <div>
                                <div style="font-weight:700;">instrucciones_cuidado.mp3</div>
                                <div style="font-size:0.75rem; color:#64748b;">Especialista: Erika Gomez</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-premium" style="background:rgba(34, 197, 94, 0.2); color:#22c55e;">VOZ</span></td>
                    <td>1.1 MB</td>
                    <td>12 Ene 2026, 11:15</td>
                    <td>
                        <div style="display:flex; gap:10px;">
                            <button title="Escuchar" style="background:none; border:none; color:#22c55e; cursor:pointer;"><i data-lucide="play-circle"></i></button>
                            <button title="Descargar" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i data-lucide="download"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- File 3: Camera Capture -->
                <tr class="file-row">
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="file-icon" style="color:#f472b6;"><i data-lucide="camera"></i></div>
                            <div>
                                <div style="font-weight:700;">test_camara_soporte.png</div>
                                <div style="font-size:0.75rem; color:#64748b;">Soporte Técnico ID: #992</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-premium" style="background:rgba(244, 114, 182, 0.2); color:#f472b6;">CAMARA</span></td>
                    <td>850 KB</td>
                    <td>11 Ene 2026, 18:45</td>
                    <td>
                        <div style="display:flex; gap:10px;">
                            <button title="Ver" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i data-lucide="eye"></i></button>
                            <button title="Eliminar" style="background:none; border:none; color:#f43f5e; cursor:pointer;"><i data-lucide="trash"></i></button>
                        </div>
                    </td>
                </tr>

                <!-- File 4: Video -->
                <tr class="file-row">
                    <td>
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div class="file-icon" style="color:#3b82f6;"><i data-lucide="video"></i></div>
                            <div>
                                <div style="font-weight:700;">demo_recorrido_salon.mp4</div>
                                <div style="font-size:0.75rem; color:#64748b;">Cliente: Carlos Ruiz</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge-premium" style="background:rgba(59, 130, 246, 0.2); color:#3b82f6;">VIDEO</span></td>
                    <td>15.2 MB</td>
                    <td>10 Ene 2026, 09:20</td>
                    <td>
                        <div style="display:flex; gap:10px;">
                            <button title="Ver" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i data-lucide="eye"></i></button>
                            <button title="Descargar" style="background:none; border:none; color:#94a3b8; cursor:pointer;"><i data-lucide="download"></i></button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });
</script>
@endsection
