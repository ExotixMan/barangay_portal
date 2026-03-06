<?php
// database/seeders/RoleSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminRole;
use App\Models\AdminPermission;

class AdminRoleSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $roles = [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Admin',
                'description' => 'Full system access with all permissions',
                'is_system_role' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrative access with limited system controls',
                'is_system_role' => true,
            ],
            [
                'name' => 'staff',
                'display_name' => 'Staff',
                'description' => 'Basic staff access for daily operations',
                'is_system_role' => true,
            ],
            [
                'name' => 'secretary',
                'display_name' => 'Secretary',
                'description' => 'Document and content management access',
                'is_system_role' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            $role = AdminRole::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            // Assign permissions based on role
            $this->assignPermissionsToRole($role);
        }
    }

    private function assignPermissionsToRole($role)
    {
        $allPermissions = AdminPermission::pluck('id')->toArray();

        switch ($role->name) {
            case 'super_admin':
                $role->permissions()->sync($allPermissions);
                break;

            case 'admin':
                $permissions = AdminPermission::whereIn('name', [
                    'view_residents', 'add_residents', 'edit_residents',
                    'view_requests', 'create_requests', 'approve_requests',
                    'view_reports', 'create_reports',
                    'view_content', 'create_content', 'edit_content', 'publish_content',
                    'reset_password', 'deactivate_users', 'change_user_role',
                ])->pluck('id')->toArray();
                $role->permissions()->sync($permissions);
                break;

            case 'staff':
                $permissions = AdminPermission::whereIn('name', [
                    'view_residents',
                    'view_requests', 'create_requests',
                    'view_reports',
                    'view_content',
                ])->pluck('id')->toArray();
                $role->permissions()->sync($permissions);
                break;

            case 'secretary':
                $permissions = AdminPermission::whereIn('name', [
                    'view_residents',
                    'view_requests', 'create_requests',
                    'view_content', 'create_content', 'edit_content',
                    'generate_certificates',
                ])->pluck('id')->toArray();
                $role->permissions()->sync($permissions);
                break;
        }
    }
}