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
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->integer('sks');
            $table->integer('semester'); // 1, 2, 3, dst
            $table->enum('jenis', ['wajib', 'pilihan'])->default('wajib');
            $table->foreignId('prodi_id')->constrained('program_studi')->onDelete('cascade');
            $table->foreignId('kurikulum_id')->constrained('kurikulum')->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah');
    }
};
