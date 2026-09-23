<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Tampilkan halaman kelola permission
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function ($permission) {
            // Kelompokkan permission berdasarkan prefix
            $parts = explode('.', $permission->name);
            return $parts[0] ?? 'other';
        });

        return view('admin.permissions.index', compact('roles', 'permissions'));
    }

    /**
     * Toggle permission untuk role
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
            'permission' => 'required|exists:permissions,name',
        ]);

        $role = Role::findByName($request->role);
        $permission = Permission::findByName($request->permission);

        if ($role->hasPermissionTo($permission)) {
            $role->revokePermissionTo($permission);
            $status = 'revoked';
        } else {
            $role->givePermissionTo($permission);
            $status = 'given';
        }

        return response()->json([
            'success' => true,
            'status' => $status,
            'message' => "Permission '{$permission->name}' berhasil diubah untuk role '{$role->name}'"
        ]);
    }
}
