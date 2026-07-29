@extends('layouts.app')

@section('title', 'Input Pesanan Baru')

@section('styles')
<style>
    .card {
        background: var(--glass-bg);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 30px;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
    }

    .card-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 20px;
        color: #e0e7ff;
        border-bottom: 1px solid var(--glass-border);
        padding-bottom: 12px;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }

    .form-group {
        flex: 1;
        min-width: 200px;
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-control {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid var(--glass-border);
        color: var(--text-main);
        padding: 12px 16px;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        outline: none;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
    }

    .form-control:disabled, .form-control[readonly] {
        background: rgba(255,255,255,0.05);
        color: var(--text-muted);
        cursor: not-allowed;
    }

    select.form-control option {
        background: var(--bg-color);
        color: var(--text-main);
    }

    .item-row {
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid var(--glass-border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        position: relative;
    }

    .btn-remove-item {
        position: absolute;
        top: 12px;
        right: 12px;
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        transition: all 0.2s;
    }
    
    .btn-remove-item:hover {
        background: #ef4444;
        color: white;
    }

    .btn-add-item {
        background: rgba(99, 102, 241, 0.15);
        color: #818cf8;
        border: 1px dashed rgba(99, 102, 241, 0.4);
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        margin-bottom: 24px;
    }

    .btn-add-item:hover {
        background: rgba(99, 102, 241, 0.25);
        border-color: var(--primary);
        color: #a5b4fc;
    }

    .total-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 24px;
        padding-top: 24px;
        border-top: 1px solid var(--glass-border);
    }

    .total-label {
        font-size: 18px;
        color: var(--text-muted);
    }

    .total-value {
        font-size: 32px;
        font-weight: 700;
        color: #10b981; /* Green color for money */
    }

    .btn-submit {
        background: linear-gradient(135deg, var(--primary), #8b5cf6);
        color: white;
        border: none;
        padding: 14px 32px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px -10px rgba(99, 102, 241, 0.5);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 25px -10px rgba(99, 102, 241, 0.7);
    }

    .subtotal-display {
        font-size: 18px;
        font-weight: 600;
        color: #818cf8;
        margin-top: 28px;
        display: block;
    }

    /* Modal Styles */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(5px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 100;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .modal-overlay.active {
        display: flex;
        opacity: 1;
    }

    .modal-content {
        background: var(--bg-color);
        border: 1px solid var(--glass-border);
        border-radius: 16px;
        padding: 30px;
        width: 100%;
        max-width: 400px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        transform: translateY(-20px);
        transition: transform 0.3s ease;
    }

    .modal-overlay.active .modal-content {
        transform: translateY(0);
    }

    .btn-close-modal {
        float: right;
        background: transparent;
        color: var(--text-muted);
        border: none;
        font-size: 20px;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 24px; font-weight: 600; margin-bottom: 8px;">Input Pesanan Baru</h2>
    <p style="color: var(--text-muted); font-size: 15px;">Pilih pelanggan dan tentukan detail layanan cetak.</p>
</div>

@if($errors->any())
<div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
    <ul style="margin-left: 20px;">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('error'))
<div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #fca5a5; padding: 16px; border-radius: 12px; margin-bottom: 24px;">
    <strong>Gagal: </strong> {{ session('error') }}
</div>
@endif

<form action="{{ route('pesanan.store') }}" method="POST" id="pesananForm">
    @csrf
    
    <!-- Bagian 1: Data Utama -->
    <div class="card">
        <h3 class="card-title">1. Informasi Umum</h3>
        
        <div class="form-row">
            <div class="form-group" style="flex: 1.5;">
                <label>Pelanggan</label>
                <div style="display: flex; gap: 10px;">
                    <select name="id_pelanggan" id="id_pelanggan" class="form-control" style="flex-grow: 1;" required onchange="calculateAll()">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $p)
                            <option value="{{ $p->id_pelanggan }}" data-tipe="{{ $p->tipe_pelanggan }}">{{ $p->nama_pelanggan }} ({{ ucfirst($p->tipe_pelanggan) }})</option>
                        @endforeach
                    </select>
                    <button type="button" onclick="openModal()" class="action-btn" style="padding: 12px 16px;">+ Baru</button>
                </div>
            </div>
            <div class="form-group">
                <label>Sumber Pesanan</label>
                <select name="sumber_pesanan" class="form-control" required>
                    <option value="datang_langsung">Datang ke Toko (Offline)</option>
                    <option value="whatsapp">Via WhatsApp</option>
                    <option value="instagram">Via Instagram</option>
                    <option value="facebook">Via Facebook</option>
                </select>
            </div>
            <div class="form-group">
                <label>Status Pembayaran</label>
                <select name="status_pembayaran" class="form-control" required>
                    <option value="belum_bayar">Belum Lunas (Bayar Nanti)</option>
                    <option value="lunas">Lunas (Bayar Tunai/Transfer)</option>
                </select>
            </div>
        </div>
        
        <div class="form-group" style="width: 100%;">
            <label>Catatan Pesanan (Opsional)</label>
            <input type="text" name="catatan" class="form-control" placeholder="Misal: Tolong diprioritaskan, diambil besok pagi">
        </div>
    </div>

    <!-- Bagian 2: Detail Layanan -->
    <div class="card">
        <h3 class="card-title">2. Detail Layanan Cetak</h3>
        
        <div id="items-container">
            <!-- Items akan ditambah via JS -->
        </div>

        <button type="button" class="btn-add-item" onclick="addItem()">+ Tambah Layanan Cetak</button>

        <div class="total-section">
            <span class="total-label">Total Harga:</span>
            <span class="total-value" id="displayTotal">Rp 0</span>
        </div>
    </div>

    <div style="text-align: right; margin-bottom: 60px;">
        <button type="submit" class="btn-submit">Simpan Pesanan & Cetak Struk</button>
    </div>
</form>

<!-- Modal Tambah Pelanggan -->
<div class="modal-overlay" id="modalPelanggan">
    <div class="modal-content">
        <button type="button" class="btn-close-modal" onclick="closeModal()">×</button>
        <h3 class="card-title" style="border:none; padding-bottom: 0;">Tambah Pelanggan Baru</h3>
        
        <form id="formPelangganBaru">
            <div class="form-group" style="margin-bottom: 12px;">
                <label>Nama Pelanggan</label>
                <input type="text" id="p_nama" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom: 12px;">
                <label>No. WhatsApp</label>
                <input type="text" id="p_nowa" class="form-control" required>
            </div>
            <div class="form-group" style="margin-bottom: 12px;">
                <label>Tipe Pelanggan</label>
                <select id="p_tipe" class="form-control" required>
                    <option value="umum">Umum</option>
                    <option value="studio">Studio / Rekanan</option>
                </select>
            </div>
            <div class="form-group" style="margin-bottom: 16px;">
                <label>Alamat (Opsional)</label>
                <textarea id="p_alamat" class="form-control" rows="2" placeholder="Jalan, Kecamatan, Kota"></textarea>
            </div>
            <button type="button" onclick="simpanPelanggan()" class="btn-submit" style="width: 100%; margin-top: 5px;" id="btnSimpanPelanggan">Simpan Pelanggan</button>
        </form>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Data JSON dari Backend
    const layanans = @json($layanans);
    let itemIndex = 0;

    // Format Rupiah
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
    }

    function addItem() {
        const container = document.getElementById('items-container');
        
        // Buat options untuk select layanan
        let layananOptions = '<option value="">-- Pilih Layanan --</option>';
        layanans.forEach(l => {
            layananOptions += `<option value="${l.id_layanan}">${l.nama_layanan}</option>`;
        });

        const html = `
            <div class="item-row" id="item_row_${itemIndex}">
                <button type="button" class="btn-remove-item" onclick="removeItem(${itemIndex})" title="Hapus Layanan">×</button>
                
                <div class="form-row">
                    <div class="form-group" style="flex: 2;">
                        <label>Layanan Cetak</label>
                        <select name="items[${itemIndex}][id_layanan]" class="form-control layanan-select" onchange="handleLayananChange(${itemIndex})" required>
                            ${layananOptions}
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Punya Desain?</label>
                        <select name="items[${itemIndex}][punya_desain]" class="form-control desain-select" onchange="calculateAll()" required>
                            <option value="ya">Sudah Ada Desain</option>
                            <option value="tidak">Belum Ada (Tambah Biaya)</option>
                        </select>
                    </div>
                </div>

                <div class="form-row size-row" id="size_row_${itemIndex}">
                    <!-- Panjang dan Lebar hanya muncul jika satuan meter -->
                    <div class="form-group pjg-group" style="display: none;">
                        <label>Panjang (m)</label>
                        <input type="number" step="0.01" name="items[${itemIndex}][ukuran_panjang]" class="form-control input-panjang" value="1" onkeyup="calculateAll()" onchange="calculateAll()">
                    </div>
                    <div class="form-group lbr-group" style="display: none;">
                        <label>Lebar (m)</label>
                        <input type="number" step="0.01" name="items[${itemIndex}][ukuran_lebar]" class="form-control input-lebar" value="1" onkeyup="calculateAll()" onchange="calculateAll()">
                    </div>
                    
                    <div class="form-group">
                        <label>Jumlah (Qty)</label>
                        <input type="number" name="items[${itemIndex}][jumlah]" class="form-control input-jumlah" value="1" min="1" required onkeyup="calculateAll()" onchange="calculateAll()">
                    </div>
                    
                    <div class="form-group" style="justify-content: center; align-items: flex-end;">
                        <span class="subtotal-display" id="subtotal_display_${itemIndex}">Rp 0</span>
                    </div>
                </div>
                
                <div class="info-harga" id="info_harga_${itemIndex}" style="font-size: 12px; color: var(--text-muted); margin-top: -5px; margin-bottom: 10px;">
                    <!-- Info harga per unit muncul disini -->
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        itemIndex++;
    }

    function removeItem(index) {
        document.getElementById(`item_row_${index}`).remove();
        calculateAll();
    }

    function handleLayananChange(index) {
        const selectElement = document.querySelector(`#item_row_${index} .layanan-select`);
        const idLayanan = selectElement.value;
        const sizeRow = document.getElementById(`size_row_${index}`);
        const pjgGroup = sizeRow.querySelector('.pjg-group');
        const lbrGroup = sizeRow.querySelector('.lbr-group');

        if (!idLayanan) {
            pjgGroup.style.display = 'none';
            lbrGroup.style.display = 'none';
            calculateAll();
            return;
        }

        const layanan = layanans.find(l => l.id_layanan == idLayanan);
        
        // Show/hide Panjang Lebar based on satuan
        if (layanan.satuan === 'meter' || layanan.satuan === 'm²') {
            pjgGroup.style.display = 'flex';
            lbrGroup.style.display = 'flex';
        } else {
            pjgGroup.style.display = 'none';
            lbrGroup.style.display = 'none';
        }

        calculateAll();
    }

    function calculateAll() {
        const pelangganSelect = document.getElementById('id_pelanggan');
        if (!pelangganSelect.value) return;

        const tipePelanggan = pelangganSelect.options[pelangganSelect.selectedIndex].getAttribute('data-tipe');
        
        let grandTotal = 0;
        const rows = document.querySelectorAll('.item-row');
        
        rows.forEach(row => {
            const index = row.id.split('_')[2];
            const idLayanan = row.querySelector('.layanan-select').value;
            
            if (!idLayanan) return;

            const layanan = layanans.find(l => l.id_layanan == idLayanan);
            const punyaDesain = row.querySelector('.desain-select').value;
            const jumlah = parseFloat(row.querySelector('.input-jumlah').value) || 0;
            const panjang = parseFloat(row.querySelector('.input-panjang').value) || 0;
            const lebar = parseFloat(row.querySelector('.input-lebar').value) || 0;
            const infoHarga = document.getElementById(`info_harga_${index}`);

            // Tentukan Harga Dasar
            const hargaDasar = (tipePelanggan === 'studio') ? parseFloat(layanan.harga_studio) : parseFloat(layanan.harga_umum);
            const biayaDesain = (punyaDesain === 'tidak') ? parseFloat(layanan.biaya_desain) : 0;
            
            let subtotal = 0;
            let detailTeks = `Harga Dasar: ${formatRupiah(hargaDasar)}`;
            
            if (biayaDesain > 0) detailTeks += ` + Desain: ${formatRupiah(biayaDesain)}`;

            if (layanan.satuan === 'meter' || layanan.satuan === 'm²') {
                const luas = panjang * lebar;
                subtotal = ((hargaDasar * luas) + biayaDesain) * jumlah;
                detailTeks += ` | Luas: ${luas} m²`;
            } else {
                subtotal = (hargaDasar + biayaDesain) * jumlah;
            }

            infoHarga.innerHTML = detailTeks;
            document.getElementById(`subtotal_display_${index}`).innerHTML = formatRupiah(subtotal);
            
            grandTotal += subtotal;
        });

        document.getElementById('displayTotal').innerHTML = formatRupiah(grandTotal);
    }

    // Add first item by default
    window.onload = function() {
        addItem();
    };

    // Modal logic
    function openModal() {
        document.getElementById('modalPelanggan').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modalPelanggan').classList.remove('active');
    }

    async function simpanPelanggan() {
        const btn = document.getElementById('btnSimpanPelanggan');
        const nama = document.getElementById('p_nama').value;
        const no_wa = document.getElementById('p_nowa').value;
        const tipe = document.getElementById('p_tipe').value;
        const alamat = document.getElementById('p_alamat').value;

        if(!nama || !no_wa) { alert('Nama dan No WA harus diisi!'); return; }

        btn.innerHTML = 'Menyimpan...';
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('pelanggan.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ nama_pelanggan: nama, no_wa: no_wa, tipe_pelanggan: tipe, alamat: alamat })
            });
            const result = await response.json();
            
            if(result.success) {
                // Add to select and select it
                const select = document.getElementById('id_pelanggan');
                const opt = document.createElement('option');
                opt.value = result.data.id_pelanggan;
                opt.setAttribute('data-tipe', result.data.tipe_pelanggan);
                opt.innerHTML = `${result.data.nama_pelanggan} (${result.data.tipe_pelanggan.charAt(0).toUpperCase() + result.data.tipe_pelanggan.slice(1)})`;
                select.appendChild(opt);
                select.value = result.data.id_pelanggan;
                
                calculateAll();
                closeModal();
                
                // reset form
                document.getElementById('formPelangganBaru').reset();
            } else {
                alert('Gagal menyimpan pelanggan.');
            }
        } catch(e) {
            alert('Error jaringan!');
        }

        btn.innerHTML = 'Simpan Pelanggan';
        btn.disabled = false;
    }
</script>
@endsection
