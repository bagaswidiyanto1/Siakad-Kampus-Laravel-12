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

class UserManagementController extends Controller
{
    /**
     * Tampilkan daftar user
     */
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter berdasarkan role
        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter berdasarkan username
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('username', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate');
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

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus');
    }

    /**
     * Reset password user
     */
    public function resetPassword(User $user)
    {
        $user->update([
            'password' => Hash::make('password')
        ]);

        return redirect()->back()
            ->with('success', 'Password berhasil direset ke: password');
    }
}
