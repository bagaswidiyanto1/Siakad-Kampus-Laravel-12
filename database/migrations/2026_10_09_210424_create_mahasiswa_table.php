<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('nim', 20)->unique();
            $table->string('nisn', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->string('tempat_lahir', 100)->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Lainnya'])->nullable();
            $table->enum('status_pernikahan', ['Belum Kawin', 'Kawin', 'Cerai'])->default('Belum Kawin');
            $table->string('kewarganegaraan', 50)->default('WNI');
            $table->text('alamat_ktp')->nullable();
            $table->text('alamat_domisili')->nullable();
            $table->string('no_telepon', 20)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('email_pribadi', 100)->nullable();
            $table->string('foto', 255)->nullable();
            $table->string('angkatan', 4)->nullable();
            $table->enum('status_mahasiswa', ['aktif', 'cuti', 'lulus', 'keluar', 'meninggal'])->default('aktif');
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->onDelete('set null');
            $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->onDelete('set null');
            $table->foreignId('dosen_wali_id')->nullable()->constrained('dosen')->onDelete('set null');
            $table->date('tgl_masuk')->nullable();
            $table->date('tgl_lulus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
