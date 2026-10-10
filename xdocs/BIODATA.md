# Alur Penggunaan Modul Biodata Mahasiswa

Alur ini saya susun dari controller, model, dan blade yang terkirim. `edit.blade.php` dan `tab-data-diri.blade.php` tidak ikut terbaca isinya, jadi bagian itu saya asumsikan dari controller.

## 1. Gambaran umum

```
Login
  │
  ├─ Role mahasiswa ──► /biodata ──► redirect otomatis ──► /biodata/{id}/edit (data sendiri)
  │
  └─ Role staf (baa, superadmin, admin_fakultas,
     sekretaris_prodi, dosen_wali)
        │
        ▼
     /biodata (index: tabel DataTables + filter)
        │
        ├─ Tambah Mahasiswa ──► /biodata/create ──► store (hanya superadmin & baa)
        │
        └─ Edit ──► /biodata/{id}/edit ──► update (simpan data & foto)
```

## 2. Hak akses per role

| Role             | Lihat daftar     | Tambah | Edit                    | Cakupan data                             |
| ---------------- | ---------------- | ------ | ----------------------- | ---------------------------------------- |
| mahasiswa        | langsung ke edit | tidak  | ya                      | hanya data sendiri                       |
| dosen_wali       | ya               | tidak  | **read only**           | mahasiswa bimbingannya (`dosen_wali_id`) |
| sekretaris_prodi | ya               | tidak  | ya                      | satu prodi (`prodi_id`)                  |
| admin_fakultas   | ya               | tidak  | ya                      | satu fakultas (`fakultas_id`)            |
| baa              | ya               | ya     | ya, termasuk NIM & nama | semua                                    |
| superadmin       | ya               | ya     | ya, termasuk NIM & nama | semua                                    |

## 3. Alur halaman daftar (`index`)

1. Controller mengecek role. Mahasiswa dialihkan ke halaman edit miliknya. Role di luar daftar mendapat 403.
2. View `index.blade.php` dirender dengan dropdown Fakultas, Prodi, dan Status.
3. DataTables memanggil `biodata.index` lewat AJAX. Jika tidak ada record `Dosen` untuk dosen wali, hasilnya dikosongkan dengan `whereRaw('1 = 0')`.
4. Query dibatasi sesuai role (fakultas, prodi, atau dosen wali), lalu ditambah filter dari form (`fakultas_id`, `prodi_id`, `status`, `search_custom`).
5. Kolom diformat: NIM, nama, prodi, angkatan, badge status, dan tombol **Edit**.
6. Dropdown filter langsung reload tabel. Kolom pencarian memakai debounce 400 ms. Tombol Reset mengosongkan semua filter.

## 4. Alur tambah mahasiswa (`create` → `store`)

1. BAA atau superadmin mengisi NIM, nama, email (opsional), fakultas, prodi, dosen wali (opsional), angkatan, dan tanggal masuk.
2. Form `.ajax-form` dikirim lewat AJAX.
3. Validasi: NIM harus unik di `mahasiswa.nim` dan `users.username`, email unik, fakultas dan prodi harus ada.
4. Dalam satu transaksi database:
    - Buat `User` dengan username = NIM dan password default `password`.
    - Beri role `mahasiswa`.
    - Buat record `Mahasiswa` dengan status `aktif`.
5. Jika berhasil, response JSON berisi `redirect` ke daftar. Jika gagal, transaksi di-rollback.

Setelah itu mahasiswa login dengan NIM dan password `password`, lalu melengkapi biodatanya sendiri.

## 5. Alur edit biodata (`edit`)

1. Controller memuat `Mahasiswa` beserta relasinya: user, fakultas, prodi, dosenWali, orangTua, wali, dan riwayatPendidikan.
2. Akses dicek sesuai role. Jika tidak berhak, hasilnya 403.
3. Variabel `$isReadOnly` bernilai `true` hanya untuk dosen wali. Semua tab memakai variabel ini untuk men-_disable_ input.
4. Halaman edit berisi tab-tab berikut:

| Tab                    | Isi                                                            | Disimpan lewat              |
| ---------------------- | -------------------------------------------------------------- | --------------------------- |
| Data Diri              | NISN, NIK, tempat/tanggal lahir, agama, foto, dll.             | `update`                    |
| Alamat & Kontak        | alamat KTP/domisili, telepon, HP, email                        | `update`                    |
| Akademik               | angkatan, status, fakultas, prodi, dosen wali, tgl masuk/lulus | `update`                    |
| Orang Tua              | data ayah dan ibu                                              | `update` (`orang_tua[...]`) |
| Wali                   | data wali (opsional)                                           | `update` (`wali[...]`)      |
| Riwayat Pendidikan     | daftar dan tambah/hapus                                        | endpoint terpisah           |
| Sekolah Asal / PT Asal | dropdown master                                                | lihat catatan di bawah      |
| KTM                    | pratinjau kartu, otomatis dari biodata                         | tidak ada input             |

## 6. Alur simpan (`update`)

1. Cek akses ulang. Dosen wali ditolak dengan 403.
2. Validasi semua field, termasuk `orang_tua.*`, `wali.*`, dan `foto` (jpg/jpeg/png, maks 2 MB, minimal 100×100 px). Khusus BAA dan superadmin, ditambah validasi `nim` dan `name`.
3. Transaksi dimulai.
4. **Foto:** jika ada file baru, foto lama dihapus, nama file dibuat dengan `generateFilename`, lalu `LocalStorageService::upload` melakukan resize dan kompres. Path relatif disimpan di kolom `foto`.
5. `Mahasiswa::update(...)`. NIM dan nama (di tabel `users`) hanya diubah oleh BAA atau superadmin.
6. Data `OrangTua` dan `Wali` di-_update_ jika sudah ada, atau dibuat baru jika belum.
7. Commit. Response berupa JSON untuk AJAX, atau redirect ke halaman edit dengan flash `success`.
8. Jika terjadi error, rollback dan kirim pesan error.

## 7. Alur riwayat pendidikan

- **Tambah:** tombol "+ Tambah Riwayat" mengirim `fetch` POST ke `biodata.riwayat.store`. Controller mengecek `can('update', $mahasiswa)` lalu validasi, dan jika berhasil halaman di-reload.
- **Hapus:** tombol "Hapus" (dengan `confirm`) mengirim `fetch` DELETE. Controller mengecek policy `update` pada mahasiswa pemilik riwayat, lalu menghapusnya.
- Dosen wali tidak melihat tombol hapus maupun form tambah.

## 8. Alur hapus foto (`deleteFoto`)

Controller mengambil `Mahasiswa` milik user yang sedang login, menghapus file lewat `LocalStorageService::delete`, lalu mengosongkan kolom `foto`.

## 9. Hal yang perlu Anda cek

1. **Sekolah Asal dan PT Asal tidak tersimpan.** `sekolah_asal_id` dan `pt_asal_id` tampil di tab, tetapi tidak ada di `$fillable`, validasi, maupun `$mahasiswaData`. Dropdown-nya hanya tampilan. Jika kolomnya ada di tabel, tambahkan di ketiga tempat itu.
2. **Mahasiswa bisa mengubah data akademiknya sendiri.** Untuk role mahasiswa, `isReadOnly` bernilai `false`, jadi fakultas, prodi, dosen wali, status, dan tanggal lulus bisa diedit, dan `update()` menyimpannya dari request. Sebaiknya field itu hanya diproses jika user adalah BAA, superadmin, atau staf yang berwenang, seperti yang sudah Anda lakukan untuk `nim` dan `name`.
3. **`deleteFoto` hanya bisa menghapus foto milik user yang login.** Staf tidak bisa menghapus foto mahasiswa lain lewat endpoint ini.
4. **`name` ada di `Mahasiswa::create()` pada `store`, tapi tidak ada di `$fillable`.** Nilainya diam-diam diabaikan. Hapus baris itu, atau tambahkan kolom `name` jika memang ada di tabel.
5. **Tab KTM belum menampilkan foto.** Foto sudah tersimpan, tetapi kartu masih memakai inisial nama. Anda bisa menampilkan `Storage::disk('public')->url($mahasiswa->foto)` di sana.
6. **Record kosong untuk wali.** Karena `wali` selalu terkirim dari form, record `Wali` dengan nilai kosong akan dibuat meskipun tidak diisi. Anda bisa melewati pembuatannya jika semua field kosong.
