<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar 12 role sesuai spesifikasi
        $roles = [
            'superadmin',
            'baa',
            'admin_fakultas',
            'admin_prodi',
            'sekretaris_prodi',
            'dosen',
            'dosen_wali',
            'mahasiswa',
            'keuangan',
            'admin_kemahasiswaan',
            'admin_perpus',
            'auditor_qa'
        ];

        foreach ($roles as $role) {
            Role::create(['name' => $role]);
        }

        // Buat user superadmin default
        $user = \App\Models\User::create([
            'username' => 'superadmin',
            'name' => 'Super Admin',
            'email' => 'superadmin@siakad.test',
            'password' => bcrypt('password'),
        ]);

        $user->assignRole('superadmin');
    }
}
