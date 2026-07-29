<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pemakaian_bahan_manual', function (Blueprint $table) {
            $table->id('id_pemakaian');
            $table->unsignedBigInteger('id_bahan');
            $table->unsignedBigInteger('id_user'); // Operator/Admin yang input
            $table->decimal('jumlah_keluar', 10, 2);
            $table->dateTime('tanggal_keluar');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            $table->foreign('id_bahan')->references('id_bahan')->on('bahan_baku')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemakaian_bahan_manual');
    }
};
