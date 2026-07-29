<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPembelian extends Model
{
    protected $table = 'detail_pembelian';
    protected $primaryKey = 'id_detail_pembelian';
    protected $guarded = [];

    public function pembelian() { return $this->belongsTo(PembelianBahan::class, 'id_pembelian'); }
    public function bahan() { return $this->belongsTo(BahanBaku::class, 'id_bahan'); }
}
