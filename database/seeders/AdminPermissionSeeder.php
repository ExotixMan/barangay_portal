<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminPermission;
use App\Models\AdminRole;
use Illuminate\Support\Facades\DB;

class AdminPermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            // Residents Management
            ['name' => 'view_residents', 'display_name' => 'View Residents', 'module' => 'residents'],
            ['name' => 'add_residents', 'display_name' => 'Add Residents', 'module' => 'residents'],
            ['name' => 'edit_residents', 'display_name' => 'Edit Residents', 'module' => 'residents'],
            ['name' => 'delete_residents', 'display_name' => 'Delete Residents', 'module' => 'residents'],
            ['name' => 'export_residents', 'display_name' => 'Export Residents', 'module' => 'residents'],

            // Requests Management
            ['name' => 'view_requests', 'display_name' => 'View Requests', 'module' => 'requests'],
            ['name' => 'create_requests', 'display_name' => 'Create Requests', 'module' => 'requests'],
            ['name' => 'approve_requests', 'display_name' => 'Approve Requests', 'module' => 'requests'],
            ['name' => 'reject_requests', 'display_name' => 'Reject Requests', 'module' => 'requests'],
            ['name' => 'generate_certificates', 'display_name' => 'Generate Certificates', 'module' => 'requests'],

            // Reports
            ['name' => 'view_reports', 'display_name' => 'View Reports', 'module' => 'reports'],
            ['name' => 'create_reports', 'display_name' => 'Create Reports', 'module' => 'reports'],
            ['name' => 'edit_reports', 'display_name' => 'Edit Reports', 'module' => 'reports'],
            ['name' => 'export_reports', 'display_name' => 'Export Reports', 'module' => 'reports'],
            ['name' => 'delete_reports', 'display_name' => 'Delete Reports', 'module' => 'reports'],

            // Content Management
            ['name' => 'view_content', 'display_name' => 'View Content', 'module' => 'content'],
            ['name' => 'create_content', 'display_name' => 'Create Content', 'module' => 'content'],
            ['name' => 'edit_content', 'display_name' => 'Edit Content', 'module' => 'content'],
            ['name' => 'publish_content', 'display_name' => 'Publish Content', 'module' => 'content'],
            ['name' => 'delete_content', 'display_name' => 'Delete Content', 'module' => 'content'],

            // System Administration
            ['name' => 'manage_users', 'display_name' => 'Manage Users', 'module' => 'system'],
            ['name' => 'manage_roles', 'display_name' => 'Manage Roles', 'module' => 'system'],
            ['name' => 'view_audit_logs', 'display_name' => 'View Audit Logs', 'module' => 'system'],
            ['name' => 'system_settings', 'display_name' => 'System Settings', 'module' => 'system'],
            ['name' => 'backup_system', 'display_name' => 'Backup System', 'module' => 'system'],

            // User Management
            ['name' => 'reset_password', 'display_name' => 'Reset Password', 'module' => 'users'],
            ['name' => 'deactivate_users', 'display_name' => 'Deactivate Users', 'module' => 'users'],
            ['name' => 'change_user_role', 'display_name' => 'Change User Role', 'module' => 'users'],
            ['name' => 'view_user_activity', 'display_name' => 'View User Activity', 'module' => 'users'],
            ['name' => 'send_notifications', 'display_name' => 'Send Notifications', 'module' => 'users'],
            
            // Chatbot Management
            ['name' => 'manage_chatbot', 'display_name' => 'Manage Chatbot', 'module' => 'chatbot'],
            ['name' => 'edit_chatbot', 'display_name' => 'Edit Chatbot Content', 'module' => 'chatbot'],
            
            // Backup Management
            ['name' => 'view_backup', 'display_name' => 'View Backups', 'module' => 'backup'],
            ['name' => 'manage_backup', 'display_name' => 'Manage Backups', 'module' => 'backup'],
        ];

        foreach ($permissions as $permission) {
            AdminPermission::updateOrCreate(
                ['name' => $permission['name']],
                $permission
            );
        }
    }
}