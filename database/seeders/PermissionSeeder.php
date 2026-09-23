<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Daftar semua permission yang akan digunakan
        $permissions = [
            // User Management
            'user.manage',
            'permission.manage',

            // Dashboard
            'dashboard.view',

            // Biodata
            'biodata.view',
            'biodata.manage',

            // KRS
            'krs.view',
            'krs.create',
            'krs.delete',
            'krs.lock',
            'krs.unlock',
            'krs.approve',
            'krs.periode.manage',
            'krs.kelas.manage',

            // Biaya
            'biaya.view',
            'biaya.bayar',
            'biaya.bayar_tunai',
            'biaya.verify',
            'biaya.manage',

            // Materi & Tugas
            'materi.view',
            'materi.manage',
            'tugas.submit',

            // Jadwal & Presensi
            'jadwal.view',
            'jadwal.manage',
            'presensi.input',
            'presensi.self',

            // PA Online
            'bimbingan.create',
            'bimbingan.approve',

            // Kuesioner
            'kuesioner.manage',
            'kuesioner.isi',

            // Nilai
            'nilai.input',
            'nilai.view',

            // Pengajuan
            'pengajuan.create',
            'pengajuan.approve.prodi',
            'pengajuan.approve.fakultas',
            'pengajuan.approve.baa',

            // Perpustakaan
            'perpus.pinjam',
            'perpus.kembali',
            'perpus.manage',

            // SKPI
            'skpi.create',
            'skpi.verify',
            'skpi.terbitkan',

            // Tracer Study
            'tracer.fill',
            'tracer.rekap',

            // Panduan
            'panduan.view',
            'panduan.manage',

            // Master Data
            'master.akademik.manage',
            'master.biaya.manage',
            'master.perpus.manage',
            'master.pengajuan.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Assign semua permission ke superadmin
        $superadmin = Role::findByName('superadmin');
        $superadmin->givePermissionTo(Permission::all());

        // Assign permission spesifik ke role lain
        $this->assignPermissionsToRoles();
    }

    private function assignPermissionsToRoles(): void
    {
        // BAA
        $baa = Role::findByName('baa');
        $baa->givePermissionTo([
            'biodata.manage',
            'krs.periode.manage',
            'krs.unlock',
            'krs.approve',
            'kuesioner.manage',
            'pengajuan.approve.baa',
            'skpi.terbitkan',
            'master.akademik.manage',
            'master.pengajuan.manage',
        ]);

        // Admin Fakultas
        $adminFakultas = Role::findByName('admin_fakultas');
        $adminFakultas->givePermissionTo([
            'biodata.manage',
            'pengajuan.approve.fakultas',
        ]);

        // Admin Prodi
        $adminProdi = Role::findByName('admin_prodi');
        $adminProdi->givePermissionTo([
            'biodata.manage',
            'krs.kelas.manage',
            'jadwal.manage',
            'pengajuan.approve.prodi',
        ]);

        // Sekretaris Prodi
        $sekretarisProdi = Role::findByName('sekretaris_prodi');
        $sekretarisProdi->givePermissionTo([
            'biodata.manage',
            'jadwal.manage',
        ]);

        // Dosen
        $dosen = Role::findByName('dosen');
        $dosen->givePermissionTo([
            'materi.view',
            'materi.manage',
            'tugas.submit',
            'presensi.input',
            'nilai.input',
        ]);

        // Dosen Wali
        $dosenWali = Role::findByName('dosen_wali');
        $dosenWali->givePermissionTo([
            'materi.view',
            'materi.manage',
            'tugas.submit',
            'presensi.input',
            'nilai.input',
            'bimbingan.approve',
        ]);

        // Mahasiswa
        $mahasiswa = Role::findByName('mahasiswa');
        $mahasiswa->givePermissionTo([
            'dashboard.view',
            'biodata.view',
            'krs.view',
            'krs.create',
            'krs.delete',
            'krs.lock',
            'biaya.view',
            'biaya.bayar',
            'materi.view',
            'tugas.submit',
            'jadwal.view',
            'presensi.self',
            'bimbingan.create',
            'kuesioner.isi',
            'nilai.view',
            'pengajuan.create',
            'perpus.pinjam',
            'perpus.kembali',
            'skpi.create',
            'tracer.fill',
            'panduan.view',
        ]);

        // Keuangan
        $keuangan = Role::findByName('keuangan');
        $keuangan->givePermissionTo([
            'biaya.view',
            'biaya.bayar_tunai',
            'biaya.verify',
            'biaya.manage',
            'master.biaya.manage',
        ]);

        // Admin Kemahasiswaan
        $adminKemahasiswaan = Role::findByName('admin_kemahasiswaan');
        $adminKemahasiswaan->givePermissionTo([
            'skpi.verify',
            'tracer.rekap',
        ]);

        // Admin Perpus
        $adminPerpus = Role::findByName('admin_perpus');
        $adminPerpus->givePermissionTo([
            'perpus.manage',
            'master.perpus.manage',
        ]);

        // Auditor QA (hanya bisa read)
        $auditorQa = Role::findByName('auditor_qa');
        $auditorQa->givePermissionTo([
            'dashboard.view',
            'biodata.view',
            'krs.view',
            'biaya.view',
            'materi.view',
            'jadwal.view',
            'nilai.view',
            'panduan.view',
        ]);
    }
}
