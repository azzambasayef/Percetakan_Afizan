<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('layanan_cetak', function (Blueprint $table) {
            $table->id('id_layanan');
            $table->unsignedBigInteger('id_kategori');
            $table->unsignedBigInteger('id_bahan_baku');
            $table->string('nama_layanan', 100);
            $table->string('satuan', 20); // Meter / Lembar
            $table->decimal('harga_umum', 12, 2);
            $table->decimal('harga_studio', 12, 2);
            $table->decimal('biaya_desain', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_kategori')->references('id_kategori')->on('kategori_layanan')->onDelete('cascade');
            $table->foreign('id_bahan_baku')->references('id_bahan')->on('bahan_baku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan_cetak');
    }
};
