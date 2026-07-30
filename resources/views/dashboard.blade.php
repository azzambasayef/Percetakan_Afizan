@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
<style>
    .page-header { margin-bottom: 32px; }
    .page-header h2 { font-size: 24px; font-weight: 600; margin-bottom: 8px; }
    .page-header p { color: var(--text-muted); font-size: 15px; }

    .grid-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.7);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 3px;
        background: linear-gradient(90deg, var(--primary), #c084fc);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .stat-card:hover::before { opacity: 1; }

    .stat-card h3 {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .stat-card .value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-main);
    }

    .welcome-panel {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 32px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .welcome-text h3 {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #e0e7ff;
    }

    .welcome-text p {
        color: var(--text-muted);
        line-height: 1.6;
    }

    .action-btn {
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.5);
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -10px rgba(99, 102, 241, 0.7);
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Overview Sistem</h2>
    <p>Pantau aktivitas pesanan dan ketersediaan bahan baku hari ini.</p>
</div>

@if(session('success'))
<div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #6ee7b7; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
    {{ session('success') }}
</div>
@endif

<div class="welcome-panel" style="margin-bottom: 24px;">
    <div class="welcome-text">
        <h3>Selamat Bekerja, {{ auth()->user()->nama }}!</h3>
        <p>Anda login menggunakan hak akses <strong style="color: #818cf8;">{{ strtoupper($role) }}</strong>.</p>
    </div>
    @if($role == 'cs')
    <div>
        <a href="{{ route('pesanan.create') }}" class="action-btn">+ Input Pesanan Baru</a>
    </div>
    @endif
</div>

<!-- ======================= 
     DASHBOARD OWNER / ADMIN 
======================== -->
@if($role == 'owner')
<div style="display: flex; gap: 12px; margin-bottom: 20px;">
    @php
        $rentang = request('rentang', 'hari');
        $labelWaktu = 'Hari Ini';
        if($rentang == 'minggu') $labelWaktu = 'Minggu Ini';
        if($rentang == 'bulan') $labelWaktu = 'Bulan Ini';
        if($rentang == 'tahun') $labelWaktu = 'Tahun Ini';
    @endphp
    <a href="{{ route('dashboard', ['rentang' => 'hari']) }}" class="action-btn" style="background: {{ $rentang == 'hari' ? '#6366f1' : 'rgba(255,255,255,0.1)' }}; padding: 6px 16px; font-size: 14px; text-decoration: none; border-radius: 20px;">Hari Ini</a>
    <a href="{{ route('dashboard', ['rentang' => 'minggu']) }}" class="action-btn" style="background: {{ $rentang == 'minggu' ? '#6366f1' : 'rgba(255,255,255,0.1)' }}; padding: 6px 16px; font-size: 14px; text-decoration: none; border-radius: 20px;">Minggu Ini</a>
    <a href="{{ route('dashboard', ['rentang' => 'bulan']) }}" class="action-btn" style="background: {{ $rentang == 'bulan' ? '#6366f1' : 'rgba(255,255,255,0.1)' }}; padding: 6px 16px; font-size: 14px; text-decoration: none; border-radius: 20px;">Bulan Ini</a>
    <a href="{{ route('dashboard', ['rentang' => 'tahun']) }}" class="action-btn" style="background: {{ $rentang == 'tahun' ? '#6366f1' : 'rgba(255,255,255,0.1)' }}; padding: 6px 16px; font-size: 14px; text-decoration: none; border-radius: 20px;">Tahun Ini</a>
</div>

<div class="grid-stats">
    <div class="stat-card">
        <h3>Pemasukan {{ $labelWaktu }}</h3>
        <div class="value" style="color: #10b981;">Rp {{ number_format($pemasukanHariIni, 0, ',', '.') }}</div>
    </div>
    <div class="stat-card">
        <h3>Pesanan {{ $labelWaktu }}</h3>
        <div class="value">{{ $pesananHariIni }}</div>
    </div>
    <div class="stat-card">
        <h3>Stok Bahan Kritis</h3>
        <div class="value" style="color: {{ $stokKritis > 0 ? '#ef4444' : '#10b981' }};">{{ $stokKritis }}</div>
    </div>
</div>

<div style="display: flex; gap: 16px; margin-bottom: 24px;">
    <a href="{{ route('laporan.keuangan', ['rentang' => $rentang]) }}" target="_blank" class="action-btn" style="background: #10b981; border: 1px solid #059669;">
        🖨️ Cetak Laporan Keuangan
    </a>
    <a href="{{ route('laporan.stok') }}" target="_blank" class="action-btn" style="background: #3b82f6; border: 1px solid #2563eb;">
        🖨️ Cetak Laporan Stok Bahan Baku
    </a>
</div>
@endif

<!-- ======================= 
     DASHBOARD ADMIN (PENGADAAN)
======================== -->
@if($role == 'admin')
<div class="grid-stats">
    <div class="stat-card">
        <h3>Total Macam Bahan Baku</h3>
        <div class="value" style="color: #6366f1;">{{ $totalBahan }}</div>
    </div>
    <div class="stat-card">
        <h3>Stok Bahan Kritis</h3>
        <div class="value" style="color: {{ $stokKritis > 0 ? '#ef4444' : '#10b981' }};">{{ $stokKritis }}</div>
    </div>
</div>

<div style="display: flex; gap: 16px; margin-bottom: 24px;">
    <a href="{{ route('laporan.stok') }}" target="_blank" class="action-btn" style="background: #3b82f6; border: 1px solid #2563eb;">
        🖨️ Cetak Laporan Stok Bahan Baku
    </a>
</div>

<div class="stat-card" style="padding: 0; overflow: hidden; margin-bottom: 40px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; color: #e0e7ff;">Daftar Bahan Baku & Manajemen Stok</h3>
    </div>
    <div style="max-height: 400px; overflow-y: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead style="background: rgba(0,0,0,0.2);">
            <tr>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">ID Bahan</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Nama Bahan</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Stok Saat Ini</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Batas Minimum</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($daftarBahan as $b)
            @php $isKritis = $b->stok_sekarang <= $b->batas_minimum; @endphp
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05); {{ $isKritis ? 'background: rgba(239,68,68,0.05);' : '' }}">
                <td style="padding: 16px 24px; font-weight: 600;">{{ $b->id_bahan }}</td>
                <td style="padding: 16px 24px;">{{ $b->nama_bahan }}</td>
                <td style="padding: 16px 24px; color: {{ $isKritis ? '#ef4444' : '#10b981' }}; font-weight: bold;">
                    {{ $b->stok_sekarang }} {{ $b->satuan }}
                    @if($isKritis) <span style="font-size: 10px; padding: 2px 6px; background: rgba(239,68,68,0.2); border-radius: 4px; margin-left: 8px;">Kritis</span> @endif
                </td>
                <td style="padding: 16px 24px; color: var(--text-muted);">{{ $b->batas_minimum }} {{ $b->satuan }}</td>
                <td style="padding: 16px 24px;">
                    <form action="{{ route('bahan.restock', $b->id_bahan) }}" method="POST" style="display: flex; gap: 8px; align-items: center; margin: 0;">
                        @csrf
                        <select name="id_supplier" style="width: 120px; padding: 6px; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); color: white; border-radius: 4px;" required>
                            <option value="">Pilih Supplier</option>
                            @foreach($daftarSupplier as $sup)
                                <option value="{{ $sup->id_supplier }}">{{ $sup->nama_supplier }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="jumlah" min="1" placeholder="Jml Masuk" style="width: 80px; padding: 6px; background: rgba(0,0,0,0.2); border: 1px solid var(--glass-border); color: white; border-radius: 4px;" required>
                        <button type="submit" class="action-btn" style="padding: 6px 12px; font-size: 12px; background: #6366f1; border: none; cursor: pointer;">+ Restock</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>

<div class="glass-card" style="margin-top: 24px; padding: 0;">
    <div style="padding: 16px 24px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center; background: rgba(0,0,0,0.2);">
        <h3 style="margin: 0; font-size: 14px; font-weight: 600; letter-spacing: 1px; color: var(--text-muted); text-transform: uppercase;">RIWAYAT RESTOK BAHAN (PEMBELIAN)</h3>
    </div>
    <div style="overflow-x: auto;">
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr style="border-bottom: 1px solid var(--glass-border); background: rgba(0,0,0,0.1);">
                <th style="padding: 16px 24px; font-weight: 500; color: var(--text-muted);">Tanggal</th>
                <th style="padding: 16px 24px; font-weight: 500; color: var(--text-muted);">Supplier</th>
                <th style="padding: 16px 24px; font-weight: 500; color: var(--text-muted);">Bahan Baku</th>
                <th style="padding: 16px 24px; font-weight: 500; color: var(--text-muted);">Jml Masuk</th>
                <th style="padding: 16px 24px; font-weight: 500; color: var(--text-muted);">Admin</th>
            </tr>
        </thead>
        <tbody>
            @foreach($riwayatPembelian as $rb)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px 24px;">{{ $rb->tanggal_pembelian }}</td>
                <td style="padding: 16px 24px;">{{ $rb->supplier->nama_supplier ?? '-' }}</td>
                <td style="padding: 16px 24px;">
                    @foreach($rb->detail as $dp)
                        {{ $dp->bahan->nama_bahan ?? 'Unknown' }} <br>
                    @endforeach
                </td>
                <td style="padding: 16px 24px;">
                    @foreach($rb->detail as $dp)
                        <span style="color: var(--success);">+{{ $dp->jumlah_beli }} {{ $dp->bahan->satuan ?? '' }}</span> <br>
                    @endforeach
                </td>
                <td style="padding: 16px 24px; color: var(--text-muted);">{{ $rb->user->nama ?? '-' }}</td>
            </tr>
            @endforeach
            @if($riwayatPembelian->isEmpty())
            <tr><td colspan="5" style="padding: 24px; text-align: center; color: var(--text-muted);">Belum ada riwayat restok.</td></tr>
            @endif
        </tbody>
    </table>
    </div>
</div>
@endif


<!-- ======================= 
     DASHBOARD DESAINER 
======================== -->
@if($role == 'desainer')
<div class="stat-card" style="padding: 0; overflow: hidden; margin-bottom: 40px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--glass-border);">
        <h3 style="margin: 0; color: #e0e7ff;">Daftar Antrean Desain</h3>
    </div>
    <div style="max-height: 400px; overflow-y: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead style="background: rgba(255,255,255,0.05);">
            <tr>
                <th style="padding: 16px 24px; text-align: left;">ID / Pelanggan</th>
                <th style="padding: 16px 24px; text-align: left;">Detail Layanan</th>
                <th style="padding: 16px 24px; text-align: left;">Status</th>
                <th style="padding: 16px 24px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tugasDesain as $t)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px 24px;"><b>{{ $t->id_pesanan }}</b><br>{{ $t->pelanggan->nama_pelanggan }}</td>
                <td style="padding: 16px 24px;">
                    @foreach($t->detailPesanan as $d)
                        - {{ $d->layananCetak->nama_layanan }} ({{ $d->jumlah }} {{ $d->layananCetak->satuan }})<br>
                    @endforeach
                </td>
                <td style="padding: 16px 24px;"><span style="color: #fbbf24;">Menunggu Desain</span></td>
                <td style="padding: 16px 24px; text-align: center;">
                    <form action="{{ route('pesanan.status', $t->id_pesanan) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="siap_cetak">
                        <button type="submit" class="action-btn" style="padding: 8px 16px; font-size: 12px; background: #10b981;">Selesai Desain</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="padding: 24px; text-align: center;">Bagus! Tidak ada antrean desain saat ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endif

<!-- ======================= 
     DASHBOARD OPERATOR 
======================== -->
@if($role == 'operator')
<div style="margin-bottom: 20px;">
    <button class="action-btn" style="background: #f59e0b;" onclick="document.getElementById('modalBahanManual').style.display='flex'">+ Input Pemakaian Tinta/Bahan Manual</button>
</div>
<div class="stat-card" style="padding: 0; overflow: hidden; margin-bottom: 40px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--glass-border);">
        <h3 style="margin: 0; color: #e0e7ff;">Daftar Antrean Cetak Mesin</h3>
    </div>
    <div style="max-height: 400px; overflow-y: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead style="background: rgba(255,255,255,0.05);">
            <tr>
                <th style="padding: 16px 24px; text-align: left;">ID / Pelanggan</th>
                <th style="padding: 16px 24px; text-align: left;">Detail Cetak & Ukuran</th>
                <th style="padding: 16px 24px; text-align: left;">Status</th>
                <th style="padding: 16px 24px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tugasCetak as $t)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px 24px;"><b>{{ $t->id_pesanan }}</b><br>{{ $t->pelanggan->nama_pelanggan }}</td>
                <td style="padding: 16px 24px;">
                    @foreach($t->detailPesanan as $d)
                        - {{ $d->layananCetak->nama_layanan }} 
                        @if($d->layananCetak->satuan == 'meter')
                            ({{ $d->ukuran_panjang }} x {{ $d->ukuran_lebar }}m)
                        @endif
                        - <b>{{ $d->jumlah }} {{ $d->layananCetak->satuan }}</b><br>
                    @endforeach
                </td>
                <td style="padding: 16px 24px;">
                    <span style="color: {{ $t->status_pesanan == 'siap_cetak' ? '#fbbf24' : '#3b82f6' }};">
                        {{ str_replace('_', ' ', strtoupper($t->status_pesanan)) }}
                    </span>
                </td>
                <td style="padding: 16px 24px; text-align: center; display: flex; gap: 8px; justify-content: center;">
                    @if($t->status_pesanan == 'siap_cetak')
                    <form action="{{ route('pesanan.status', $t->id_pesanan) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="sedang_cetak">
                        <button type="submit" class="action-btn" style="padding: 8px 16px; font-size: 12px; background: #3b82f6;">Mulai Cetak</button>
                    </form>
                    @endif
                    
                    @if($t->status_pesanan == 'sedang_cetak')
                    <form action="{{ route('pesanan.status', $t->id_pesanan) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="selesai">
                        <button type="submit" class="action-btn" style="padding: 8px 16px; font-size: 12px; background: #10b981;">Cetak Selesai</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" style="padding: 24px; text-align: center;">Tidak ada antrean cetak saat ini.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endif

<!-- ======================= 
     DASHBOARD CS / ADMIN 
======================== -->
@if(in_array($role, ['cs', 'admin']))
<div class="stat-card" style="padding: 0; overflow: hidden; margin-bottom: 40px;">
    <div style="padding: 20px 24px; border-bottom: 1px solid var(--glass-border); display: flex; justify-content: space-between; align-items: center;">
        <h3 style="margin: 0; color: #e0e7ff;">Riwayat Pesanan Terbaru</h3>
    </div>
    <div style="max-height: 400px; overflow-y: auto;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead style="background: rgba(255,255,255,0.05);">
            <tr>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">ID Pesanan</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Pelanggan</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Tanggal</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Total Harga</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Status Produksi</th>
                <th style="padding: 16px 24px; text-align: left; color: var(--text-muted); font-weight: 500;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayatPesanan as $pesanan)
            <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <td style="padding: 16px 24px; font-weight: 600;">{{ $pesanan->id_pesanan }}</td>
                <td style="padding: 16px 24px;">{{ $pesanan->pelanggan->nama_pelanggan ?? '-' }}</td>
                <td style="padding: 16px 24px;">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y, H:i') }}</td>
                <td style="padding: 16px 24px; color: #10b981; font-weight: 600;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                <td style="padding: 16px 24px;">
                    <span style="padding: 4px 12px; border-radius: 20px; font-size: 12px; background: rgba(99, 102, 241, 0.2); color: #818cf8;">
                        {{ str_replace('_', ' ', strtoupper($pesanan->status_pesanan)) }}
                    </span>
                </td>
                <td style="padding: 16px 24px; display: flex; gap: 8px;">
                    <a href="{{ route('pesanan.struk', $pesanan->id_pesanan) }}" target="_blank" class="action-btn" style="padding: 6px 12px; font-size: 12px; background: #6366f1; text-decoration: none;">Cetak</a>
                    @if($pesanan->status_pembayaran == 'belum_bayar')
                    <form action="{{ route('pesanan.bayar', $pesanan->id_pesanan) }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="action-btn" style="padding: 6px 12px; font-size: 12px; background: #10b981; border: none; cursor: pointer;">Lunas</button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="padding: 24px; text-align: center; color: var(--text-muted);">Belum ada pesanan yang dibuat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endif

<!-- ======================= 
     MODAL INPUT BAHAN MANUAL (OPERATOR)
======================== -->
@if($role == 'operator')
<div class="modal-overlay" id="modalBahanManual" style="
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6); backdrop-filter: blur(5px);
    display: none; align-items: center; justify-content: center; z-index: 100;
">
    <div class="modal-content" style="
        background: var(--bg-color); border: 1px solid var(--glass-border);
        border-radius: 16px; padding: 30px; width: 100%; max-width: 400px;
    ">
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
            <h3 style="margin: 0; color: #e0e7ff;">Input Pemakaian Manual</h3>
            <button onclick="document.getElementById('modalBahanManual').style.display='none'" style="background:none; border:none; color:white; font-size:20px; cursor:pointer;">×</button>
        </div>
        <form action="{{ route('pemakaian.manual') }}" method="POST">
            @csrf
            <div style="margin-bottom: 15px;">
                <label style="color: var(--text-muted); font-size: 13px; display:block; margin-bottom:5px;">Pilih Bahan Baku (Tinta/Lem)</label>
                <select name="id_bahan" class="form-control" style="width: 100%; background: rgba(15, 23, 42, 0.6); color: white; border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px;" required>
                    @foreach(\App\Models\BahanBaku::where('tipe_pengurangan', 'manual')->get() as $bahan)
                        <option value="{{ $bahan->id_bahan }}">{{ $bahan->nama_bahan }} (Stok: {{ $bahan->stok_sekarang }} {{ $bahan->satuan }})</option>
                    @endforeach
                </select>
            </div>
            <div style="margin-bottom: 15px;">
                <label style="color: var(--text-muted); font-size: 13px; display:block; margin-bottom:5px;">Jumlah Diambil</label>
                <input type="number" name="jumlah" class="form-control" style="width: 100%; background: rgba(15, 23, 42, 0.6); color: white; border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px;" required min="1">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="color: var(--text-muted); font-size: 13px; display:block; margin-bottom:5px;">Keterangan</label>
                <input type="text" name="keterangan" placeholder="Misal: Tinta Cyan habis" class="form-control" style="width: 100%; background: rgba(15, 23, 42, 0.6); color: white; border: 1px solid var(--glass-border); padding: 10px; border-radius: 8px;">
            </div>
            <button type="submit" class="action-btn" style="width: 100%;">Simpan Pemakaian</button>
        </form>
    </div>
</div>
@endif

@endsection
