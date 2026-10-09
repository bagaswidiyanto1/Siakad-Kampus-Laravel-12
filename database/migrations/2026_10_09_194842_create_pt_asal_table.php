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
        Schema::create('pt_asal', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 200);
            $table->string('kota', 100)->nullable();
            $table->string('kode_pt', 20)->nullable();
            $table->enum('jenis', ['PTN', 'PTS', 'Politeknik', 'Akademi'])->default('PTS');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pt_asal');
    }
};
