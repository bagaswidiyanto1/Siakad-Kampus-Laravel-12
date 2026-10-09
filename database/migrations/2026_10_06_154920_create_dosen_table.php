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
        Schema::create('dosen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('nidn', 20)->unique();
            $table->string('gelar_depan', 30)->nullable();
            $table->string('gelar_belakang', 30)->nullable();
            $table->string('spesialisasi', 100)->nullable();
            $table->string('jabatan_fungsional', 50)->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status', ['aktif', 'cuti', 'pensiun'])->default('aktif');
            $table->string('foto', 255)->nullable();
            $table->foreignId('fakultas_id')->nullable()->constrained('fakultas')->onDelete('set null');
            $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
