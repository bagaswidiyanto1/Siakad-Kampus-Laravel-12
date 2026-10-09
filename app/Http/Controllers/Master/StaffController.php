<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Fakultas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\DataTables;

class StaffController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Staff::with(['user.roles', 'fakultas'])->latest();

            return DataTables::of($query)
                ->addIndexColumn()

                ->editColumn(
                    'nip',
                    fn($row) =>
                    $row->nip
                        ? '<span class="font-mono text-xs text-gray-700">' . e($row->nip) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn(
                    'nama_staff',
                    fn($row) =>
                    e($row->user->name ?? '-')
                )

                ->addColumn(
                    'jabatan_text',
                    fn($row) =>
                    $row->jabatan
                        ? '<span class="text-sm text-gray-700">' . e($row->jabatan) . '</span>'
                        : '<span class="text-gray-400">-</span>'
                )

                ->addColumn('role_badges', function ($row) {
                    if (!$row->user || $row->user->roles->isEmpty()) {
                        return '<span class="text-gray-400">-</span>';
                    }

                    return $row->user->roles
                        ->map(
                            fn($role) =>
                            '<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">'
                                . e($role->name) . '</span>'
                        )
                        ->implode(' ');
                })

                ->addColumn(
                    'fakultas_nama',
                    fn($row) =>
                    $row->fakultas->nama ?? '-'
                )

                ->addColumn('action', function ($row) {
                    return '<div class="flex items-center justify-end gap-1.5 whitespace-nowrap">
                    <a href="' . route('master.staff.edit', $row->id) . '"
                       class="bg-blue-600 hover:bg-blue-900 text-xs py-1 px-2 rounded-md text-white font-medium">
                       Edit
                    </a>
                    <button type="button"
                        class="btn-reset-password bg-yellow-500 hover:bg-yellow-600 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.staff.reset-password', $row->id) . '"
                        data-name="Staff ' . e($row->user->name ?? $row->nip) . '">
                        Reset Pass
                    </button>
                    <button type="button"
                        class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-xs py-1 px-2 rounded-md text-white font-medium"
                        data-url="' . route('master.staff.destroy', $row->id) . '"
                        data-name="Staff ' . e($row->user->name ?? $row->nip) . '">
                        Hapus
                    </button>
                </div>';
                })

                ->rawColumns(['nip', 'jabatan_text', 'role_badges', 'action'])
                ->make(true);
        }

        $useDatatables = true;

        return view('master.staff.index', compact('useDatatables'));
    }

    public function create()
    {
        $fakultas = Fakultas::where('is_active', true)->get();
        $roles = Role::whereIn('name', [
            'admin_fakultas',
            'admin_prodi',
            'sekretaris_prodi',
            'keuangan',
            'admin_kemahasiswaan',
            'admin_perpus',
            'auditor_qa',
            'baa'
        ])->get();
        return view('master.staff.create', compact('fakultas', 'roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'nullable|string|max:20|unique:staff,nip|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email',
            'role' => 'required|exists:roles,name',
            'jabatan' => 'nullable|string|max:100',
            'fakultas_id' => 'nullable|exists:fakultas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Buat user
        $user = User::create([
            'username' => $request->nip ?? strtolower(str_replace(' ', '', $request->name)) . rand(100, 999),
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'),
            'fakultas_id' => $request->fakultas_id,
        ]);

        // Assign role
        $user->assignRole($request->role);

        // Buat staff
        Staff::create([
            'user_id' => $user->id,
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'fakultas_id' => $request->fakultas_id,
        ]);

        return response()->json([
            'message'  => 'Staff berhasil ditambahkan! Password default: password',
            'redirect' => route('master.staff.index'),
        ]);
    }

    public function edit(Staff $staff)
    {
        $fakultas = Fakultas::where('is_active', true)->get();
        $roles = Role::whereIn('name', [
            'admin_fakultas',
            'admin_prodi',
            'sekretaris_prodi',
            'keuangan',
            'admin_kemahasiswaan',
            'admin_perpus',
            'auditor_qa',
            'baa'
        ])->get();

        $currentRole = $staff->user?->getRoleNames()->first();

        return view('master.staff.edit', compact('staff', 'fakultas', 'roles', 'currentRole'));
    }

    public function update(Request $request, Staff $staff)
    {
        $validator = Validator::make($request->all(), [
            'nip' => 'nullable|string|max:20|unique:staff,nip,' . $staff->id . '|unique:users,username,' . $staff->user_id,
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $staff->user_id,
            'role' => 'required|exists:roles,name',
            'jabatan' => 'nullable|string|max:100',
            'fakultas_id' => 'nullable|exists:fakultas,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal!',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Update user
        $user = $staff->user;
        $user->update([
            'username' => $request->nip ?? $user->username,
            'name' => $request->name,
            'email' => $request->email,
            'fakultas_id' => $request->fakultas_id,
        ]);

        // Sync role
        $user->syncRoles([$request->role]);

        // Update staff
        $staff->update([
            'nip' => $request->nip,
            'jabatan' => $request->jabatan,
            'fakultas_id' => $request->fakultas_id,
        ]);

        return response()->json([
            'message'  => 'Staff berhasil diupdate!',
            'redirect' => route('master.staff.index'),
        ]);
    }

    public function destroy(Staff $staff)
    {
        $user = $staff->user;
        $staff->delete();
        $user->delete();


        return response()->json([
            'message' => 'Staff berhasil dihapus!',
            'redirect' => route('master.staff.index'),
        ]);
    }

    public function resetPassword(Staff $staff)
    {
        $staff->user->update([
            'password' => Hash::make('password')
        ]);

        return response()->json([
            'message' => 'Password staff berhasil direset ke: password',
        ]);
    }
}
