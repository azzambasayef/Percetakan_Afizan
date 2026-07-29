<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianBahan extends Model
{
    protected $table = 'pembelian_bahan';
    protected $primaryKey = 'id_pembelian';
    protected $guarded = [];

    public function supplier() { return $this->belongsTo(Supplier::class, 'id_supplier'); }
    public function user() { return $this->belongsTo(User::class, 'id_user'); }
    public function detail() { return $this->hasMany(DetailPembelian::class, 'id_pembelian'); }
}
