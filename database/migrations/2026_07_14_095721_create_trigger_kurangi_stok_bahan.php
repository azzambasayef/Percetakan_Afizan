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
        DB::unprepared('
            DROP TRIGGER IF EXISTS kurangi_stok_otomatis;
            CREATE TRIGGER kurangi_stok_otomatis 
            AFTER INSERT ON detail_pesanan
            FOR EACH ROW
            BEGIN
                DECLARE v_id_bahan BIGINT;
                DECLARE v_tipe_pengurangan VARCHAR(20);
                DECLARE v_satuan VARCHAR(20);
                DECLARE v_pengurangan DECIMAL(10,2);
                
                -- Ambil info bahan baku berdasarkan layanan yang dipesan
                SELECT b.id_bahan, b.tipe_pengurangan, b.satuan
                INTO v_id_bahan, v_tipe_pengurangan, v_satuan
                FROM layanan_cetak l
                JOIN bahan_baku b ON l.id_bahan_baku = b.id_bahan
                WHERE l.id_layanan = NEW.id_layanan;
                
                -- Jika bahan baku diatur untuk berkurang otomatis (Hybrid Concept)
                IF v_tipe_pengurangan = "otomatis" THEN
                    -- Hitung berapa banyak stok yang harus dikurangi
                    IF v_satuan = "meter" THEN
                        SET v_pengurangan = (IFNULL(NEW.ukuran_panjang, 1) * IFNULL(NEW.ukuran_lebar, 1)) * NEW.jumlah;
                    ELSE
                        SET v_pengurangan = NEW.jumlah;
                    END IF;
                    
                    -- Kurangi stoknya
                    UPDATE bahan_baku
                    SET stok_sekarang = stok_sekarang - v_pengurangan
                    WHERE id_bahan = v_id_bahan;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_otomatis');
    }
};
