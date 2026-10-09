<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Master\DosenController;
use App\Http\Controllers\Master\KurikulumController;
use App\Http\Controllers\Master\FakultasController;
use App\Http\Controllers\Master\JenisPengajuanController;
use App\Http\Controllers\Master\KategoriBukuController;
use App\Http\Controllers\Master\KelasController;
use App\Http\Controllers\Master\KomponenBiayaController;
use App\Http\Controllers\Master\MataKuliahController;
use App\Http\Controllers\Master\ProgramStudiController;
use App\Http\Controllers\Master\PtAsalController;
use App\Http\Controllers\Master\RuangController;
use App\Http\Controllers\Master\SekolahAsalController;
use App\Http\Controllers\Master\SlotWaktuController;
use App\Http\Controllers\Master\StaffController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Master\TahunAkademikController;
use Illuminate\Support\Facades\Route;

// ==========================================
// HALAMAN UTAMA
// ==========================================
Route::get('/', function () {
    return redirect()->route('login');
});


// ==========================================
// AUTHENTIKASI
// ==========================================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/quick-login/{username}', [LoginController::class, 'quickLogin'])->name('quick.login');


// Route logout
Route::post('/logout', function () {
    auth()->logout();
    return redirect()->route('login');
})->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Dashboard (protected)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('master')->name('master.')->group(function () {
        Route::resource('fakultas', FakultasController::class)
            ->parameters(['fakultas' => 'fakultas']);
        Route::resource('prodi', ProgramStudiController::class);
        Route::resource('tahun-akademik', TahunAkademikController::class);
        Route::resource('kurikulum', KurikulumController::class);
        Route::resource('mata-kuliah', MataKuliahController::class);
        Route::resource('kelas', KelasController::class)
            ->parameters(['kelas' => 'kelas']);

        Route::resource('dosen', DosenController::class);
        Route::post('dosen/{dosen}/reset-password', [DosenController::class, 'resetPassword'])->name('dosen.reset-password');

        Route::resource('staff', StaffController::class);
        Route::post('staff/{staff}/reset-password', [StaffController::class, 'resetPassword'])->name('staff.reset-password');

        // Biaya
        Route::resource('komponen-biaya', KomponenBiayaController::class);
        Route::resource('ruang', RuangController::class);
        Route::resource('slot-waktu', SlotWaktuController::class);

        Route::resource('sekolah-asal', SekolahAsalController::class);
        Route::resource('pt-asal', PtAsalController::class);

        Route::resource('kategori-buku', KategoriBukuController::class);

        Route::resource('jenis-pengajuan', JenisPengajuanController::class);
    });
});

// Admin Routes
Route::middleware(['auth', 'role:superadmin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::resource('users', UserManagementController::class);
    Route::post('users/{user}/reset-password', [UserManagementController::class, 'resetPassword'])->name('users.reset-password');

    // Permission Management
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions/toggle', [PermissionController::class, 'toggle'])->name('permissions.toggle');

    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/export', [ActivityLogController::class, 'export'])->name('activity-logs.export');
    Route::get('activity-logs/{id}', [ActivityLogController::class, 'show'])->name('activity-logs.show');
    Route::delete('activity-logs/{id}', [ActivityLogController::class, 'destroy'])->name('activity-logs.destroy');
    Route::delete('activity-logs', [ActivityLogController::class, 'clear'])->name('activity-logs.clear');
});
