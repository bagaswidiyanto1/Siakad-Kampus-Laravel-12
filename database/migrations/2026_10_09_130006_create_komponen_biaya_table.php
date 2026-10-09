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
        Schema::create('komponen_biaya', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->enum('jenis', ['ukt', 'spp', 'praktikum', 'seminar', 'wisuda', 'lainnya'])->default('lainnya');
            $table->decimal('nominal', 15, 2);
            $table->string('semester_berlaku')->nullable(); // contoh: "2024/2025 Ganjil"
            $table->foreignId('prodi_id')->nullable()->constrained('program_studi')->onDelete('set null');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komponen_biaya');
    }
};
