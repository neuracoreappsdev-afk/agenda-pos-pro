<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
    public function run()
    {
        // Configuración de Negocio
        Setting::updateOrCreate(['key' => 'business_name'], [
            'value' => 'Mi Negocio',
            'type' => 'text',
            'category' => 'negocio',
            'label' => 'Nombre del Negocio',
            'description' => 'Nombre comercial de tu empresa'
        ]);

        Setting::updateOrCreate(['key' => 'business_email'], [
            'value' => 'info@minegocio.com',
            'type' => 'text',
            'category' => 'negocio',
            'label' => 'Email de Contacto',
            'description' => 'Email principal del negocio'
        ]);

        Setting::updateOrCreate(['key' => 'business_phone'], [
            'value' => '+57 300 123 4567',
            'type' => 'text',
            'category' => 'negocio',
            'label' => 'Teléfono',
            'description' => 'Teléfono principal'
        ]);

        Setting::updateOrCreate(['key' => 'work_days'], [
            'value' => json_encode(['monday', 'tuesday', 'wednesday', 'thursday', 'friday']),
            'type' => 'json',
            'category' => 'horarios',
            'label' => 'Días Laborales',
            'description' => 'Días de la semana en que opera el negocio'
        ]);

        Setting::updateOrCreate(['key' => 'work_hours_start'], [
            'value' => '09:00',
            'type' => 'text',
            'category' => 'horarios',
            'label' => 'Hora de Inicio',
            'description' => 'Hora de apertura'
        ]);

        Setting::updateOrCreate(['key' => 'work_hours_end'], [
            'value' => '18:00',
            'type' => 'text',
            'category' => 'horarios',
            'label' => 'Hora de Cierre',
            'description' => 'Hora de cierre'
        ]);

        // Formas de Pago
        Setting::updateOrCreate(['key' => 'formas_pago'], [
            'value' => json_encode(['Efectivo', 'Tarjeta de Crédito', 'Tarjeta de Débito', 'Transferencia Bancaria']),
            'type' => 'json',
            'category' => 'caja',
            'label' => 'Formas de Pago',
            'description' => 'Métodos de pago aceptados'
        ]);

        // Notificaciones
        Setting::updateOrCreate(['key' => 'notifications_enabled'], [
            'value' => '1',
            'type' => 'boolean',
            'category' => 'notificaciones',
            'label' => 'Notificaciones Habilitadas',
            'description' => 'Activar/desactivar sistema de notificaciones'
        ]);

        Setting::updateOrCreate(['key' => 'email_notifications'], [
            'value' => '1',
            'type' => 'boolean',
            'category' => 'notificaciones',
            'label' => 'Notificaciones por Email',
            'description' => 'Enviar notificaciones por correo electrónico'
        ]);

        // Reservas en Línea
        Setting::updateOrCreate(['key' => 'online_booking_enabled'], [
            'value' => '1',
            'type' => 'boolean',
            'category' => 'reservas',
            'label' => 'Reservas en Línea Habilitadas',
            'description' => 'Permitir reservas desde la web'
        ]);

        Setting::updateOrCreate(['key' => 'booking_advance_days'], [
            'value' => '30',
            'type' => 'number',
            'category' => 'reservas',
            'label' => 'Días de Anticipación',
            'description' => 'Cuántos días adelante pueden reservar los clientes'
        ]);

        echo "✅ Settings seeded successfully!\n";
    }
}
