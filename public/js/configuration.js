// =============================================
// CONFIGURACIÓN - JAVASCRIPT FUNCIONAL
// =============================================

// CSRF Token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

// =============================================
// FORMAS DE PAGO
// =============================================

// Listar formas de pago
function loadPaymentMethods() {
    fetch('/admin/api/configuration/payment-methods')
        .then(response => response.json())
        .then(methods => {
            const container = document.getElementById('payment-methods-list');
            if (!container) return;

            container.innerHTML = methods.map(method => `
                <div class="payment-card" style="background: ${method.color || '#f3f4f6'}">
                    <h4>${method.icon || '💳'} ${method.name}</h4>
                    <span class="badge ${method.active ? 'badge-success' : 'badge-secondary'}">
                        ${method.active ? 'Activo' : 'Inactivo'}
                    </span>
                    <div class="actions">
                        <button onclick="editPaymentMethod(${method.id})" class="btn-edit">Editar</button>
                        <button onclick="deletePaymentMethod(${method.id})" class="btn-delete">Eliminar</button>
                    </div>
                </div>
            `).join('');
        });
}

// Guardar forma de pago
function savePaymentMethod(event) {
    event.preventDefault();

    const formData = {
        name: document.getElementById('payment_name').value,
        icon: document.getElementById('payment_icon').value,
        color: document.getElementById('payment_color').value,
        active: document.getElementById('payment_active').checked,
        requires_approval: document.getElementById('payment_requires_approval').checked
    };

    const id = document.getElementById('payment_id').value;
    const url = id ? `/admin/api/configuration/payment-methods/${id}` : '/admin/api/configuration/payment-methods';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✓ ' + result.message);
                closeModal('paymentModal');
                loadPaymentMethods();
            }
        })
        .catch(error => alert('Error: ' + error));
}

// Editar forma de pago
function editPaymentMethod(id) {
    fetch(`/admin/api/configuration/payment-methods`)
        .then(response => response.json())
        .then(methods => {
            const method = methods.find(m => m.id === id);
            if (method) {
                document.getElementById('payment_id').value = method.id;
                document.getElementById('payment_name').value = method.name;
                document.getElementById('payment_icon').value = method.icon || '';
                document.getElementById('payment_color').value = method.color || '#3b82f6';
                document.getElementById('payment_active').checked = method.active;
                document.getElementById('payment_requires_approval').checked = method.requires_approval;
                openModal('paymentModal');
            }
        });
}

// Eliminar forma de pago
function deletePaymentMethod(id) {
    if (!confirm('¿Eliminar este método de pago?')) return;

    fetch(`/admin/api/configuration/payment-methods/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✓ Eliminado correctamente');
                loadPaymentMethods();
            }
        });
}

// =============================================
// ESPECIALIDADES
// =============================================

function loadSpecialties() {
    fetch('/admin/api/configuration/specialties')
        .then(response => response.json())
        .then(specialties => {
            const container = document.getElementById('specialties-list');
            if (!container) return;

            container.innerHTML = specialties.map(spec => `
                <div class="specialty-item">
                    <span class="drag-handle">⋮⋮</span>
                    <span style="color: ${spec.color}">${spec.icon || '💼'}</span>
                    <strong>${spec.name}</strong>
                    <span class="badge">${spec.specialists_count || 0} especialistas</span>
                    <button onclick="editSpecialty(${spec.id})">✏️</button>
                    <button onclick="deleteSpecialty(${spec.id})">🗑️</button>
                </div>
            `).join('');
        });
}

function saveSpecialty(event) {
    event.preventDefault();

    const formData = {
        name: document.getElementById('specialty_name').value,
        icon: document.getElementById('specialty_icon').value,
        color: document.getElementById('specialty_color').value,
        description: document.getElementById('specialty_description').value
    };

    const id = document.getElementById('specialty_id').value;
    const url = id ? `/admin/api/configuration/specialties/${id}` : '/admin/api/configuration/specialties';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✓ ' + result.message);
                closeModal('specialtyModal');
                loadSpecialties();
            }
        });
}

function editSpecialty(id) {
    fetch('/admin/api/configuration/specialties')
        .then(response => response.json())
        .then(specialties => {
            const spec = specialties.find(s => s.id === id);
            if (spec) {
                document.getElementById('specialty_id').value = spec.id;
                document.getElementById('specialty_name').value = spec.name;
                document.getElementById('specialty_icon').value = spec.icon || '';
                document.getElementById('specialty_color').value = spec.color || '#ec4899';
                document.getElementById('specialty_description').value = spec.description || '';
                openModal('specialtyModal');
            }
        });
}

function deleteSpecialty(id) {
    if (!confirm('¿Eliminar especialidad?')) return;

    fetch(`/admin/api/configuration/specialties/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✓ Eliminada');
                loadSpecialties();
            }
        });
}

// =============================================
// TIPOS DE CLIENTES
// =============================================

function loadClientTypes() {
    fetch('/admin/api/configuration/client-types')
        .then(response => response.json())
        .then(types => {
            const container = document.getElementById('client-types-list');
            if (!container) return;

            container.innerHTML = types.map(type => `
                <div class="client-type-card" style="border-left: 4px solid ${type.color}">
                    <h4>${type.name}</h4>
                    <p>${type.clients_count || 0} clientes</p>
                    <p>Descuento: ${type.default_discount}%</p>
                    <button onclick="editClientType(${type.id})">Editar</button>
                    <button onclick="deleteClientType(${type.id})">Eliminar</button>
                </div>
            `).join('');
        });
}

function saveClientType(event) {
    event.preventDefault();

    const formData = {
        name: document.getElementById('client_type_name').value,
        description: document.getElementById('client_type_description').value,
        color: document.getElementById('client_type_color').value,
        default_discount: document.getElementById('client_type_discount').value,
        auto_apply_discount: document.getElementById('client_type_auto_apply').checked
    };

    const id = document.getElementById('client_type_id').value;
    const url = id ? `/admin/api/configuration/client-types/${id}` : '/admin/api/configuration/client-types';
    const method = id ? 'PUT' : 'POST';

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(formData)
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✓ ' + result.message);
                closeModal('clientTypeModal');
                loadClientTypes();
            }
        });
}

function editClientType(id) {
    fetch('/admin/api/configuration/client-types')
        .then(response => response.json())
        .then(types => {
            const type = types.find(t => t.id === id);
            if (type) {
                document.getElementById('client_type_id').value = type.id;
                document.getElementById('client_type_name').value = type.name;
                document.getElementById('client_type_description').value = type.description || '';
                document.getElementById('client_type_color').value = type.color || '#3b82f6';
                document.getElementById('client_type_discount').value = type.default_discount || 0;
                document.getElementById('client_type_auto_apply').checked = type.auto_apply_discount;
                openModal('clientTypeModal');
            }
        });
}

function deleteClientType(id) {
    if (!confirm('¿Eliminar tipo de cliente?')) return;

    fetch(`/admin/api/configuration/client-types/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken }
    })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('✓ Eliminado');
                loadClientTypes();
            }
        });
}

// =============================================
// UTILIDADES
// =============================================

function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    // Limpiar formulario
    const form = document.querySelector(`#${modalId} form`);
    if (form) form.reset();
}

// Cerrar modal al hacer click fuera
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function (e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Subir fotos
function handleFileUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function (e) {
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Cargar al inicio
document.addEventListener('DOMContentLoaded', function () {
    // Cargar datos si los contenedores existen
    if (document.getElementById('payment-methods-list')) loadPaymentMethods();
    if (document.getElementById('specialties-list')) loadSpecialties();
    if (document.getElementById('client-types-list')) loadClientTypes();
});
