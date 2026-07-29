<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Stok Gudang - Percetakan Afizan</title>
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
        .text-center { text-align: center; }
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
        .kritis { background-color: #fee2e2; }
        @media print {
            body { padding: 0; }
            button { display: none; }
            .kritis { background-color: #fee2e2 !important; -webkit-print-color-adjust: exact; }
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
        LAPORAN SISA STOK BAHAN BAKU<br>
        <span style="font-size: 14px; font-weight: normal;">Dicetak pada: {{ now()->format('d F Y, H:i') }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="15%">ID Bahan</th>
                <th width="35%">Nama Bahan Baku</th>
                <th class="text-center" width="15%">Batas Minimum</th>
                <th class="text-center" width="15%">Stok Saat Ini</th>
                <th class="text-center" width="15%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bahan as $index => $b)
                @php $isKritis = $b->stok_sekarang <= $b->batas_minimum; @endphp
                <tr class="{{ $isKritis ? 'kritis' : '' }}">
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $b->id_bahan }}</td>
                    <td>{{ $b->nama_bahan }}</td>
                    <td class="text-center">{{ $b->batas_minimum }} {{ $b->satuan }}</td>
                    <td class="text-center"><strong>{{ $b->stok_sekarang }} {{ $b->satuan }}</strong></td>
                    <td class="text-center">
                        @if($isKritis)
                            <span style="color: #dc2626; font-weight: bold;">KRITIS</span>
                        @else
                            <span style="color: #16a34a;">AMAN</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data bahan baku.</td>
                </tr>
            @endforelse
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
