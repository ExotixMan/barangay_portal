<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use App\Models\AdminRole;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        $superAdminRole = AdminRole::where('name', 'super_admin')->first();

        AdminUser::updateOrCreate(
            ['email' => 'admin@barangay.gov.ph'],
            [
                'first_name' => 'Juan',
                'last_name' => 'Dela Cruz',
                'username' => 'superadmin',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
    }
}