<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\Kurikulum;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class DosenController extends Controller
{


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Dosen::with(['user', 'fakultas', 'prodi'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'nidn',
                    fn($row) =>
                    '<span class="font-mono text-xs text-gray-700">' . e($row->nidn) . '</span>'
                )

                ->addColumn(
                    'nama_dosen',
                    fn($row) =>
                    e($row->user->name ?? '-')
                )

                ->addColumn('gelar_lengkap', function ($row) {
                    $gelar = trim(($row->gelar_depan ?? '') . ' ' . ($row->gelar_belakang ?? ''));
                    return $gelar !== ''
                        ? '<span class="text-sm text-gray-700">' . e($gelar) . '</span>'
                        : '<span class="text-gray-400">-</span>';
                })

                ->addColumn(
                    'spesialisasi_text',
                    fn($row) =>
                    $row->spesialisasi
                        ? '<span class="text-sm text-gray-700">' . e($row->spesialisasi) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn('status_badge', function ($row) {
                    $map = [
                        'aktif' => 'bg-green-100 text-green-800',
                        'cuti'  => 'bg-yellow-100 text-yellow-800',
                    ];
                    $class = $map[$row->status] ?? 'bg-red-100 text-red-800';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium '
                        . $class . '">' . e(ucfirst($row->status ?? '-')) . '</span>';
                })

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                        <a href="' . route('master.dosen.edit', $row->id) . '"
                           class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                           Edit
                        </a>
                        <button type="button"
                            class="btn-reset-password bg-yellow-500 hover:bg-yellow-600 text-xs py-1 px-2 rounded-md text-white font-medium"
                            data-url="' . route('master.dosen.reset-password', $row->id) . '"
                            data-name="Dosen ' . e($row->user->name ?? $row->nidn) . '">
                            Reset Pass
                        </button>
                        <button type="button"
                            class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                            data-url="' . route('master.dosen.destroy', $row->id) . '"
                            data-name="Dosen ' . e($row->user->name ?? $row->nidn) . '">
                            Hapus
                        </button>
                    </div>';
                })

                ->rawColumns(['nidn', 'gelar_lengkap', 'spesialisasi_text', 'status_badge', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.dosen.index', compact('useDatatables'));
    }

    public function create()
    {
        $fakultas = Fakultas::where('is_active', true)->get();
        $prodi = ProgramStudi::where('is_active', true)->get();
        $roles = Role::whereIn('name', ['dosen', 'dosen_wali'])->get();
        return view('master.dosen.create', compact('fakultas', 'prodi', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => 'required|string|max:20|unique:dosen,nidn|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'role' => 'required|exists:roles,name',
            'gelar_depan' => 'nullable|string|max:30',
            'gelar_belakang' => 'nullable|string|max:30',
            'spesialisasi' => 'nullable|string|max:100',
            'jabatan_fungsional' => 'nullable|string|max:50',
            'tanggal_masuk' => 'nullable|date',
            'status' => 'required|in:aktif,cuti,pensiun',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Buat user
        $user = User::create([
            'username' => $request->nidn,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Buat dosen
        Dosen::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'nidn' => $request->nidn,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'spesialisasi' => $request->spesialisasi,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status' => $request->status,
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
        ]);

        return response()->json([
            'message'  => 'Dosen berhasil ditambahkan!',
            'redirect' => route('master.dosen.index'),
        ]);
    }

    public function edit(Dosen $dosen)
    {
        $fakultas = Fakultas::where('is_active', true)->get();
        $prodi = ProgramStudi::where('is_active', true)->get();
        $roles = Role::whereIn('name', ['dosen', 'dosen_wali'])->get();
        $kurikulum = Kurikulum::where('is_active', true)->get();

        $currentRole = $dosen->user?->getRoleNames()->first();

        return view('master.dosen.edit', compact('dosen', 'fakultas', 'prodi', 'roles', 'kurikulum', 'currentRole'));
    }

    public function update(Request $request, Dosen $dosen)
    {
        $validator = Validator::make($request->all(), [
            'nidn' => 'required|string|max:20|unique:dosen,nidn,' . $dosen->id . '|unique:users,username,' . $dosen->user_id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $dosen->user_id,
            'role' => 'required|exists:roles,name',
            'gelar_depan' => 'nullable|string|max:30',
            'gelar_belakang' => 'nullable|string|max:30',
            'spesialisasi' => 'nullable|string|max:100',
            'jabatan_fungsional' => 'nullable|string|max:50',
            'tanggal_masuk' => 'nullable|date',
            'status' => 'required|in:aktif,cuti,pensiun',
            'fakultas_id' => 'nullable|exists:fakultas,id',
            'prodi_id' => 'nullable|exists:program_studi,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Update user
        $user = $dosen->user;
        $user->update([
            'username' => $request->nidn,
            'name' => $request->name,
            'email' => $request->email,
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
        ]);

        // Sync role
        $user->syncRoles([$request->role]);

        // Update dosen
        $dosen->update([
            'nidn' => $request->nidn,
            'gelar_depan' => $request->gelar_depan,
            'gelar_belakang' => $request->gelar_belakang,
            'spesialisasi' => $request->spesialisasi,
            'jabatan_fungsional' => $request->jabatan_fungsional,
            'tanggal_masuk' => $request->tanggal_masuk,
            'status' => $request->status,
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
        ]);

        return response()->json([
            'message'  => 'Dosen berhasil diupdate!',
            'redirect' => route('master.dosen.index'),
        ]);
    }

    public function destroy(Dosen $dosen)
    {
        // Cek apakah dosen mengajar kelas
        if ($dosen->kelas()->count() > 0) {
            return response()->json([
                'message' => 'Dosen tidak bisa dihapus karena masih mengajar kelas!',
            ], 400);
        }

        $user = $dosen->user;
        $dosen->delete();
        $user->delete();

        return response()->json([
            'message' => 'Dosen berhasil dihapus!',
            'redirect' => route('master.dosen.index'),
        ]);
    }

    public function resetPassword(Dosen $dosen)
    {
        $dosen->user->update([
            'password' => Hash::make('password')
        ]);

        return response()->json([
            'message' => 'Password dosen berhasil direset ke: password',
        ]);
    }
}
