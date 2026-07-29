<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bahan_baku', function (Blueprint $table) {
            $table->id('id_bahan');
            $table->string('nama_bahan', 100);
            $table->string('satuan', 20); // Meter, Roll, Botol, Lembar
            $table->decimal('stok_sekarang', 10, 2)->default(0);
            $table->decimal('batas_minimum', 10, 2)->default(0);
            $table->enum('tipe_pengurangan', ['otomatis', 'manual'])->default('otomatis');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bahan_baku');
    }
};
