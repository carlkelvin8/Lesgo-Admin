<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'app_name', 'value' => 'Lesgo Admin', 'type' => 'string', 'group' => 'general', 'description' => 'Application name'],
            ['key' => 'app_description', 'value' => 'Logistics Management System', 'type' => 'string', 'group' => 'general', 'description' => 'Application description'],
            ['key' => 'app_url', 'value' => config('app.url'), 'type' => 'string', 'group' => 'general', 'description' => 'Application URL'],
            ['key' => 'support_email', 'value' => 'support@lesgo.com', 'type' => 'string', 'group' => 'general', 'description' => 'Support email address'],
            ['key' => 'support_phone', 'value' => '+63 123 456 7890', 'type' => 'string', 'group' => 'general', 'description' => 'Support phone number'],
            ['key' => 'company_name', 'value' => 'Lesgo Logistics', 'type' => 'string', 'group' => 'general', 'description' => 'Company name'],
            ['key' => 'tax_id', 'value' => '123-456-789-000', 'type' => 'string', 'group' => 'general', 'description' => 'Tax ID'],
            ['key' => 'currency', 'value' => 'PHP', 'type' => 'string', 'group' => 'general', 'description' => 'Default currency'],
            ['key' => 'timezone', 'value' => 'Asia/Manila', 'type' => 'string', 'group' => 'general', 'description' => 'Default timezone'],
            
            // Order Settings
            ['key' => 'auto_accept_orders', 'value' => '0', 'type' => 'boolean', 'group' => 'orders', 'description' => 'Auto accept new orders'],
            ['key' => 'order_timeout_minutes', 'value' => '30', 'type' => 'integer', 'group' => 'orders', 'description' => 'Order timeout in minutes'],
            ['key' => 'max_orders_per_driver', 'value' => '5', 'type' => 'integer', 'group' => 'orders', 'description' => 'Max concurrent orders per driver'],
            ['key' => 'require_order_notes', 'value' => '0', 'type' => 'boolean', 'group' => 'orders', 'description' => 'Require order notes'],
            
            // Pricing Settings
            ['key' => 'base_fare', 'value' => '50', 'type' => 'integer', 'group' => 'pricing', 'description' => 'Base fare amount'],
            ['key' => 'per_km_rate', 'value' => '10', 'type' => 'integer', 'group' => 'pricing', 'description' => 'Per kilometer rate'],
            ['key' => 'minimum_fare', 'value' => '100', 'type' => 'integer', 'group' => 'pricing', 'description' => 'Minimum fare amount'],
            ['key' => 'enable_surge_pricing', 'value' => '0', 'type' => 'boolean', 'group' => 'pricing', 'description' => 'Enable surge pricing'],
            
            // Payment Settings
            ['key' => 'enable_cash_payment', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable cash payment'],
            ['key' => 'enable_card_payment', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable card payment'],
            ['key' => 'enable_wallet_payment', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable wallet payment'],
            ['key' => 'enable_online_payment', 'value' => '1', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Enable online payment'],
            ['key' => 'payment_timeout_minutes', 'value' => '15', 'type' => 'integer', 'group' => 'payment', 'description' => 'Payment timeout in minutes'],
            ['key' => 'auto_refund_cancelled_orders', 'value' => '0', 'type' => 'boolean', 'group' => 'payment', 'description' => 'Auto refund cancelled orders'],
            ['key' => 'refund_processing_days', 'value' => '7', 'type' => 'integer', 'group' => 'payment', 'description' => 'Refund processing days'],
            
            // Notification Settings
            ['key' => 'notify_new_order', 'value' => '1', 'type' => 'boolean', 'group' => 'notification', 'description' => 'Notify on new order'],
            ['key' => 'notify_order_status', 'value' => '1', 'type' => 'boolean', 'group' => 'notification', 'description' => 'Notify on order status change'],
            ['key' => 'notify_payment_received', 'value' => '1', 'type' => 'boolean', 'group' => 'notification', 'description' => 'Notify on payment received'],
            ['key' => 'notify_new_user', 'value' => '1', 'type' => 'boolean', 'group' => 'notification', 'description' => 'Notify on new user registration'],
            ['key' => 'enable_sms_notifications', 'value' => '0', 'type' => 'boolean', 'group' => 'notification', 'description' => 'Enable SMS notifications'],
            ['key' => 'sms_provider', 'value' => 'Twilio', 'type' => 'string', 'group' => 'notification', 'description' => 'SMS provider'],
            
            // Security Settings
            ['key' => 'require_email_verification', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'description' => 'Require email verification'],
            ['key' => 'enable_two_factor', 'value' => '0', 'type' => 'boolean', 'group' => 'security', 'description' => 'Enable two-factor authentication'],
            ['key' => 'max_login_attempts', 'value' => '5', 'type' => 'integer', 'group' => 'security', 'description' => 'Max login attempts'],
            ['key' => 'session_lifetime', 'value' => '120', 'type' => 'integer', 'group' => 'security', 'description' => 'Session lifetime in minutes'],
            ['key' => 'enable_audit_logs', 'value' => '1', 'type' => 'boolean', 'group' => 'security', 'description' => 'Enable audit logs'],
            ['key' => 'audit_log_retention_days', 'value' => '365', 'type' => 'integer', 'group' => 'security', 'description' => 'Audit log retention days'],
            
            // Maintenance Settings
            ['key' => 'maintenance_mode', 'value' => '0', 'type' => 'boolean', 'group' => 'maintenance', 'description' => 'Enable maintenance mode'],
            ['key' => 'maintenance_message', 'value' => 'We are currently performing scheduled maintenance. Please check back soon.', 'type' => 'string', 'group' => 'maintenance', 'description' => 'Maintenance mode message'],
            ['key' => 'auto_backup', 'value' => '1', 'type' => 'boolean', 'group' => 'maintenance', 'description' => 'Enable auto backup'],
            ['key' => 'backup_frequency', 'value' => 'daily', 'type' => 'string', 'group' => 'maintenance', 'description' => 'Backup frequency'],
            ['key' => 'backup_retention_days', 'value' => '30', 'type' => 'integer', 'group' => 'maintenance', 'description' => 'Backup retention days'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
