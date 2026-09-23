<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->roles->first()->name ?? 'tidak ada role';

        // Ambil menu yang tersedia berdasarkan permission
        $menus = $this->getAvailableMenus($user);

        return view('dashboard', compact('user', 'role', 'menus'));
    }

    private function getAvailableMenus($user)
    {
        $menus = [
            'dashboard' => [
                'label' => 'Dashboard',
                'icon' => 'home',
                'route' => 'dashboard',
                'permission' => 'dashboard.view'
            ]
        ];

        // User Management (hanya superadmin)
        if ($user->can('user.manage')) {
            $menus['users'] = [
                'label' => 'Kelola User',
                'icon' => 'users',
                'route' => 'admin.users.index',
                'permission' => 'user.manage'
            ];
        }

        // Permission Management (hanya superadmin)
        if ($user->can('permission.manage')) {
            $menus['permissions'] = [
                'label' => 'Hak Akses',
                'icon' => 'key',
                'route' => 'admin.permissions.index',
                'permission' => 'permission.manage'
            ];
        }

        // Master Data (akan diisi di TAHAP 5)
        if (
            $user->can('master.akademik.manage') ||
            $user->can('master.biaya.manage') ||
            $user->can('master.perpus.manage') ||
            $user->can('master.pengajuan.manage')
        ) {
            $menus['master'] = [
                'label' => 'Master Data',
                'icon' => 'database',
                'children' => [
                    // Akan diisi di TAHAP 5
                ]
            ];
        }

        return $menus;
    }
}
