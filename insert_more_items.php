<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\KategoriLayanan;
use App\Models\BahanBaku;
use App\Models\LayananCetak;

$katDigital = KategoriLayanan::where('nama_kategori', 'Digital Printing')->first();
$katMerch = KategoriLayanan::where('nama_kategori', 'Merchandise')->first();
$katOffset = KategoriLayanan::firstOrCreate(['nama_kategori' => 'Offset Printing']);

// 1. Separate Plakat and Sertifikat
// Update the old one to just Plakat
$oldPlakat = LayananCetak::where('nama_layanan', 'Cetak Plakat / Sertifikat')->first();
if ($oldPlakat) {
    $oldPlakat->update(['nama_layanan' => 'Cetak Plakat']);
}

// Add Sertifikat
$bhKertas = BahanBaku::firstOrCreate(['nama_bahan' => 'Kertas Sertifikat'], [
    'satuan' => 'lembar', 'stok_sekarang' => 100, 'batas_minimum' => 20, 'tipe_pengurangan' => 'otomatis'
]);
LayananCetak::firstOrCreate(['nama_layanan' => 'Cetak Sertifikat'], [
    'id_kategori' => $katDigital->id_kategori,
    'id_bahan_baku' => $bhKertas->id_bahan,
    'satuan' => 'pcs',
    'harga_umum' => 5000,
    'harga_studio' => 4000,
    'biaya_desain' => 15000
]);

// 2. Cetak Undangan
$bhUndangan = BahanBaku::firstOrCreate(['nama_bahan' => 'Blanko Undangan'], [
    'satuan' => 'lembar', 'stok_sekarang' => 500, 'batas_minimum' => 50, 'tipe_pengurangan' => 'otomatis'
]);
LayananCetak::firstOrCreate(['nama_layanan' => 'Cetak Undangan'], [
    'id_kategori' => $katOffset->id_kategori,
    'id_bahan_baku' => $bhUndangan->id_bahan,
    'satuan' => 'pcs',
    'harga_umum' => 2000,
    'harga_studio' => 1500,
    'biaya_desain' => 25000
]);

// 3. Cetak Akrilik
$bhAkrilik = BahanBaku::firstOrCreate(['nama_bahan' => 'Papan Akrilik'], [
    'satuan' => 'meter', 'stok_sekarang' => 10, 'batas_minimum' => 2, 'tipe_pengurangan' => 'otomatis'
]);
LayananCetak::firstOrCreate(['nama_layanan' => 'Cetak Akrilik'], [
    'id_kategori' => $katDigital->id_kategori, // or merch
    'id_bahan_baku' => $bhAkrilik->id_bahan,
    'satuan' => 'meter',
    'harga_umum' => 250000,
    'harga_studio' => 220000,
    'biaya_desain' => 50000
]);

// 4. Badges / Lanyard
$bhLanyard = BahanBaku::firstOrCreate(['nama_bahan' => 'Tali Lanyard'], [
    'satuan' => 'pcs', 'stok_sekarang' => 100, 'batas_minimum' => 20, 'tipe_pengurangan' => 'otomatis'
]);
LayananCetak::firstOrCreate(['nama_layanan' => 'Cetak Lanyard / Id Card'], [
    'id_kategori' => $katMerch->id_kategori,
    'id_bahan_baku' => $bhLanyard->id_bahan,
    'satuan' => 'pcs',
    'harga_umum' => 15000,
    'harga_studio' => 12000,
    'biaya_desain' => 20000
]);

// List all
$layanans = LayananCetak::all();
foreach ($layanans as $l) {
    echo "- " . $l->nama_layanan . " (" . $l->satuan . ")\n";
}
