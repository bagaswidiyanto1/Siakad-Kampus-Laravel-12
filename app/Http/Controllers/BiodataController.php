<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\OrangTua;
use App\Models\Wali;
use App\Models\RiwayatPendidikan;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\Dosen;
use App\Models\SekolahAsal;
use App\Models\PtAsal;
use App\Models\User;
use App\Services\LocalStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\DataTables;

class BiodataController extends Controller
{
    /**
     * Tampilkan daftar mahasiswa (untuk admin/dosen)
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // ===================================================
        // 1. Redirect mahasiswa ke halaman edit dirinya
        //    (dilakukan SEBELUM cek AJAX supaya tidak stuck)
        // ===================================================
        if ($user->hasRole('mahasiswa') && !$request->ajax()) {
            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
            if ($mahasiswa) {
                return redirect()->route('biodata.edit', $mahasiswa->id);
            }
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan!');
        }

        // ===================================================
        // 2. Cek akses role
        // ===================================================
        $allowedRoles = ['baa', 'admin_fakultas', 'sekretaris_prodi', 'dosen_wali', 'superadmin'];
        $hasAccess = false;
        foreach ($allowedRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini!');
        }

        // ===================================================
        // 3. AJAX: DataTables server-side
        // ===================================================
        if ($request->ajax()) {
            $query = Mahasiswa::with(['user', 'fakultas', 'prodi', 'dosenWali.user']);

            // --- Scope role ---
            if ($user->hasRole('admin_fakultas')) {
                $query->where('fakultas_id', $user->fakultas_id);
            } elseif ($user->hasRole('sekretaris_prodi')) {
                $query->where('prodi_id', $user->prodi_id);
            } elseif ($user->hasRole('dosen_wali')) {
                $dosen = Dosen::where('user_id', $user->id)->first();
                if ($dosen) {
                    $query->where('dosen_wali_id', $dosen->id);
                } else {
                    // Dosen wali tidak punya record dosen → kosongkan
                    $query->whereRaw('1 = 0');
                }
            }

            // Filter dropdown
            if ($request->filled('fakultas_id')) {
                $query->where('fakultas_id', $request->fakultas_id);
            }
            if ($request->filled('prodi_id')) {
                $query->where('prodi_id', $request->prodi_id);
            }
            if ($request->filled('status')) {
                $query->where('status_mahasiswa', $request->status);
            }

            // ==========================================
            // TAMBAHKAN INI: filter pencarian NIM/Nama
            // ==========================================
            if ($request->filled('search_custom')) {
                $search = $request->search_custom;
                $query->where(function ($q) use ($search) {
                    $q->where('nim', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                });
            }

            // Catatan: filter "search" TIDAK perlu ditambahkan manual.
            // DataTables menangani search via kolom searchable.

            return DataTables::of($query)

                ->addIndexColumn()

                ->editColumn(
                    'nim',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->nim) . '</span>'
                )

                ->addColumn(
                    'nama_mahasiswa',
                    fn($row) =>
                    e($row->user->name ?? $row->name ?? '-')
                )

                ->addColumn(
                    'prodi_nama',
                    fn($row) =>
                    $row->prodi->nama ?? '-'
                )

                ->addColumn(
                    'angkatan_text',
                    fn($row) =>
                    $row->angkatan
                        ? '<span class="text-sm text-gray-700">' . e($row->angkatan) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn('status_badge', function ($row) {
                    $map = [
                        'aktif'    => 'bg-green-100 text-green-800',
                        'cuti'     => 'bg-yellow-100 text-yellow-800',
                        'lulus'    => 'bg-blue-100 text-blue-800',
                        'keluar'   => 'bg-red-100 text-red-800',
                        'meninggal' => 'bg-gray-100 text-gray-800',
                    ];
                    $class = $map[$row->status_mahasiswa] ?? 'bg-gray-100 text-gray-800';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e(ucfirst($row->status_mahasiswa ?? '-')) . '</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('biodata.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                </div>';
                })

                ->rawColumns(['nim', 'nama_mahasiswa', 'angkatan_text', 'status_badge', 'action'])
                ->make(true);
        }

        // ===================================================
        // 4. Non-AJAX: render view
        // ===================================================
        $fakultas   = Fakultas::where('is_active', true)->orderBy('nama')->get();
        $prodi      = ProgramStudi::where('is_active', true)->orderBy('nama')->get();
        $statusList = ['aktif', 'cuti', 'lulus', 'keluar', 'meninggal'];

        $useDatatables = true;

        return view('biodata.index', compact('fakultas', 'prodi', 'statusList', 'useDatatables'));
    }


    public function create()
    {
        $fakultas    = Fakultas::where('is_active', true)->orderBy('nama')->get();
        $prodi       = ProgramStudi::where('is_active', true)->orderBy('nama')->get();
        $dosen       = Dosen::with('user')->where('status', 'aktif')->orderBy('nidn')->get();

        return view('biodata.create', compact('fakultas', 'prodi', 'dosen'));
    }

    public function store(Request $request)
    {
        // Hanya superadmin (dan baa) yang boleh menambah
        $user = Auth::user();
        if (!$user->hasRole('superadmin') && !$user->hasRole('baa')) {
            abort(403, 'Anda tidak memiliki akses untuk menambah mahasiswa.');
        }

        // Validasi
        $validator = Validator::make($request->all(), [
            'nim'           => 'required|string|max:20|unique:mahasiswa,nim|unique:users,username',
            'name'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255|unique:users,email',
            'fakultas_id'   => 'required|exists:fakultas,id',
            'prodi_id'      => 'required|exists:program_studi,id',
            'dosen_wali_id' => 'nullable|exists:dosen,id',
            'tgl_masuk'     => 'nullable|date',
            'angkatan'      => 'nullable|string|max:4',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // 1. Buat User (untuk login mahasiswa)
            $userBaru = User::create([
                'username'    => $request->nim,           // username = NIM
                'name'        => $request->name,
                'email'       => $request->email,
                'password'    => Hash::make('password'),  // default password
                'fakultas_id' => $request->fakultas_id,
                'prodi_id'    => $request->prodi_id,
            ]);

            // 2. Assign role mahasiswa
            $userBaru->assignRole('mahasiswa');

            // 3. Buat record Mahasiswa
            $mahasiswa = Mahasiswa::create([
                'user_id'        => $userBaru->id,
                'name'           => $request->name,
                'nim'            => $request->nim,
                'fakultas_id'    => $request->fakultas_id,
                'prodi_id'       => $request->prodi_id,
                'dosen_wali_id'  => $request->dosen_wali_id,
                'tgl_masuk'      => $request->tgl_masuk,
                'angkatan'       => $request->angkatan ?? date('Y'),
                'status_mahasiswa' => 'aktif',
            ]);

            DB::commit();

            return response()->json([
                'message'  => 'Mahasiswa berhasil ditambahkan! Password default: "password".',
                'redirect' => route('biodata.index'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan form edit biodata
     */
    public function edit($id)
    {
        $mahasiswa = Mahasiswa::with([
            'user',
            'fakultas',
            'prodi',
            'dosenWali.user',
            'orangTua',
            'wali',
            'riwayatPendidikan'
        ])->findOrFail($id);

        $user = Auth::user();
        $isReadOnly = false;

        // Cek akses dengan logika sederhana
        if ($user->hasRole('mahasiswa')) {
            // Mahasiswa hanya bisa edit data sendiri
            if ($mahasiswa->user_id != $user->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini!');
            }
            $isReadOnly = false;
        } elseif ($user->hasRole('dosen_wali')) {
            // Dosen wali hanya bisa lihat mahasiswa bimbingannya
            $dosen = Dosen::where('user_id', $user->id)->first();
            if (!$dosen || $mahasiswa->dosen_wali_id != $dosen->id) {
                abort(403, 'Anda tidak memiliki akses ke data ini!');
            }
            $isReadOnly = true; // Read only
        } elseif ($user->hasRole('admin_fakultas')) {
            if ($mahasiswa->fakultas_id != $user->fakultas_id) {
                abort(403, 'Anda tidak memiliki akses ke data ini!');
            }
            $isReadOnly = false;
        } elseif ($user->hasRole('sekretaris_prodi')) {
            if ($mahasiswa->prodi_id != $user->prodi_id) {
                abort(403, 'Anda tidak memiliki akses ke data ini!');
            }
            $isReadOnly = false;
        } elseif ($user->hasRole('baa') || $user->hasRole('superadmin')) {
            $isReadOnly = false;
        } else {
            abort(403, 'Anda tidak memiliki akses ke halaman ini!');
        }

        // Data untuk dropdown
        $fakultas = Fakultas::where('is_active', true)->get();
        $prodi = ProgramStudi::where('is_active', true)->get();
        $dosen = Dosen::with('user')->where('status', 'aktif')->get();
        $sekolahAsal = SekolahAsal::where('is_active', true)->get();
        $ptAsal = PtAsal::where('is_active', true)->get();

        $agamaList = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'];
        $statusList = ['aktif', 'cuti', 'lulus', 'keluar', 'meninggal'];
        $jenjangList = ['SD', 'SMP', 'SMA/SMK', 'D3', 'D4', 'S1', 'S2', 'S3'];
        $jenisKelaminList = ['L', 'P'];
        $statusPernikahanList = ['Belum Kawin', 'Kawin', 'Cerai'];
        $kewarganegaraanList = ['WNI', 'WNA'];

        return view('biodata.edit', compact(
            'mahasiswa',
            'fakultas',
            'prodi',
            'dosen',
            'sekolahAsal',
            'ptAsal',
            'agamaList',
            'statusList',
            'jenjangList',
            'jenisKelaminList',
            'statusPernikahanList',
            'kewarganegaraanList',
            'isReadOnly'
        ));
    }


    /**
     * Update biodata mahasiswa
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Cek akses
        $user = Auth::user();

        if ($user->hasRole('mahasiswa')) {
            if ($mahasiswa->user_id != $user->id) {
                abort(403, 'Anda tidak memiliki akses!');
            }
        } elseif ($user->hasRole('dosen_wali')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosen Wali hanya bisa melihat data!'
                ], 403);
            }
            return redirect()->back()->with('error', 'Dosen Wali hanya bisa melihat data!');
        } elseif ($user->hasRole('admin_fakultas')) {
            if ($mahasiswa->fakultas_id != $user->fakultas_id) {
                abort(403, 'Anda tidak memiliki akses!');
            }
        } elseif ($user->hasRole('sekretaris_prodi')) {
            if ($mahasiswa->prodi_id != $user->prodi_id) {
                abort(403, 'Anda tidak memiliki akses!');
            }
        } elseif (!$user->hasRole('baa') && !$user->hasRole('superadmin')) {
            abort(403, 'Anda tidak memiliki akses!');
        }

        // Validasi
        $rules = [
            'nisn' => 'required|string|max:20',
            'nik' => 'required|string|min:16|max:16',
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:50',
            'status_pernikahan' => 'nullable|string|max:20',
            'kewarganegaraan' => 'nullable|string|max:50',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'nullable|string',
            'no_telepon' => 'required|string|max:20',
            'no_hp' => 'required|string|max:20',
            'email_pribadi' => 'required|email|max:100',
            'angkatan' => 'nullable|string|max:4',
            'status_mahasiswa' => 'nullable|in:aktif,cuti,lulus,keluar,meninggal',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'prodi_id' => 'nullable|exists:program_studi,id',
            'dosen_wali_id' => 'nullable|exists:dosen,id',
            'tgl_masuk' => 'nullable|date',
            'tgl_lulus' => 'nullable|date|after_or_equal:tgl_masuk',

            'orang_tua.nama_ayah' => 'required|string|max:100',
            'orang_tua.nama_ibu' => 'required|string|max:100',
            'orang_tua.pekerjaan_ayah' => 'required|string|max:100',
            'orang_tua.pekerjaan_ibu' => 'required|string|max:100',
            'orang_tua.alamat_ortu' => 'required|string',
            'orang_tua.no_hp_ortu' => 'required|string|max:20',

            'wali.nama_wali' => 'nullable|string|max:100',
            'wali.hubungan' => 'nullable|string|max:50',
            'wali.pekerjaan' => 'nullable|string|max:100',
            'wali.alamat' => 'nullable|string',
            'wali.no_hp' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048|dimensions:min_width=100,min_height=100',
        ];

        if ($user->hasRole('baa') || $user->hasRole('superadmin')) {
            $rules['nim'] = 'required|string|max:20|unique:mahasiswa,nim,' . $mahasiswa->id;
            $rules['name'] = 'required|string|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // ==========================================
            // PROSES FOTO (GABUNG DI UPDATE)
            // ==========================================
            $fotoUrl = $mahasiswa->foto; // default: pakai foto lama

            if ($request->hasFile('foto')) {
                $storage = new \App\Services\LocalStorageService();

                // Hapus foto lama
                if ($mahasiswa->foto) {
                    // $mahasiswa->foto sekarang berisi path relatif (mis. "mahasiswa/2024001/mhs_xxx.jpg")
                    $storage->delete($mahasiswa->foto);
                }

                $filename = $storage->generateFilename(
                    $request->file('foto')->getClientOriginalName(),
                    'mhs_' . $mahasiswa->nim
                );

                $path = $storage->upload(
                    $request->file('foto'),
                    'mahasiswa/' . $mahasiswa->nim,
                    $filename,
                    true
                );

                if (!$path) {
                    throw new \Exception('Gagal upload foto!');
                }

                $fotoUrl = $path; // simpan path relatif
            }

            // ==========================================
            // UPDATE MAHASISWA
            // ==========================================
            $mahasiswaData = [
                'nisn' => $request->nisn,
                'nik' => $request->nik,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'agama' => $request->agama,
                'status_pernikahan' => $request->status_pernikahan,
                'kewarganegaraan' => $request->kewarganegaraan,
                'alamat_ktp' => $request->alamat_ktp,
                'alamat_domisili' => $request->alamat_domisili,
                'no_telepon' => $request->no_telepon,
                'no_hp' => $request->no_hp,
                'email_pribadi' => $request->email_pribadi,
                'angkatan' => $request->angkatan,
                'status_mahasiswa' => $request->status_mahasiswa,
                'fakultas_id' => $request->fakultas_id,
                'prodi_id' => $request->prodi_id,
                'dosen_wali_id' => $request->dosen_wali_id,
                'tgl_masuk' => $request->tgl_masuk,
                'tgl_lulus' => $request->tgl_lulus,
                'foto' => $fotoUrl,
            ];

            if ($user->hasRole('baa') || $user->hasRole('superadmin')) {
                $mahasiswaData['nim'] = $request->nim;
            }

            $mahasiswa->update($mahasiswaData);

            if ($user->hasRole('baa') || $user->hasRole('superadmin')) {
                if ($request->filled('name')) {
                    $mahasiswa->user->update([
                        'name' => $request->name
                    ]);
                }
            }

            // Update Orang Tua & Wali
            if ($request->has('orang_tua')) {
                $orangTua = OrangTua::where('mahasiswa_id', $mahasiswa->id)->first();
                if ($orangTua) {
                    $orangTua->update($request->orang_tua);
                } else {
                    OrangTua::create(array_merge($request->orang_tua, ['mahasiswa_id' => $mahasiswa->id]));
                }
            }

            if ($request->has('wali')) {
                $wali = Wali::where('mahasiswa_id', $mahasiswa->id)->first();
                if ($wali) {
                    $wali->update($request->wali);
                } else {
                    Wali::create(array_merge($request->wali, ['mahasiswa_id' => $mahasiswa->id]));
                }
            }

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data biodata berhasil diperbarui!',
                    'data' => ['mahasiswa' => $mahasiswa->fresh()]
                ]);
            }

            return redirect()->route('biodata.edit', $mahasiswa->id)
                ->with('success', 'Data biodata berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Tambah riwayat pendidikan
     */
    public function addRiwayat(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Cek policy untuk update (karena menambah riwayat = update)
        $user = Auth::user();
        if (!$user->can('update', $mahasiswa)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses!'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'jenjang' => 'required|in:SD,SMP,SMA/SMK,D3,D4,S1,S2,S3',
            'nama_institusi' => 'required|string|max:200',
            'kota' => 'nullable|string|max:100',
            'tahun_masuk' => 'nullable|integer|min:1980|max:' . date('Y'),
            'tahun_lulus' => 'nullable|integer|min:1980|max:' . date('Y') + 1,
            'jurusan' => 'nullable|string|max:100',
            'nilai_akhir' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $riwayat = RiwayatPendidikan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'jenjang' => $request->jenjang,
            'nama_institusi' => $request->nama_institusi,
            'kota' => $request->kota,
            'tahun_masuk' => $request->tahun_masuk,
            'tahun_lulus' => $request->tahun_lulus,
            'jurusan' => $request->jurusan,
            'nilai_akhir' => $request->nilai_akhir,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil ditambahkan!',
            'data' => $riwayat
        ]);
    }

    /**
     * Hapus riwayat pendidikan
     */
    public function deleteRiwayat($id)
    {
        $riwayat = RiwayatPendidikan::findOrFail($id);
        $mahasiswa = $riwayat->mahasiswa;

        // Cek policy untuk update (karena menghapus riwayat = update)
        $user = Auth::user();
        if (!$user->can('update', $mahasiswa)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses!'
            ], 403);
        }

        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat pendidikan berhasil dihapus!'
        ]);
    }



    public function deleteFoto(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        if (!$mahasiswa) {
            return response()->json([
                'success' => false,
                'message' => 'Data mahasiswa tidak ditemukan!',
            ], 404);
        }

        if (!$mahasiswa->foto) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada foto yang dihapus!',
            ], 400);
        }

        try {
            // Hapus dari storage — sudah handle path relatif & URL lengkap
            $storage = new \App\Services\LocalStorageService();
            $storage->delete($mahasiswa->foto);

            $mahasiswa->update(['foto' => null]);

            return response()->json([
                'success' => true,
                'message' => 'Foto berhasil dihapus!',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Delete foto mahasiswa error', [
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}
