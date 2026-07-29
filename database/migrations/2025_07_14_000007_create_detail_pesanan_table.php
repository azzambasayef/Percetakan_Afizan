<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pesanan', function (Blueprint $table) {
            $table->id('id_detail');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedBigInteger('id_layanan');
            $table->decimal('ukuran_panjang', 8, 2)->nullable(); // Meter - untuk spanduk/stiker
            $table->decimal('ukuran_lebar', 8, 2)->nullable();   // Meter - untuk spanduk/stiker
            $table->integer('jumlah')->nullable();                // Lembar - untuk A3
            $table->boolean('punya_desain')->default(true);
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onDelete('cascade');
            $table->foreign('id_layanan')->references('id_layanan')->on('layanan_cetak')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pesanan');
    }
};
