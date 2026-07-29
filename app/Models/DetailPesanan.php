<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    protected $guarded = [];

    public function pesanan() { return $this->belongsTo(Pesanan::class, 'id_pesanan'); }
    public function layananCetak() { return $this->belongsTo(LayananCetak::class, 'id_layanan'); }
}
