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
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswa')->onDelete('cascade');
            $table->string('nama_ayah', 100)->nullable();
            $table->string('nama_ibu', 100)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->text('alamat_ortu')->nullable();
            $table->string('no_hp_ortu', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua');
    }
};
