<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_pembelian', function (Blueprint $table) {
            $table->id('id_detail_pembelian');
            $table->unsignedBigInteger('id_pembelian');
            $table->unsignedBigInteger('id_bahan');
            $table->decimal('jumlah_beli', 10, 2);
            $table->decimal('harga_beli', 12, 2);
            $table->timestamps();

            $table->foreign('id_pembelian')->references('id_pembelian')->on('pembelian_bahan')->onDelete('cascade');
            $table->foreign('id_bahan')->references('id_bahan')->on('bahan_baku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_pembelian');
    }
};
