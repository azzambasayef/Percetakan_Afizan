<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    protected $guarded = [];

    public function pembelianBahan() { return $this->hasMany(PembelianBahan::class, 'id_supplier'); }
}
