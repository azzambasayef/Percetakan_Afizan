<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
        $user = auth()->user();
        $role = $user->role;

        $rentang = $request->query('rentang', 'hari');
        $queryPesanan = \App\Models\Pesanan::query();
        $queryPemasukan = \App\Models\Pesanan::where('status_pembayaran', 'lunas');

        if ($rentang == 'minggu') {
            $queryPesanan->whereBetween('tanggal_pesanan', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
            $queryPemasukan->whereBetween('tanggal_pesanan', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
        } elseif ($rentang == 'bulan') {
            $queryPesanan->whereMonth('tanggal_pesanan', \Carbon\Carbon::now()->month)
                         ->whereYear('tanggal_pesanan', \Carbon\Carbon::now()->year);
            $queryPemasukan->whereMonth('tanggal_pesanan', \Carbon\Carbon::now()->month)
                           ->whereYear('tanggal_pesanan', \Carbon\Carbon::now()->year);
        } elseif ($rentang == 'tahun') {
            $queryPesanan->whereYear('tanggal_pesanan', \Carbon\Carbon::now()->year);
            $queryPemasukan->whereYear('tanggal_pesanan', \Carbon\Carbon::now()->year);
        } else {
            // default: hari
            $queryPesanan->whereDate('tanggal_pesanan', \Carbon\Carbon::today());
            $queryPemasukan->whereDate('tanggal_pesanan', \Carbon\Carbon::today());
        }

        $pesananHariIni = $queryPesanan->count();
        $pemasukanHariIni = $queryPemasukan->sum('total_harga');

        $pesananAntrean = \App\Models\Pesanan::whereIn('status_pesanan', ['menunggu_desain', 'siap_cetak', 'sedang_cetak'])->count();
        $stokKritis = \App\Models\BahanBaku::whereRaw('stok_sekarang <= batas_minimum')->count();
        $riwayatPesanan = \App\Models\Pesanan::with('pelanggan')->orderBy('tanggal_pesanan', 'desc')->get();

        // Data khusus Desainer
        $tugasDesain = \App\Models\Pesanan::with(['pelanggan', 'detailPesanan.layananCetak'])
            ->where('status_pesanan', 'menunggu_desain')
            ->orderBy('tanggal_pesanan', 'asc')->get();

        // Data khusus Operator
        $tugasCetak = \App\Models\Pesanan::with(['pelanggan', 'detailPesanan.layananCetak'])
            ->whereIn('status_pesanan', ['siap_cetak', 'sedang_cetak'])
            ->orderBy('tanggal_pesanan', 'asc')->get();

        // Data khusus Admin (Procurement)
        $daftarBahan = \App\Models\BahanBaku::orderBy('nama_bahan', 'asc')->get();
        $totalBahan = $daftarBahan->count();
        $daftarSupplier = \App\Models\Supplier::orderBy('nama_supplier', 'asc')->get();
        $riwayatPembelian = \App\Models\PembelianBahan::with(['supplier', 'user', 'detail.bahan'])
            ->orderBy('id_pembelian', 'desc')->limit(10)->get();

        return view('dashboard', compact(
            'pesananHariIni', 'pesananAntrean', 'stokKritis', 'riwayatPesanan',
            'tugasDesain', 'tugasCetak', 'pemasukanHariIni', 'daftarBahan', 'totalBahan', 'daftarSupplier', 'riwayatPembelian', 'role', 'rentang'
        ));
    })->name('dashboard');

    Route::post('/bahan-baku/{id}/restock', function($id, \Illuminate\Http\Request $request) {
        $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'id_supplier' => 'required|exists:supplier,id_supplier'
        ]);
        
        $bahan = \App\Models\BahanBaku::findOrFail($id);
        
        // Catat ke pembelian_bahan
        $pembelian = \App\Models\PembelianBahan::create([
            'id_supplier' => $request->id_supplier,
            'id_user' => \Illuminate\Support\Facades\Auth::user()->id_user,
            'tanggal_pembelian' => now()->toDateString(),
            'total_biaya' => 0 // Asumsi harga bisa di-update nanti
        ]);
        
        // Catat ke detail_pembelian
        \App\Models\DetailPembelian::create([
            'id_pembelian' => $pembelian->id_pembelian,
            'id_bahan' => $bahan->id_bahan,
            'jumlah_beli' => $request->jumlah,
            'harga_beli' => 0
        ]);

        // Gunakan Stored Procedure untuk tambah stok!
        \Illuminate\Support\Facades\DB::statement("CALL sp_tambah_stok_bahan(?, ?)", [$bahan->id_bahan, $request->jumlah]);

        return back()->with('success', "Berhasil merestok {$bahan->nama_bahan} dari Supplier!");
    })->name('bahan.restock');

    Route::post('/pesanan/{id}/status', function($id, \Illuminate\Http\Request $request) {
        $pesanan = \App\Models\Pesanan::findOrFail($id);
        $pesanan->status_pesanan = $request->status;
        $pesanan->save();
        return back()->with('success', 'Status pesanan diperbarui!');
    })->name('pesanan.status');

    Route::post('/pesanan/{id}/bayar', function($id) {
        $pesanan = \App\Models\Pesanan::findOrFail($id);
        $pesanan->status_pembayaran = 'lunas';
        $pesanan->save();
        return back()->with('success', 'Pembayaran berhasil dilunaskan!');
    })->name('pesanan.bayar');

    Route::post('/pemakaian-manual', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'id_bahan' => 'required',
            'jumlah' => 'required|numeric|min:1'
        ]);

        \Illuminate\Support\Facades\DB::transaction(function() use ($request) {
            $bahan = \App\Models\BahanBaku::findOrFail($request->id_bahan);
            $bahan->stok_sekarang -= $request->jumlah;
            $bahan->save();

            \App\Models\PemakaianBahanManual::create([
                'id_bahan' => $request->id_bahan,
                'id_user' => auth()->id(),
                'jumlah_keluar' => $request->jumlah,
                'tanggal_keluar' => now(),
                'keterangan' => $request->keterangan ?? 'Pengambilan manual'
            ]);
        });

        return back()->with('success', 'Pemakaian bahan manual berhasil dicatat!');
    })->name('pemakaian.manual');

    Route::get('/pesanan/{id}/struk', function($id) {
        $pesanan = \App\Models\Pesanan::with(['pelanggan', 'detailPesanan.layananCetak', 'user'])->findOrFail($id);
        return view('pesanan.struk', compact('pesanan'));
    })->name('pesanan.struk');

    Route::get('/laporan/keuangan', function(\Illuminate\Http\Request $request) {
        $rentang = $request->query('rentang', 'hari');
        $queryPesanan = \App\Models\Pesanan::with('pelanggan')->orderBy('tanggal_pesanan', 'desc');

        if ($rentang == 'minggu') {
            $queryPesanan->whereBetween('tanggal_pesanan', [\Carbon\Carbon::now()->startOfWeek(), \Carbon\Carbon::now()->endOfWeek()]);
        } elseif ($rentang == 'bulan') {
            $queryPesanan->whereMonth('tanggal_pesanan', \Carbon\Carbon::now()->month)
                         ->whereYear('tanggal_pesanan', \Carbon\Carbon::now()->year);
        } elseif ($rentang == 'tahun') {
            $queryPesanan->whereYear('tanggal_pesanan', \Carbon\Carbon::now()->year);
        } else {
            $queryPesanan->whereDate('tanggal_pesanan', \Carbon\Carbon::today());
        }

        $pesanan = $queryPesanan->get();
        return view('laporan.keuangan', compact('pesanan', 'rentang'));
    })->name('laporan.keuangan');

    Route::get('/laporan/stok', function() {
        $bahan = \App\Models\BahanBaku::orderBy('nama_bahan', 'asc')->get();
        return view('laporan.stok', compact('bahan'));
    })->name('laporan.stok');

    Route::get('/pesanan/create', [\App\Http\Controllers\PesananController::class, 'create'])->name('pesanan.create');
    Route::post('/pesanan', [\App\Http\Controllers\PesananController::class, 'store'])->name('pesanan.store');

    Route::post('/pelanggan/ajax', function(\Illuminate\Http\Request $request) {
        $request->validate([
            'nama_pelanggan' => 'required',
            'no_wa' => 'required',
            'tipe_pelanggan' => 'required|in:umum,studio',
            'alamat' => 'nullable'
        ]);
        
        $p = \App\Models\Pelanggan::create($request->all());
        return response()->json(['success' => true, 'data' => $p]);
    })->name('pelanggan.ajax');
});
