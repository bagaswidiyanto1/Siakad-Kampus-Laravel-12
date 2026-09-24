<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\DataTables;

class PermissionController extends Controller
{
    /**
     * Tampilkan halaman kelola permission
     */
    public function index(Request $request)
    {
        $roles = Role::orderBy('name')->get();

        if ($request->ajax()) {
            $query = Permission::query()->orderBy('name');

            $dt = DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('group', fn(Permission $p) => explode('.', $p->name)[0] ?? 'other');

            // 1 kolom per role
            foreach ($roles as $role) {
                $dt->addColumn('role_' . $role->name, function (Permission $permission) use ($role) {
                    $checked  = $role->hasPermissionTo($permission) ? 'checked' : '';
                    $disabled = $role->name === 'superadmin' ? 'disabled' : '';
                    $textBg = $role->name === 'superadmin' ? 'text-gray-400 focus:ring-gray-400 cursor-no-drop' : 'text-blue-600 focus:ring-blue-400 cursor-pointer';

                    return '<div class="text-center">
                    <input type="checkbox"
                        class="permission-toggle h-4 w-4 rounded border-gray-300 ' . $textBg . '"
                        data-role="' . e($role->name) . '"
                        data-permission="' . e($permission->name) . '"
                        ' . $checked . ' ' . $disabled . '>
                </div>';
                });
            }

            // Daftar kolom yang berisi HTML
            $raw = ['group'];
            foreach ($roles as $role) {
                $raw[] = 'role_' . $role->name;
            }

            return $dt->rawColumns($raw)->make(true);
        }

        return view('admin.permissions.index', compact('roles'));
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
