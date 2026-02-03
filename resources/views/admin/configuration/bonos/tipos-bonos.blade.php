@extends('admin.configuration._layout')

@section('config_title', 'Tipos de Bonos')

@section('config_content')

<div class="config-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">Define tipos de bonos y vales que entregan a tus clientes</p>
        <button class="btn btn-primary" onclick="openModal('bonoModal')">➕ Nuevo Tipo</button>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 15px;">
        <div style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; padding: 25px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%;"></div>
            <div style="position: relative;">
                <div style="font-size: 32px; margin-bottom: 10px;">🎁</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 700; font-size: 18px; color: #78350f;">Bono Regalo</h4>
                <p style="margin: 0 0 15px 0; font-size: 13px; color: #92400e;">Para obsequiar a clientes especiales</p>
                <div style="background: rgba(255,255,255,0.5); padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; color: #78350f;">
                    <strong>Valor:</strong> Variable<br>
                    <strong>Vigencia:</strong> 90 días<br>
                    <strong>Activos:</strong> 23 bonos
                </div>
                <button class="btn btn-secondary" onclick="editBono(1)" style="width: 100%;">Editar Tipo</button>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; padding: 25px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%;"></div>
            <div style="position: relative;">
                <div style="font-size: 32px; margin-bottom: 10px;">💝</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 700; font-size: 18px; color: #1e40af;">Bono Promocional</h4>
                <p style="margin: 0 0 15px 0; font-size: 13px; color: #1e40af;">Para campañas y promociones</p>
                <div style="background: rgba(255,255,255,0.5); padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; color: #1e40af;">
                    <strong>Valor:</strong> Fijo $50.000<br>
                    <strong>Vigencia:</strong> 30 días<br>
                    <strong>Activos:</strong> 45 bonos
                </div>
                <button class="btn btn-secondary" onclick="editBono(2)" style="width: 100%;">Editar Tipo</button>
            </div>
        </div>

        <div style="background: linear-gradient(135deg, #fce7f3 0%, #fbcfe8 100%); border-radius: 12px; padding: 25px; position: relative; overflow: hidden;">
            <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(255,255,255,0.2); border-radius: 50%;"></div>
            <div style="position: relative;">
                <div style="font-size: 32px; margin-bottom: 10px;">🎂</div>
                <h4 style="margin: 0 0 8px 0; font-weight: 700; font-size: 18px; color: #9f1239;">Bono Cumpleaños</h4>
                <p style="margin: 0 0 15px 0; font-size: 13px; color: #be123c;">Regalo especial de cumpleaños</p>
                <div style="background: rgba(255,255,255,0.5); padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 13px; color: #9f1239;">
                    <strong>Valor:</strong> 20% Descuento<br>
                    <strong>Vigencia:</strong> Mes cumpleaños<br>
                    <strong>Activos:</strong> 67 bonos
                </div>
                <button class="btn btn-secondary" onclick="editBono(3)" style="width: 100%;">Editar Tipo</button>
            </div>
        </div>
    </div>
</div>

<div id="bonoModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Nuevo Tipo de Bono</h3>
            <button class="modal-close" onclick="closeModal('bonoModal')">×</button>
        </div>
        <form>
            <div class="form-group">
                <label>Nombre del Bono *</label>
                <input type="text" class="form-control" placeholder="Ej: Bono Regalo" required>
            </div>
            <div class="form-group">
                <label>Tipo de Valor</label>
                <select class="form-control">
                    <option>Valor Fijo</option>
                    <option>Porcentaje Descuento</option>
                    <option>Variable</option>
                </select>
            </div>
            <div class="form-group">
                <label>Valor</label>
                <input type="number" class="form-control" placeholder="50000">
            </div>
            <div class="form-group">
                <label>Vigencia (días)</label>
                <input type="number" class="form-control" value="90">
            </div>
            <div class="form-group">
                <label>Color del Bono</label>
                <input type="color" class="form-control" value="#fbbf24">
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('bonoModal')">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Tipo</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) { document.getElementById(id).classList.add('active'); }
function closeModal(id) { document.getElementById(id).classList.remove('active'); }
function editBono(id) { openModal('bonoModal'); }
document.getElementById('bonoModal').addEventListener('click', e => { if(e.target === e.currentTarget) closeModal('bonoModal'); });
</script>
@endsection
