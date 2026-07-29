<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembelian_bahan', function (Blueprint $table) {
            $table->id('id_pembelian');
            $table->unsignedBigInteger('id_supplier');
            $table->unsignedBigInteger('id_user'); // Admin yang input
            $table->date('tanggal_pembelian');
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->timestamps();

            $table->foreign('id_supplier')->references('id_supplier')->on('supplier')->onDelete('cascade');
            $table->foreign('id_user')->references('id_user')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembelian_bahan');
    }
};
