<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananCetak extends Model
{
    protected $table = 'layanan_cetak';
    protected $primaryKey = 'id_layanan';
    protected $guarded = [];

    public function kategori() { return $this->belongsTo(KategoriLayanan::class, 'id_kategori'); }
    public function bahanBaku() { return $this->belongsTo(BahanBaku::class, 'id_bahan_baku'); }
    public function detailPesanan() { return $this->hasMany(DetailPesanan::class, 'id_layanan'); }
}
