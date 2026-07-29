<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    protected $guarded = [];

    public function pelanggan() { return $this->belongsTo(Pelanggan::class, 'id_pelanggan'); }
    public function user() { return $this->belongsTo(User::class, 'id_user'); }
    public function detailPesanan() { return $this->hasMany(DetailPesanan::class, 'id_pesanan'); }
}
