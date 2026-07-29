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
        // 1. Create View for Bab 4.5
        \Illuminate\Support\Facades\DB::unprepared("
            DROP VIEW IF EXISTS vw_laporan_pendapatan;
            CREATE VIEW vw_laporan_pendapatan AS
            SELECT 
                p.id_pesanan,
                p.tanggal_pesanan,
                c.nama_pelanggan,
                p.total_harga,
                p.status_pembayaran
            FROM pesanan p
            JOIN pelanggan c ON p.id_pelanggan = c.id_pelanggan
            WHERE p.status_pembayaran = 'lunas';
        ");

        // 2. Create Stored Procedure for Bab 4.6
        \Illuminate\Support\Facades\DB::unprepared("
            DROP PROCEDURE IF EXISTS sp_tambah_stok_bahan;
            CREATE PROCEDURE sp_tambah_stok_bahan(IN p_id_bahan BIGINT, IN p_jumlah INT)
            BEGIN
                UPDATE bahan_baku 
                SET stok_sekarang = stok_sekarang + p_jumlah, 
                    updated_at = NOW()
                WHERE id_bahan = p_id_bahan;
            END;
        ");

        // 3. Create Trigger for Bab 4.7
        \Illuminate\Support\Facades\DB::unprepared("
            DROP TRIGGER IF EXISTS trg_after_pemakaian_bahan;
            CREATE TRIGGER trg_after_pemakaian_bahan
            AFTER INSERT ON pemakaian_bahan_manual
            FOR EACH ROW
            BEGIN
                UPDATE bahan_baku
                SET stok_sekarang = stok_sekarang - NEW.jumlah_keluar,
                    updated_at = NOW()
                WHERE id_bahan = NEW.id_bahan;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::unprepared("DROP VIEW IF EXISTS vw_laporan_pendapatan;");
        \Illuminate\Support\Facades\DB::unprepared("DROP PROCEDURE IF EXISTS sp_tambah_stok_bahan;");
        \Illuminate\Support\Facades\DB::unprepared("DROP TRIGGER IF EXISTS trg_after_pemakaian_bahan;");
    }
};
