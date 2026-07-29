<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id('id_pesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_user'); // CS yang menginput
            $table->dateTime('tanggal_pesanan');
            $table->enum('sumber_pesanan', ['datang_langsung', 'whatsapp', 'instagram', 'facebook'])->default('datang_langsung');
            $table->enum('status_pesanan', ['menunggu_desain', 'siap_cetak', 'sedang_cetak', 'selesai', 'diambil'])->default('menunggu_desain');
            $table->enum('status_pembayaran', ['belum_bayar', 'lunas'])->default('belum_bayar');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pelanggan')->on('pelanggan')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
