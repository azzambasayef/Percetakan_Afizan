<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - Percetakan Afizan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; font-size: 14px; color: #666; }
        .title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 12px;
        }
        th {
            background-color: #f3f4f6;
            text-align: left;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .total-row {
            font-weight: bold;
            background-color: #f3f4f6;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: flex-end;
        }
        .signature {
            text-align: center;
            width: 200px;
        }
        .signature p { margin: 0; }
        .signature .name {
            margin-top: 80px;
            font-weight: bold;
            text-decoration: underline;
        }
        @media print {
            body { padding: 0; }
            button { display: none; }
        }
        .btn-print {
            padding: 10px 20px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <button class="btn-print" onclick="window.print()">Cetak Laporan (Print)</button>

    <div class="header">
        <h1>PERCETAKAN AFIZAN</h1>
        <p>Jl. Contoh No. 123, Kota Anda, Telp: 0812-3456-7890</p>
    </div>

    <div class="title">
        @php
            $labelRange = 'HARI INI';
            if($rentang == 'minggu') $labelRange = 'MINGGU INI';
            if($rentang == 'bulan') $labelRange = 'BULAN INI';
            if($rentang == 'tahun') $labelRange = 'TAHUN INI';
        @endphp
        LAPORAN REKAPITULASI PENDAPATAN ({{ $labelRange }})<br>
        <span style="font-size: 14px; font-weight: normal;">Dicetak pada: {{ now()->format('d F Y, H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">ID Faktur</th>
                <th width="20%">Tanggal Transaksi</th>
                <th width="25%">Nama Pelanggan</th>
                <th width="15%">Status Pembayaran</th>
                <th class="text-right" width="20%">Total Harga (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($pesanan as $index => $p)
                @if($p->status_pembayaran == 'lunas')
                    @php $grandTotal += $p->total_harga; @endphp
                @endif
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $p->id_pesanan }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pesanan)->format('d M Y, H:i') }}</td>
                    <td>{{ $p->pelanggan->nama_pelanggan ?? '-' }}</td>
                    <td>{{ strtoupper(str_replace('_', ' ', $p->status_pembayaran)) }}</td>
                    <td class="text-right">{{ number_format($p->total_harga, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data transaksi.</td>
                </tr>
            @endforelse
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN (LUNAS):</td>
                <td class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        <div class="signature">
            <p>Mengetahui,</p>
            <p style="margin-top: 5px;">{{ now()->format('d F Y') }}</p>
            <p class="name">Pemilik / Manajer</p>
        </div>
    </div>
    
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
