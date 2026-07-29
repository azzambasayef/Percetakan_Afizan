<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ==============================
        // 1. DATA USERS (7 Karyawan + 1 Owner)
        // ==============================
        DB::table('users')->insert([
            ['nama' => 'Pemilik Afizan',     'username' => 'owner',     'password' => Hash::make('password'), 'role' => 'owner',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Admin Percetakan',   'username' => 'admin',     'password' => Hash::make('password'), 'role' => 'admin',    'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'CS Satu',            'username' => 'cs1',       'password' => Hash::make('password'), 'role' => 'cs',       'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'CS Dua',             'username' => 'cs2',       'password' => Hash::make('password'), 'role' => 'cs',       'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Desainer Grafis',    'username' => 'desainer',  'password' => Hash::make('password'), 'role' => 'desainer', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Operator Satu',      'username' => 'operator1', 'password' => Hash::make('password'), 'role' => 'operator', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Operator Dua',       'username' => 'operator2', 'password' => Hash::make('password'), 'role' => 'operator', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Operator Tiga',      'username' => 'operator3', 'password' => Hash::make('password'), 'role' => 'operator', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ==============================
        // 2. DATA KATEGORI LAYANAN
        // ==============================
        DB::table('kategori_layanan')->insert([
            ['id_kategori' => 1, 'nama_kategori' => 'Spanduk',  'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 2, 'nama_kategori' => 'Stiker',   'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 3, 'nama_kategori' => 'Cetak A3', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ==============================
        // 3. DATA BAHAN BAKU (14 item)
        // ==============================
        DB::table('bahan_baku')->insert([
            // Bahan Spanduk - Otomatis
            ['id_bahan' => 1,  'nama_bahan' => 'Spanduk Flexi 1m',      'satuan' => 'Roll',   'stok_sekarang' => 10, 'batas_minimum' => 3, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 2,  'nama_bahan' => 'Spanduk Flexi 2m',      'satuan' => 'Roll',   'stok_sekarang' => 10, 'batas_minimum' => 3, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 3,  'nama_bahan' => 'Spanduk Flexi 3m',      'satuan' => 'Roll',   'stok_sekarang' => 10, 'batas_minimum' => 3, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 4,  'nama_bahan' => 'Bahan Blackout',        'satuan' => 'Roll',   'stok_sekarang' => 10, 'batas_minimum' => 3, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 5,  'nama_bahan' => 'Bahan Backlite',        'satuan' => 'Roll',   'stok_sekarang' => 10, 'batas_minimum' => 3, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            // Bahan Stiker - Otomatis
            ['id_bahan' => 6,  'nama_bahan' => 'Stiker Vynil',          'satuan' => 'Roll',   'stok_sekarang' => 10, 'batas_minimum' => 3, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            // Bahan Kertas A3 - Otomatis
            ['id_bahan' => 7,  'nama_bahan' => 'Kertas Artpaper A3',    'satuan' => 'Lembar', 'stok_sekarang' => 500, 'batas_minimum' => 100, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 8,  'nama_bahan' => 'Kertas Kromo A3',       'satuan' => 'Lembar', 'stok_sekarang' => 500, 'batas_minimum' => 100, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 9,  'nama_bahan' => 'Kuantak Biasa A3',      'satuan' => 'Lembar', 'stok_sekarang' => 500, 'batas_minimum' => 100, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 10, 'nama_bahan' => 'Kuantak Transparan A3', 'satuan' => 'Lembar', 'stok_sekarang' => 500, 'batas_minimum' => 100, 'tipe_pengurangan' => 'otomatis', 'created_at' => now(), 'updated_at' => now()],
            // Bahan Tinta - Manual
            ['id_bahan' => 11, 'nama_bahan' => 'Tinta Cyan',            'satuan' => 'Botol',  'stok_sekarang' => 5,  'batas_minimum' => 2,  'tipe_pengurangan' => 'manual',   'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 12, 'nama_bahan' => 'Tinta Magenta',         'satuan' => 'Botol',  'stok_sekarang' => 5,  'batas_minimum' => 2,  'tipe_pengurangan' => 'manual',   'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 13, 'nama_bahan' => 'Tinta Yellow',          'satuan' => 'Botol',  'stok_sekarang' => 5,  'batas_minimum' => 2,  'tipe_pengurangan' => 'manual',   'created_at' => now(), 'updated_at' => now()],
            ['id_bahan' => 14, 'nama_bahan' => 'Tinta Black',           'satuan' => 'Botol',  'stok_sekarang' => 5,  'batas_minimum' => 2,  'tipe_pengurangan' => 'manual',   'created_at' => now(), 'updated_at' => now()],
        ]);

        // ==============================
        // 4. DATA LAYANAN CETAK (Master Harga - 10 layanan)
        // ==============================
        DB::table('layanan_cetak')->insert([
            // Spanduk (kategori 1)
            ['id_kategori' => 1, 'id_bahan_baku' => 1, 'nama_layanan' => 'Spanduk Flexi',         'satuan' => 'm²',     'harga_umum' => 30000,  'harga_studio' => 25000,  'biaya_desain' => 20000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 1, 'id_bahan_baku' => 4, 'nama_layanan' => 'Spanduk Blackout',      'satuan' => 'm²',     'harga_umum' => 50000,  'harga_studio' => 42000,  'biaya_desain' => 20000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 1, 'id_bahan_baku' => 5, 'nama_layanan' => 'Spanduk Backlite',      'satuan' => 'm²',     'harga_umum' => 100000, 'harga_studio' => 85000,  'biaya_desain' => 20000, 'created_at' => now(), 'updated_at' => now()],
            // Stiker (kategori 2)
            ['id_kategori' => 2, 'id_bahan_baku' => 6, 'nama_layanan' => 'Stiker Vynil (No Cutting)',  'satuan' => 'm²', 'harga_umum' => 130000, 'harga_studio' => 115000, 'biaya_desain' => 25000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 2, 'id_bahan_baku' => 6, 'nama_layanan' => 'Stiker Vynil (Cutting)',     'satuan' => 'm²', 'harga_umum' => 160000, 'harga_studio' => 140000, 'biaya_desain' => 25000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 2, 'id_bahan_baku' => 6, 'nama_layanan' => 'Stiker Vynil (Laminating)',  'satuan' => 'm²', 'harga_umum' => 190000, 'harga_studio' => 165000, 'biaya_desain' => 25000, 'created_at' => now(), 'updated_at' => now()],
            // Cetak A3 (kategori 3)
            ['id_kategori' => 3, 'id_bahan_baku' => 7,  'nama_layanan' => 'A3 Artpaper',           'satuan' => 'Lembar', 'harga_umum' => 10000,  'harga_studio' => 8500,   'biaya_desain' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 3, 'id_bahan_baku' => 8,  'nama_layanan' => 'A3 Kromo',              'satuan' => 'Lembar', 'harga_umum' => 13000,  'harga_studio' => 11000,  'biaya_desain' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 3, 'id_bahan_baku' => 9,  'nama_layanan' => 'A3 Kuantak Biasa',      'satuan' => 'Lembar', 'harga_umum' => 15000,  'harga_studio' => 13000,  'biaya_desain' => 10000, 'created_at' => now(), 'updated_at' => now()],
            ['id_kategori' => 3, 'id_bahan_baku' => 10, 'nama_layanan' => 'A3 Kuantak Transparan', 'satuan' => 'Lembar', 'harga_umum' => 20000,  'harga_studio' => 17000,  'biaya_desain' => 10000, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ==============================
        // 5. DATA SUPPLIER (2 supplier dari wawancara)
        // ==============================
        DB::table('supplier')->insert([
            ['nama_supplier' => 'Printmet',    'no_telepon' => '08xxxxxxxxxx', 'no_wa' => '08xxxxxxxxxx', 'alamat' => 'Lhokseumawe', 'created_at' => now(), 'updated_at' => now()],
            ['nama_supplier' => 'Paperworks',  'no_telepon' => '08xxxxxxxxxx', 'no_wa' => '08xxxxxxxxxx', 'alamat' => 'Lhokseumawe', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ==============================
        // 6. DATA PELANGGAN CONTOH
        // ==============================
        DB::table('pelanggan')->insert([
            ['nama_pelanggan' => 'Budi Santoso',    'no_wa' => '081234567890', 'tipe_pelanggan' => 'umum',   'alamat' => 'Jl. Merdeka No. 10, Lhokseumawe',  'created_at' => now(), 'updated_at' => now()],
            ['nama_pelanggan' => 'Studio Kreatif',  'no_wa' => '082345678901', 'tipe_pelanggan' => 'studio', 'alamat' => 'Jl. Darusalam No. 5, Lhokseumawe', 'created_at' => now(), 'updated_at' => now()],
            ['nama_pelanggan' => 'Siti Aminah',     'no_wa' => '083456789012', 'tipe_pelanggan' => 'umum',   'alamat' => 'Jl. Cut Nyak Dhien, Lhokseumawe',  'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
