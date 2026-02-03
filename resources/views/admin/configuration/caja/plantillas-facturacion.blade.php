@extends('admin.configuration._layout')

@section('config_title', 'Plantillas de Facturación')

@section('config_content')

<style>
    .template-preview {
        background: white;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 20px;
        position: relative;
        transition: all 0.3s;
    }
    
    .template-preview:hover {
        border-color: #1a73e8;
        box-shadow: 0 8px 30px rgba(26, 115, 232, 0.1);
    }
    
    .template-preview.selected {
        border-color: #1a73e8;
        background: linear-gradient(135deg, #ffffff 0%, #f0f7ff 100%);
    }
    
    .template-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 6px 14px;
        background: #1a73e8;
        color: white;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
    }
    
    .template-header {
        text-align: center;
        padding-bottom: 20px;
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 20px;
    }
    
    .template-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%);
        border-radius: 12px;
        margin: 0 auto 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
    }
    
    .template-company {
        font-size: 22px;
        font-weight: 700;
        color: #1f2937;
        margin: 0 0 5px 0;
    }
    
    .template-info {
        font-size: 13px;
        color: #6b7280;
        margin: 0;
    }
    
    .invoice-preview {
        background: #f9fafb;
        padding: 20px;
        border-radius: 8px;
        font-size: 13px;
    }
    
    .invoice-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #e5e7eb;
    }
    
    .invoice-row:last-child {
        border-bottom: none;
        font-weight: 600;
        font-size: 15px;
        color: #1f2937;
    }
    
    .template-actions {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }
    
    .radio-card {
        position: relative;
        cursor: pointer;
    }
    
    .radio-card input[type="radio"] {
        position: absolute;
        opacity: 0;
    }
    
    .radio-indicator {
        position: absolute;
        top: 20px;
        left: 20px;
        width: 24px;
        height: 24px;
        border: 2px solid #d1d5db;
        border-radius: 50%;
        background: white;
    }
    
    .radio-card input[type="radio"]:checked ~ .template-preview {
        border-color: #1a73e8;
    }
    
    .radio-card input[type="radio"]:checked ~ .radio-indicator {
        border-color: #1a73e8;
        background: #1a73e8;
    }
    
    .radio-card input[type="radio"]:checked ~ .radio-indicator::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 14px;
        font-weight: 700;
    }
</style>

<div class="config-card">
    <div style="margin-bottom: 30px;">
        <p style="color: #6b7280; font-size: 14px; margin: 0;">
            Personaliza cómo se ven tus facturas y recibos. Selecciona una plantilla y ajusta los detalles.
        </p>
    </div>

    <!-- Plantilla Clásica -->
    <label class="radio-card">
        <input type="radio" name="template" value="classic" checked>
        <div class="radio-indicator"></div>
        <div class="template-preview selected">
            <span class="template-badge">POR DEFECTO</span>
            
            <div class="template-header">
                <div class="template-logo">🏢</div>
                <h3 class="template-company">Mi Negocio SPA</h3>
                <p class="template-info">
                    Calle 10 #8-40, Villavicencio, Meta<br>
                    Tel: +57 310 123 4567 | NIT: 900.123.456-7
                </p>
            </div>
            
            <div style="margin: 20px 0;">
                <h4 style="font-size: 16px; font-weight: 600; margin: 0 0 10px 0;">FACTURA #00001</h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; font-size: 13px;">
                    <div><strong>Cliente:</strong> Juan Pérez</div>
                    <div><strong>Fecha:</strong> 06 Ene 2026</div>
                    <div><strong>Atendido por:</strong> María García</div>
                    <div><strong>Método:</strong> Efectivo</div>
                </div>
            </div>
            
            <div class="invoice-preview">
                <div class="invoice-row">
                    <span>Corte de Cabello</span>
                    <span>$25.000</span>
                </div>
                <div class="invoice-row">
                    <span>Manicure Tradicional</span>
                    <span>$20.000</span>
                </div>
                <div class="invoice-row">
                    <span>Subtotal</span>
                    <span>$45.000</span>
                </div>
                <div class="invoice-row">
                    <span>IVA (19%)</span>
                    <span>$8.550</span>
                </div>
                <div class="invoice-row">
                    <span>TOTAL</span>
                    <span>$53.550</span>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 20px; font-size: 12px; color: #6b7280;">
                ¡Gracias por su visita! Lo esperamos pronto 😊
            </div>
            
            <div class="template-actions">
                <button class="btn btn-primary" style="flex: 1;" onclick="customizeTemplate('classic')">
                    ✏️ Personalizar
                </button>
                <button class="btn btn-secondary" onclick="previewTemplate('classic')">
                    👁️ Vista Previa
                </button>
            </div>
        </div>
    </label>

    <!-- Plantilla Moderna -->
    <label class="radio-card">
        <input type="radio" name="template" value="modern">
        <div class="radio-indicator"></div>
        <div class="template-preview">
            <div style="background: linear-gradient(135deg, #1a73e8 0%, #1557b0 100%); color: white; padding: 25px; border-radius: 8px 8px 0 0; margin: -30px -30px 20px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h3 style="margin: 0 0 10px 0; font-size: 24px;">Mi Negocio SPA</h3>
                        <p style="margin: 0; font-size: 13px; opacity: 0.9;">www.minegocio.com</p>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 32px; font-weight: 700;">#00001</div>
                        <div style="font-size: 12px; opacity: 0.9;">06 Ene 2026</div>
                    </div>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <div style="font-size: 11px; color: #9ca3af; font-weight: 600; margin-bottom: 5px;">FACTURADO A:</div>
                    <div style="font-weight: 600; margin-bottom: 3px;">Juan Pérez</div>
                    <div style="font-size: 13px; color: #6b7280;">CC 1.234.567.890</div>
                </div>
                <div>
                    <div style="font-size: 11px; color: #9ca3af; font-weight: 600; margin-bottom: 5px;">ATENDIDO POR:</div>
                    <div style="font-weight: 600; margin-bottom: 3px;">María García</div>
                    <div style="font-size: 13px; color: #6b7280;">Especialista Senior</div>
                </div>
            </div>
            
            <table style="width: 100%; font-size: 13px; margin: 20px 0;">
                <thead style="background: #f9fafb; border-top: 2px solid #e5e7eb; border-bottom: 2px solid #e5e7eb;">
                    <tr>
                        <th style="padding: 10px; text-align: left;">Servicio</th>
                        <th style="padding: 10px; text-align: right;">Precio</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">Corte de Cabello</td>
                        <td style="padding: 10px; text-align: right;">$25.000</td>
                    </tr>
                    <tr style="border-bottom: 1px solid #f3f4f6;">
                        <td style="padding: 10px;">Manicure Tradicional</td>
                        <td style="padding: 10px; text-align: right;">$20.000</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr style="font-weight: 600; font-size: 15px; background: #f9fafb;">
                        <td style="padding: 15px;">TOTAL</td>
                        <td style="padding: 15px; text-align: right; color: #1a73e8;">$53.550</td>
                    </tr>
                </tfoot>
            </table>
            
            <div style="text-align: center; padding: 15px; background: #fffbeb; border-radius: 6px; font-size: 13px; color: #92400e;">
                💳 Pago realizado: Efectivo
            </div>
            
            <div class="template-actions">
                <button class="btn btn-primary" style="flex: 1;" onclick="customizeTemplate('modern')">
                    ✏️ Personalizar
                </button>
                <button class="btn btn-secondary" onclick="previewTemplate('modern')">
                    👁️ Vista Previa
                </button>
            </div>
        </div>
    </label>

    <!-- Plantilla Minimalista -->
    <label class="radio-card">
        <input type="radio" name="template" value="minimal">
        <div class="radio-indicator"></div>
        <div class="template-preview">
            <div style="border-left: 4px solid #1a73e8; padding-left: 20px; margin-bottom: 25px;">
                <h3 style="margin: 0 0 5px 0; font-size: 28px; font-weight: 300;">Mi Negocio</h3>
                <p style="margin: 0; font-size: 13px; color: #6b7280;">Recibo de Pago</p>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e5e7eb;">
                <div>
                    <div style="font-size: 11px; color: #9ca3af;">FACTURA</div>
                    <div style="font-size: 20px; font-weight: 600;">#00001</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; color: #9ca3af;">FECHA</div>
                    <div style="font-size: 14px; font-weight: 500;">06 Ene 2026</div>
                </div>
            </div>
            
            <div style="margin: 20px 0;">
                <div style="font-size: 11px; color: #9ca3af; margin-bottom: 5px;">CLIENTE</div>
                <div style="font-size: 15px; font-weight: 500;">Juan Pérez</div>
            </div>
            
            <div style="margin: 30px 0;">
                <div style="padding: 12px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                    <span>Corte de Cabello</span>
                    <span style="font-weight: 500;">$25.000</span>
                </div>
                <div style="padding: 12px 0; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between;">
                    <span>Manicure Tradicional</span>
                    <span style="font-weight: 500;">$20.000</span>
                </div>
                <div style="padding: 15px 0; display: flex; justify-content: space-between; font-size: 18px; font-weight: 600;">
                    <span>Total</span>
                    <span>$53.550</span>
                </div>
            </div>
            
            <div class="template-actions">
                <button class="btn btn-primary" style="flex: 1;" onclick="customizeTemplate('minimal')">
                    ✏️ Personalizar
                </button>
                <button class="btn btn-secondary" onclick="previewTemplate('minimal')">
                    👁️ Vista Previa
                </button>
            </div>
        </div>
    </label>

    <div class="btn-group" style="margin-top: 30px;">
        <button type="button" class="btn btn-primary" onclick="saveTemplate()">Guardar Selección</button>
        <a href="{{ url('admin/configuration') }}" class="btn btn-secondary">Volver</a>
    </div>
</div>

<script>
function customizeTemplate(template) {
    alert('Personalizando plantilla: ' + template + '\n\nPróximamente podrás ajustar colores, logo, textos y más.');
}

function previewTemplate(template) {
    alert('Abriendo vista previa de plantilla: ' + template);
}

function saveTemplate() {
    const selected = document.querySelector('input[name="template"]:checked').value;
    alert('Plantilla "' + selected + '" guardada exitosamente');
}
</script>

@endsection
