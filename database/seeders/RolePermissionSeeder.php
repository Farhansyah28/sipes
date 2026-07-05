<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Tenant Default
        $tenant = \App\Models\Tenant::firstOrCreate(
            ['id' => 1],
            ['name' => 'Pesantren Pusat SIPES', 'domain' => 'pusat']
        );

        // Define roles
        $roles = [
            'Super Admin',
            'Admin PSB',
            'Bagian Keuangan',
            'Ustadz',
            'Musyrif',
            'Kasir Kantin',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create Dummy Users
        $users = [
            [
                'name' => 'Super Administrator',
                'username' => 'admin',
                'email' => 'admin@sipes.com',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'role' => 'Super Admin'
            ],
            [
                'name' => 'Admin PSB',
                'username' => 'admin_psb',
                'email' => 'admin_psb@sipes.com',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'role' => 'Admin PSB'
            ],
            [
                'name' => 'Bendahara Pesantren',
                'username' => 'keuangan',
                'email' => 'keuangan@sipes.com',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'role' => 'Bagian Keuangan'
            ],
            [
                'name' => 'Ustadz Ahmad',
                'username' => 'ustadz',
                'email' => 'ustadz@sipes.com',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'role' => 'Ustadz'
            ],
            [
                'name' => 'Musyrif Asrama',
                'username' => 'musyrif',
                'email' => 'musyrif@sipes.com',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'role' => 'Musyrif'
            ],
            [
                'name' => 'Kasir Kantin',
                'username' => 'kasir',
                'email' => 'kasir@sipes.com',
                'password' => Hash::make('password'),
                'tenant_id' => 1,
                'role' => 'Kasir Kantin'
            ],
        ];

        foreach ($users as $userData) {
            $roleName = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                $userData
            );

            // Assign role
            if (!$user->hasRole($roleName)) {
                $user->assignRole($roleName);
            }
        }
        
        // Asign Super Admin to existing test user if exists
        $testUser = User::where('email', 'test@example.com')->first();
        if ($testUser && !$testUser->hasRole('Super Admin')) {
            $testUser->assignRole('Super Admin');
        }
    }
}
