<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Data user untuk setiap role
        $users = [
            // Super Admin
            [
                'username' => 'superadmin',
                'name' => 'Super Admin',
                'email' => 'superadmin@siakad.test',
                'password' => 'password',
                'role' => 'superadmin',
                'fakultas_id' => null,
                'prodi_id' => null,
            ],
            // BAA
            [
                'username' => 'baa',
                'name' => 'Biro Administrasi Akademik',
                'email' => 'baa@siakad.test',
                'password' => 'password',
                'role' => 'baa',
                'fakultas_id' => null,
                'prodi_id' => null,
            ],
            // Admin Fakultas
            [
                'username' => 'admin_fakultas',
                'name' => 'Admin Fakultas',
                'email' => 'admin_fakultas@siakad.test',
                'password' => 'password',
                'role' => 'admin_fakultas',
                'fakultas_id' => 1, // akan diisi setelah TAHAP 5
                'prodi_id' => null,
            ],
            // Admin Prodi
            [
                'username' => 'admin_prodi',
                'name' => 'Admin Program Studi',
                'email' => 'admin_prodi@siakad.test',
                'password' => 'password',
                'role' => 'admin_prodi',
                'fakultas_id' => 1,
                'prodi_id' => 1,
            ],
            // Sekretaris Prodi
            [
                'username' => 'sekretaris_prodi',
                'name' => 'Sekretaris Prodi',
                'email' => 'sekretaris_prodi@siakad.test',
                'password' => 'password',
                'role' => 'sekretaris_prodi',
                'fakultas_id' => 1,
                'prodi_id' => 1,
            ],
            // Dosen
            [
                'username' => '1234567890',
                'name' => 'Dosen Test',
                'email' => 'dosen@siakad.test',
                'password' => 'password',
                'role' => 'dosen',
                'fakultas_id' => 1,
                'prodi_id' => 1,
            ],
            // Dosen Wali
            [
                'username' => '0987654321',
                'name' => 'Dosen Wali Test',
                'email' => 'dosen_wali@siakad.test',
                'password' => 'password',
                'role' => 'dosen_wali',
                'fakultas_id' => 1,
                'prodi_id' => 1,
            ],
            // Mahasiswa
            [
                'username' => '20241001',
                'name' => 'Mahasiswa Test',
                'email' => 'mahasiswa@siakad.test',
                'password' => 'password',
                'role' => 'mahasiswa',
                'fakultas_id' => 1,
                'prodi_id' => 1,
            ],
            // Keuangan
            [
                'username' => 'keuangan',
                'name' => 'Admin Keuangan',
                'email' => 'keuangan@siakad.test',
                'password' => 'password',
                'role' => 'keuangan',
                'fakultas_id' => null,
                'prodi_id' => null,
            ],
            // Admin Kemahasiswaan
            [
                'username' => 'admin_kemahasiswaan',
                'name' => 'Admin Kemahasiswaan',
                'email' => 'admin_kemahasiswaan@siakad.test',
                'password' => 'password',
                'role' => 'admin_kemahasiswaan',
                'fakultas_id' => null,
                'prodi_id' => null,
            ],
            // Admin Perpus
            [
                'username' => 'admin_perpus',
                'name' => 'Admin Perpustakaan',
                'email' => 'admin_perpus@siakad.test',
                'password' => 'password',
                'role' => 'admin_perpus',
                'fakultas_id' => null,
                'prodi_id' => null,
            ],
            // Auditor QA
            [
                'username' => 'auditor_qa',
                'name' => 'Auditor QA',
                'email' => 'auditor_qa@siakad.test',
                'password' => 'password',
                'role' => 'auditor_qa',
                'fakultas_id' => null,
                'prodi_id' => null,
            ],
        ];

        foreach ($users as $userData) {
            // Cek apakah user sudah ada
            $user = User::where('username', $userData['username'])->first();

            if (!$user) {
                // Buat user baru
                $user = User::create([
                    'username' => $userData['username'],
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make($userData['password']),
                    'fakultas_id' => $userData['fakultas_id'],
                    'prodi_id' => $userData['prodi_id'],
                ]);
            }

            // Assign role
            $role = Role::findByName($userData['role']);
            $user->syncRoles([$role]);
        }

        $this->command->info('✅ ' . count($users) . ' user berhasil dibuat/diupdate!');
    }
}
