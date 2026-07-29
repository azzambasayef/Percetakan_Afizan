<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\LayananCetak;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PesananController extends Controller
{
    public function create()
    {
        $pelanggans = Pelanggan::all();
        $layanans = LayananCetak::with('bahanBaku')->get();
        return view('pesanan.create', compact('pelanggans', 'layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_pelanggan' => 'required|exists:pelanggan,id_pelanggan',
            'sumber_pesanan' => 'required|in:datang_langsung,whatsapp,instagram,facebook',
            'catatan' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.id_layanan' => 'required|exists:layanan_cetak,id_layanan',
            'items.*.jumlah' => 'required|numeric|min:1',
            'items.*.ukuran_panjang' => 'nullable|numeric|min:0',
            'items.*.ukuran_lebar' => 'nullable|numeric|min:0',
            'items.*.punya_desain' => 'required|in:ya,tidak',
            'status_pembayaran' => 'required|in:belum_bayar,lunas',
        ]);

        try {
            DB::beginTransaction();

            // Create Pesanan first to get the auto-increment ID
            $pesanan = Pesanan::create([
                'id_pelanggan' => $request->id_pelanggan,
                'id_user' => auth()->user()->id_user,
                'tanggal_pesanan' => now(),
                'status_pesanan' => 'menunggu_desain',
                'status_pembayaran' => $request->status_pembayaran,
                'total_harga' => 0, // We will update this later
                'sumber_pesanan' => $request->sumber_pesanan,
                'catatan' => $request->catatan,
            ]);

            $totalHarga = 0;
            $pelanggan = Pelanggan::find($request->id_pelanggan);
            $tipePelanggan = $pelanggan->tipe_pelanggan;

            $detailsToInsert = [];
            foreach ($request->items as $item) {
                $layanan = LayananCetak::find($item['id_layanan']);
                $hargaDasar = ($tipePelanggan == 'studio') ? $layanan->harga_studio : $layanan->harga_umum;
                $biayaDesain = ($item['punya_desain'] == 'tidak') ? $layanan->biaya_desain : 0;
                
                $subtotal = 0;
                if ($layanan->satuan == 'meter' || $layanan->satuan == 'm²') {
                    $panjang = $item['ukuran_panjang'] ?? 1;
                    $lebar = $item['ukuran_lebar'] ?? 1;
                    $luas = $panjang * $lebar;
                    $subtotal = (($hargaDasar * $luas) + $biayaDesain) * $item['jumlah'];
                } else {
                    $subtotal = ($hargaDasar + $biayaDesain) * $item['jumlah'];
                }
                
                $totalHarga += $subtotal;

                $detailsToInsert[] = [
                    'id_pesanan' => $pesanan->id_pesanan,
                    'id_layanan' => $item['id_layanan'],
                    'ukuran_panjang' => $item['ukuran_panjang'] ?? null,
                    'ukuran_lebar' => $item['ukuran_lebar'] ?? null,
                    'jumlah' => $item['jumlah'],
                    'punya_desain' => $item['punya_desain'] == 'ya' ? 1 : 0,
                    'subtotal' => $subtotal,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Insert details
            DetailPesanan::insert($detailsToInsert);

            // Update total harga
            $pesanan->total_harga = $totalHarga;
            
            // Check if there are no items that need design, skip to siap_cetak
            $needsDesign = collect($request->items)->contains('punya_desain', 'tidak');
            if (!$needsDesign) {
                $pesanan->status_pesanan = 'siap_cetak';
            }
            $pesanan->save();

            DB::commit();

            return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage());
        }
    }
}

