<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faktur Penjualan - {{ $pesanan->id_pesanan }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #000;
            background: #fff;
            font-size: 14px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }
        .company-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo-placeholder {
            font-size: 32px;
            font-weight: bold;
            color: #2c3e50;
            letter-spacing: -1px;
        }
        .logo-placeholder span {
            color: #e74c3c;
        }
        .company-details p {
            margin: 2px 0;
            font-size: 12px;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            margin: 0 0 10px 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .invoice-meta {
            border-collapse: collapse;
            float: right;
        }
        .invoice-meta td {
            border: 1px solid #000;
            padding: 5px 15px;
            font-size: 12px;
        }
        .customer-info {
            margin-bottom: 20px;
            font-weight: bold;
        }
        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.items th, table.items td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
        }
        table.items th {
            background-color: #f8f9fa;
        }
        table.items td:nth-child(3) {
            text-align: left;
        }
        table.items td:nth-child(6), table.items td:nth-child(7) {
            text-align: right;
        }
        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .terbilang-box {
            border: 1px solid #000;
            padding: 5px;
            margin-bottom: 10px;
            font-style: italic;
        }
        .bank-info {
            font-size: 11px;
            line-height: 1.4;
        }
        .totals {
            width: 300px;
        }
        .totals-table {
            width: 100%;
            border-collapse: collapse;
        }
        .totals-table td {
            border: 1px solid #000;
            padding: 5px 10px;
        }
        .totals-table td:first-child {
            font-weight: bold;
            text-align: right;
        }
        .totals-table td:last-child {
            text-align: right;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            text-align: center;
        }
        .signatures div {
            width: 200px;
        }
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
        }
        
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        
        .print-btn {
            display: block;
            width: 200px;
            margin: 20px auto;
            padding: 10px;
            background: #4f46e5;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            border: none;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn no-print">Cetak Faktur</button>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <div class="logo-placeholder"><span>a</span>fizan</div>
                <div class="company-details">
                    <strong>PRINTING - ADVERTISING - HOME DECOR</strong>
                    <p>Jalan Darussalam No.100</p>
                    <p>Lhokseumawe - Aceh | 24315</p>
                    <p>08116850180 | afizanprinting@gmail.com</p>
                </div>
            </div>
            <div class="invoice-title">
                <h2>Faktur Penjualan</h2>
                <table class="invoice-meta">
                    <tr>
                        <td style="background: #f8f9fa;">Tanggal</td>
                        <td style="background: #f8f9fa;">Nomor</td>
                    </tr>
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y') }}</td>
                        <td>AFIZAN.{{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d.m.y') }}-{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="customer-info">
            Kepada : {{ $pesanan->pelanggan->nama_pelanggan ?? 'Umum' }}
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Produk</th>
                    <th width="35%">Keterangan</th>
                    <th width="8%">Qty</th>
                    <th width="10%">Satuan</th>
                    <th width="12%">Harga</th>
                    <th width="15%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pesanan->detailPesanan as $index => $detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ ucfirst($pesanan->pelanggan->tipe_pelanggan ?? 'Umum') }}</td>
                    <td>
                        {{ $detail->layananCetak->nama_layanan }}
                        @if($detail->layananCetak->satuan == 'meter' || $detail->layananCetak->satuan == 'm²')
                            uk {{ $detail->ukuran_panjang }}x{{ $detail->ukuran_lebar }}m
                        @endif
                    </td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>{{ $detail->layananCetak->satuan }}</td>
                    <td>{{ number_format(($detail->subtotal / $detail->jumlah), 0, ',', '.') }}</td>
                    <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <div style="width: 60%;">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <strong>Terbilang:</strong>
                    <div class="terbilang-box" style="flex-grow: 1;">
                        <!-- Simplifikasi terbilang, idealnya pakai library terbilang PHP -->
                        <em>Sesuai tagihan</em>
                    </div>
                </div>
                
                <div class="bank-info">
                    <strong>Perhatian: {{ $pesanan->status_pembayaran == 'lunas' ? 'Lunas' : 'Belum Lunas' }}</strong><br>
                    *Pembayaran yang sah hanya di transfer<br>
                    ke rekening dibawah ini:<br>
                    BSI : 7308616813<br>
                    Bank Aceh : 0300 1940 0038 12<br>
                    an. Afizan Printing
                </div>
            </div>
            
            <div class="totals">
                <table class="totals-table">
                    <tr>
                        <td>Total</td>
                        <td>{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Bayar</td>
                        <td>{{ $pesanan->status_pembayaran == 'lunas' ? number_format($pesanan->total_harga, 0, ',', '.') : '0' }}</td>
                    </tr>
                    <tr>
                        <td>Sisa</td>
                        <td>{{ $pesanan->status_pembayaran == 'lunas' ? '0' : number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="signatures">
            <div>
                Pelanggan
                <div class="signature-line">{{ $pesanan->pelanggan->nama_pelanggan ?? '.....................' }}</div>
            </div>
            <div style="position: relative;">
                Penerima
                @if($pesanan->status_pembayaran == 'lunas')
                <div style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%) rotate(-15deg); color: #2563eb; border: 3px solid #2563eb; padding: 5px 15px; border-radius: 50%; font-weight: bold; font-size: 20px; opacity: 0.7;">
                    LUNAS
                </div>
                @endif
                <div class="signature-line">{{ $pesanan->user->nama ?? 'Admin Afizan' }}</div>
            </div>
        </div>
    </div>
</body>
</html>
