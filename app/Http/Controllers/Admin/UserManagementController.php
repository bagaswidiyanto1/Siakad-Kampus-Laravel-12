<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::with('roles');

            if ($request->filled('role')) {
                $query->whereHas('roles', fn($q) => $q->where('name', $request->role));
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('roles', function (User $user) {
                    return $user->roles
                        ->map(fn($role) => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">' . e($role->name) . '</span>')
                        ->implode(' ');
                })
                ->addColumn('action', function (User $user) {
                    $actions = '<a href="' . route('admin.users.edit', $user) . '" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>';

                    if (auth()->id() !== $user->id) {
                        $actions .= ' <button class="btn-delete-ajax bg-red-600 hover:bg-red-900 text-white font-bold py-1 px-3 rounded text-xs"
                            data-url="' . route('admin.users.destroy', $user->id) . '"
                            data-name="User ' . e($user->name) . '">Hapus</button>';
                    }

                    $actions .= ' <form action="' . route('admin.users.reset-password', $user) . '" method="POST" class="inline">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-900 text-white font-bold py-1 px-3 rounded text-xs">Reset Pass</button>
                              </form>';

                    return $actions;
                })
                ->rawColumns(['roles', 'action'])
                ->make(true);
        }

        $roles = Role::all();

        return view('admin.users.index', compact('roles'));
    }

    /**
     * Tampilkan form tambah user
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Simpan user baru
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
            'email' => 'nullable|email|unique:users,email',
        ];

        // Validasi username berdasarkan role
        $role = $request->role;

        if (in_array($role, ['mahasiswa'])) {
            // Mahasiswa: username = NIM
            $rules['nim'] = 'required|string|unique:users,username|max:20';
            $request->merge(['username' => $request->nim]);
        } elseif (in_array($role, ['dosen', 'dosen_wali'])) {
            // Dosen: username = NIDN
            $rules['nidn'] = 'required|string|unique:users,username|max:20';
            $request->merge(['username' => $request->nidn]);
        } else {
            // Role lain: username manual
            $rules['username'] = 'required|string|unique:users,username|max:255';
        }

        // Fakultas/Prodi opsional (akan diisi di TAHAP 5)
        $rules['fakultas_id'] = 'nullable|integer';
        $rules['prodi_id'] = 'nullable|integer';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Buat user
        $user = User::create([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('password'), // Default password
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
        ]);

        // Assign role
        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dibuat. Password default: password');
    }

    /**
     * Tampilkan form edit user
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        // Validasi
        $rules = [
            'name' => 'required|string|max:255',
            'role' => 'required|exists:roles,name',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
        ];

        // Validasi username berdasarkan role yang DIPILIH (bukan role lama)
        $role = $request->role;

        if (in_array($role, ['mahasiswa'])) {
            $rules['nim'] = 'required|string|unique:users,username,' . $user->id . '|max:20';
            $request->merge(['username' => $request->nim]);
        } elseif (in_array($role, ['dosen', 'dosen_wali'])) {
            $rules['nidn'] = 'required|string|unique:users,username,' . $user->id . '|max:20';
            $request->merge(['username' => $request->nidn]);
        } else {
            $rules['username'] = 'required|string|unique:users,username,' . $user->id . '|max:255';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update user
        $user->update([
            'username' => $request->username,
            'name' => $request->name,
            'email' => $request->email,
            'fakultas_id' => $request->fakultas_id,
            'prodi_id' => $request->prodi_id,
        ]);

        // Sync role (hapus role lama, assign role baru)
        $user->syncRoles([$request->role]);

        return response()->json([
            'message' => 'User berhasil diperbarui',
            'redirect' => route('admin.users.index'), // opsional
        ]);
    }

    /**
     * Hapus user
     */
    public function destroy(User $user)
    {
        // Cegah menghapus diri sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()
                ->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus',
            'redirect' => route('admin.users.index'), // opsional
        ]);
    }

    /**
     * Reset password user
     */
    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password')
        ]);

        return response()->json([
            'message' => 'Password berhasil direset ke: password',
        ]);
    }
}
