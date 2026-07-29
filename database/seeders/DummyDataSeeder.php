<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // Mengambil data referensi yang sudah ada (tidak di-dummy-kan)
        $csUser = \App\Models\User::where('role', 'cs')->first() ?? \App\Models\User::first();
        $bahanBaku = \App\Models\BahanBaku::all();
        $layananCetak = \App\Models\LayananCetak::all();

        if ($layananCetak->isEmpty() || $bahanBaku->isEmpty()) {
            $this->command->info('Harap pastikan tabel Bahan Baku dan Layanan Cetak sudah terisi minimal 1 data.');
            return;
        }

        // 1. Generate 50 Pelanggan
        $this->command->info('Membuat 50 Pelanggan...');
        $pelangganIds = [];
        for ($i = 0; $i < 50; $i++) {
            $pelanggan = \App\Models\Pelanggan::create([
                'nama_pelanggan' => $faker->name,
                'no_wa' => $faker->phoneNumber,
                'tipe_pelanggan' => $faker->randomElement(['umum', 'studio']),
                'alamat' => $faker->address,
            ]);
            $pelangganIds[] = $pelanggan->id_pelanggan;
        }

        // 2. Generate 50 Pesanan & 50 Detail Pesanan (masing-masing 1 detail per pesanan)
        $this->command->info('Membuat 50 Pesanan dan Detail Pesanan...');
        foreach ($pelangganIds as $idPelanggan) {
            $tanggalPesanan = $faker->dateTimeBetween('-6 months', 'now');
            $statusPesanan = $faker->randomElement(['menunggu_desain', 'siap_cetak', 'sedang_cetak', 'selesai', 'diambil']);
            
            // Random layanan
            $layanan = $layananCetak->random();
            $jumlah = $faker->numberBetween(1, 10);
            
            // Harga
            $subtotal = $layanan->harga_per_satuan * $jumlah;
            
            // Random ukuran jika layanan meter persegi
            $panjang = null;
            $lebar = null;
            if ($layanan->satuan_harga == 'meter_persegi') {
                $panjang = $faker->randomFloat(2, 1, 5); // 1 sampai 5 meter
                $lebar = $faker->randomFloat(2, 1, 3);
                $subtotal = $layanan->harga_per_satuan * ($panjang * $lebar) * $jumlah;
            }
            
            $pesanan = \App\Models\Pesanan::create([
                'id_pelanggan' => $idPelanggan,
                'id_user' => $csUser->id_user,
                'tanggal_pesanan' => $tanggalPesanan,
                'sumber_pesanan' => $faker->randomElement(['datang_langsung', 'whatsapp', 'instagram', 'facebook']),
                'status_pesanan' => $statusPesanan,
                'status_pembayaran' => in_array($statusPesanan, ['selesai', 'diambil']) ? 'lunas' : $faker->randomElement(['belum_bayar', 'lunas']),
                'total_harga' => $subtotal,
            ]);

            \App\Models\DetailPesanan::create([
                'id_pesanan' => $pesanan->id_pesanan,
                'id_layanan' => $layanan->id_layanan,
                'ukuran_panjang' => $panjang,
                'ukuran_lebar' => $lebar,
                'jumlah' => $jumlah,
                'subtotal' => $subtotal,
            ]);
        }

        // 3. Generate 50 Pemakaian Bahan Manual
        $this->command->info('Membuat 50 Riwayat Pemakaian Bahan...');
        for ($i = 0; $i < 50; $i++) {
            $bahan = $bahanBaku->random();
            \App\Models\PemakaianBahanManual::create([
                'id_bahan' => $bahan->id_bahan,
                'id_user' => $csUser->id_user,
                'jumlah_keluar' => $faker->numberBetween(1, 5),
                'tanggal_keluar' => $faker->dateTimeBetween('-3 months', 'now'),
                'keterangan' => 'Pengeluaran logistik ' . $faker->word,
            ]);
        }

        $this->command->info('Data Dummy berhasil di-generate!');
    }
}
