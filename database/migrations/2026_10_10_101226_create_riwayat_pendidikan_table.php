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
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->enum('jenjang', ['SD', 'SMP', 'SMA/SMK', 'D3', 'D4', 'S1', 'S2', 'S3'])->nullable();
            $table->string('nama_institusi', 200);
            $table->string('kota', 100)->nullable();
            $table->year('tahun_masuk')->nullable();
            $table->year('tahun_lulus')->nullable();
            $table->string('jurusan', 100)->nullable();
            $table->string('nilai_akhir', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikan');
    }
};
