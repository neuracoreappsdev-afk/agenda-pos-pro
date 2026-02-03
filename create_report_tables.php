<?php
// Script para crear tablas adicionales para informes
$pdo = new PDO('mysql:host=127.0.0.1;dbname=booking_app', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Creando tablas para informes...\n";

// Tabla de productos
$pdo->exec("CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    sku VARCHAR(100),
    category VARCHAR(100),
    price DECIMAL(12,2) DEFAULT 0,
    cost DECIMAL(12,2) DEFAULT 0,
    quantity INT DEFAULT 0,
    min_quantity INT DEFAULT 0,
    active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
echo "- products creada\n";

// Tabla de ventas
$pdo->exec("CREATE TABLE IF NOT EXISTS sales (
    id INT AUTO_INCREMENT PRIMARY KEY,
    invoice_number VARCHAR(50),
    customer_id INT,
    specialist_id INT,
    total DECIMAL(12,2) DEFAULT 0,
    subtotal DECIMAL(12,2) DEFAULT 0,
    discount DECIMAL(12,2) DEFAULT 0,
    tax DECIMAL(12,2) DEFAULT 0,
    payment_method VARCHAR(50),
    status VARCHAR(50) DEFAULT 'completed',
    notes TEXT,
    sale_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
echo "- sales creada\n";

// Tabla de detalles de venta
$pdo->exec("CREATE TABLE IF NOT EXISTS sale_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sale_id INT,
    item_type ENUM('service', 'product') DEFAULT 'service',
    item_id INT,
    item_name VARCHAR(255),
    quantity INT DEFAULT 1,
    unit_price DECIMAL(12,2),
    discount DECIMAL(12,2) DEFAULT 0,
    total DECIMAL(12,2),
    specialist_id INT,
    commission DECIMAL(12,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "- sale_items creada\n";

// Tabla de notificaciones
$pdo->exec("CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(100),
    title VARCHAR(255),
    message TEXT,
    is_read TINYINT DEFAULT 0,
    reference_type VARCHAR(100),
    reference_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "- notifications creada\n";

// Tabla de sedes
$pdo->exec("CREATE TABLE IF NOT EXISTS locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    address TEXT,
    phone VARCHAR(50),
    active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
echo "- locations creada\n";

// Tabla de comisiones
$pdo->exec("CREATE TABLE IF NOT EXISTS commissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    specialist_id INT,
    sale_id INT,
    sale_item_id INT,
    amount DECIMAL(12,2),
    percentage DECIMAL(5,2),
    status VARCHAR(50) DEFAULT 'pending',
    paid_at DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "- commissions creada\n";

// Tabla de movimientos de caja
$pdo->exec("CREATE TABLE IF NOT EXISTS cash_movements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type ENUM('income', 'expense') NOT NULL,
    concept VARCHAR(255),
    amount DECIMAL(12,2),
    payment_method VARCHAR(50),
    reference VARCHAR(100),
    notes TEXT,
    movement_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
echo "- cash_movements creada\n";

// Insertar datos de ejemplo
echo "\nInsertando datos de ejemplo...\n";

// Sede
$pdo->exec("INSERT IGNORE INTO locations (id, name, address) VALUES (1, 'Holguines Trade Center', 'Calle Principal 123, Holguines')");

// Productos de ejemplo
$productos = [
    ['AMINO KERATIN INTENS', 'PRD-001', 45000, 25000, 0, 5],
    ['BALSAMI PRESTO TREAT', 'PRD-002', 38000, 20000, 0, 3],
    ['DORCO', 'PRD-003', 5000, 2500, 0, 10],
    ['GEL DE CEJAS', 'PRD-004', 15000, 8000, 0, 5],
    ['GLAM STYLE 250 ML', 'PRD-005', 28000, 15000, 0, 3],
    ['HI MOISTURIZING 280M', 'PRD-006', 35000, 18000, 0, 5],
    ['20 KIT M', 'PRD-007', 120000, 65000, 4, 2],
    ['SHAMPOO ANTICASPA', 'PRD-008', 22000, 12000, 15, 5],
    ['ACONDICIONADOR PRO', 'PRD-009', 25000, 14000, 8, 5],
    ['ESMALTE GEL UV', 'PRD-010', 18000, 9000, 25, 10]
];

foreach ($productos as $p) {
    $stmt = $pdo->prepare("INSERT INTO products (name, sku, price, cost, quantity, min_quantity, category) 
                           VALUES (?, ?, ?, ?, ?, ?, 'General') 
                           ON DUPLICATE KEY UPDATE name=name");
    $stmt->execute([$p[0], $p[1], $p[2], $p[3], $p[4], $p[5]]);
}
echo "- Productos insertados\n";

// Ventas de ejemplo
$ventas = [
    ['FAC-001', 1, 1, 85000, 'Efectivo', '2025-12-26'],
    ['FAC-002', 2, 2, 65000, 'Tarjeta', '2025-12-26'],
    ['FAC-003', 3, 3, 45000, 'Efectivo', '2025-12-26'],
    ['FAC-004', 1, 4, 95000, 'Nequi', '2025-12-25'],
    ['FAC-005', 2, 5, 38000, 'Daviplata', '2025-12-25'],
    ['FAC-006', 3, 1, 120000, 'Tarjeta', '2025-12-24'],
    ['FAC-007', 1, 2, 55000, 'Efectivo', '2025-12-24'],
    ['FAC-008', 2, 3, 72000, 'Efectivo', '2025-12-23'],
    ['FAC-009', 3, 4, 48000, 'Tarjeta', '2025-12-23'],
    ['FAC-010', 1, 5, 88000, 'Nequi', '2025-12-22']
];

foreach ($ventas as $v) {
    $stmt = $pdo->prepare("INSERT INTO sales (invoice_number, customer_id, specialist_id, total, payment_method, sale_date) 
                           VALUES (?, ?, ?, ?, ?, ?)
                           ON DUPLICATE KEY UPDATE invoice_number=invoice_number");
    $stmt->execute($v);
}
echo "- Ventas insertadas\n";

// Notificaciones de ejemplo
$notifs = [
    ['reservation', 'Nueva Reserva en Línea', 'Sofía Sánchez ha creado una reserva en la sede Holguines Trade Center para las 14:00:00.'],
    ['reservation', 'Nueva Reserva en Línea', 'Gisela Vásquez Mercados ha creado una Reserva en Línea en la sede Holguines Trade 12-27 a las 10:00:00.'],
    ['reservation', 'Nueva Reserva en Línea', 'Sarah Barragan ha creado una reserva en la sede Holguines Trade Center para las 10:15:00.'],
    ['confirmation', 'Reserva confirmada por cliente', 'El cliente Jane Robayo ha confirmado la cita por medio del link de confirmación en Holguines Trade Center para las 12:15:00'],
    ['reminder', 'Recordatorio de cita', 'María García tiene una cita programada para mañana a las 9:00 AM.']
];

foreach ($notifs as $n) {
    $stmt = $pdo->prepare("INSERT INTO notifications (type, title, message) VALUES (?, ?, ?)");
    $stmt->execute($n);
}
echo "- Notificaciones insertadas\n";

// Movimientos de caja
$movimientos = [
    ['income', 'Venta FAC-001', 85000, 'Efectivo', '2025-12-26'],
    ['income', 'Venta FAC-002', 65000, 'Tarjeta', '2025-12-26'],
    ['expense', 'Pago proveedor', 150000, 'Transferencia', '2025-12-26'],
    ['income', 'Venta FAC-003', 45000, 'Efectivo', '2025-12-26'],
    ['expense', 'Servicios públicos', 85000, 'Efectivo', '2025-12-25'],
    ['income', 'Venta FAC-004', 95000, 'Nequi', '2025-12-25']
];

foreach ($movimientos as $m) {
    $stmt = $pdo->prepare("INSERT INTO cash_movements (type, concept, amount, payment_method, movement_date) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute($m);
}
echo "- Movimientos de caja insertados\n";

echo "\n¡Tablas creadas y datos de ejemplo insertados!\n";
