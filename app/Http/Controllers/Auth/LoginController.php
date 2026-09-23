<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // Daftar role untuk quick login buttons
        $quickLogins = [
            'superadmin' => ['username' => 'superadmin', 'label' => 'Super Admin'],
            'baa' => ['username' => 'baa', 'label' => 'BAA'],
            'admin_fakultas' => ['username' => 'admin_fakultas', 'label' => 'Admin Fakultas'],
            'admin_prodi' => ['username' => 'admin_prodi', 'label' => 'Admin Prodi'],
            'sekretaris_prodi' => ['username' => 'sekretaris_prodi', 'label' => 'Sekretaris Prodi'],
            'dosen' => ['username' => 'dosen', 'label' => 'Dosen'],
            'dosen_wali' => ['username' => 'dosen_wali', 'label' => 'Dosen Wali'],
            'mahasiswa' => ['username' => 'mahasiswa', 'label' => 'Mahasiswa'],
            'keuangan' => ['username' => 'keuangan', 'label' => 'Keuangan'],
            'admin_kemahasiswaan' => ['username' => 'admin_kemahasiswaan', 'label' => 'Admin Kemahasiswaan'],
            'admin_perpus' => ['username' => 'admin_perpus', 'label' => 'Admin Perpus'],
            'auditor_qa' => ['username' => 'auditor_qa', 'label' => 'Auditor QA'],
        ];

        return view('auth.login', compact('quickLogins'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        // Rate limiting
        RateLimiter::hit($this->throttleKey($request));

        throw ValidationException::withMessages([
            'username' => trans('auth.failed'),
        ]);
    }

    public function quickLogin($username)
    {
        $user = User::where('username', $username)->first();

        if (!$user) {
            $user = $this->createQuickUser($username);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    private function createQuickUser($username)
    {
        $roleNames = [
            'superadmin' => 'Super Admin',
            'baa' => 'BAA',
            'admin_fakultas' => 'Admin Fakultas',
            'admin_prodi' => 'Admin Prodi',
            'sekretaris_prodi' => 'Sekretaris Prodi',
            'dosen' => 'Dosen',
            'dosen_wali' => 'Dosen Wali',
            'mahasiswa' => 'Mahasiswa',
            'keuangan' => 'Keuangan',
            'admin_kemahasiswaan' => 'Admin Kemahasiswaan',
            'admin_perpus' => 'Admin Perpus',
            'auditor_qa' => 'Auditor QA',
        ];

        $user = User::create([
            'username' => $username,
            'name' => $roleNames[$username] ?? ucfirst($username),
            'email' => $username . '@siakad.test',
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($username);

        return $user;
    }

    private function throttleKey(Request $request)
    {
        return strtolower($request->input('username')) . '|' . $request->ip();
    }
}
