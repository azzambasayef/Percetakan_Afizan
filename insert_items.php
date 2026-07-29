<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KategoriLayanan;
use App\Models\BahanBaku;
use App\Models\LayananCetak;

// Add Merchandise category if not exist
$katMerch = KategoriLayanan::firstOrCreate(['nama_kategori' => 'Merchandise']);
$katDigital = KategoriLayanan::firstOrCreate(['nama_kategori' => 'Digital Printing']);

// Add Bahan
$bhBanner = BahanBaku::firstOrCreate(['nama_bahan' => 'Bahan Banner'], [
    'satuan' => 'meter', 'stok_sekarang' => 50, 'batas_minimum' => 10, 'tipe_pengurangan' => 'otomatis'
]);
$bhPlakat = BahanBaku::firstOrCreate(['nama_bahan' => 'Akrilik Plakat'], [
    'satuan' => 'pcs', 'stok_sekarang' => 20, 'batas_minimum' => 5, 'tipe_pengurangan' => 'otomatis'
]);

// Add Layanan
LayananCetak::firstOrCreate(['nama_layanan' => 'Cetak Banner'], [
    'id_kategori' => $katDigital->id_kategori,
    'id_bahan_baku' => $bhBanner->id_bahan,
    'satuan' => 'meter',
    'harga_umum' => 25000,
    'harga_studio' => 20000,
    'biaya_desain' => 15000
]);

LayananCetak::firstOrCreate(['nama_layanan' => 'Cetak Plakat / Sertifikat'], [
    'id_kategori' => $katMerch->id_kategori,
    'id_bahan_baku' => $bhPlakat->id_bahan,
    'satuan' => 'pcs',
    'harga_umum' => 150000,
    'harga_studio' => 130000,
    'biaya_desain' => 30000
]);

echo "Banner and Plakat successfully inserted.\n";
