<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriLayanan extends Model
{
    protected $table = 'kategori_layanan';
    protected $primaryKey = 'id_kategori';
    protected $guarded = [];

    public function layanan() { return $this->hasMany(LayananCetak::class, 'id_kategori'); }
}
