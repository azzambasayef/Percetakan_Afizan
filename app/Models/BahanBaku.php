<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanBaku extends Model
{
    protected $table = 'bahan_baku';
    protected $primaryKey = 'id_bahan';
    protected $guarded = [];

    public function layanan() { return $this->hasMany(LayananCetak::class, 'id_bahan_baku'); }
    public function pemakaianManual() { return $this->hasMany(PemakaianBahanManual::class, 'id_bahan'); }
    public function detailPembelian() { return $this->hasMany(DetailPembelian::class, 'id_bahan'); }
}
